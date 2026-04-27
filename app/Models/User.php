<?php

namespace App\Models;

use App\Enums\TransactionEnum;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Str;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $sum = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password', 'user_type', 'balance'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function exists($phone)
    {
        $user = User::where(['phone' => $phone])->first();
        return ($user) ? true : false;
    }

    public function address() : HasMany
    {
        return $this->hasMany(PartnerAddress::class, 'partner_id');
    }

    public function images() : HasMany
    {
        return $this->hasMany(PartnerImage::class, 'partner_id');
    }

    public function discounts() : HasMany
    {
        return $this->hasMany(UserDiscount::class)->whereStatus('active');
    }

    public function transactions() : HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function orders() : HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function gifts() : HasMany
    {
        return $this->hasMany(UserGift::class);
    }

    public function payoutRequests() : HasMany
    {
        return $this->hasMany(PayoutRequest::class);
    }

    public function warehouses() : HasMany
    {
        return $this->hasMany(PartnerWarehouse::class);
    }

    public function shares()
    {
        return $this->hasMany(Share::class, 'partner_id')
            ->whereDate('to_date', '>', Carbon::now());
    }

    public function sharesWithoutPromocodes(): HasMany
    {
        return $this->hasMany(Share::class, 'partner_id')
            ->whereDate('to_date', '>', Carbon::now())
            ->where('type', '<>', TransactionEnum::PROMOCODE->value);
    }

    public function userProfile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    public function partnerProfile(): HasOne
    {
        return $this->hasOne(PartnerProfile::class, 'partner_id');
    }

    public function createProfile()
    {
        if ($this->user_type === 'partner') {
            return $this->partnerProfile()->create(array_merge([
                'company' => 'Новая компания',
                'category_id'   => 4,
            ]));
        }

        return $this->userProfile()->create(array_merge([
            'user_id' => $this->id,
        ]));
    }

    public function getDiscountForUser()
    {
        $user_discount = UserDiscount::where(['user_id' => $this->id, 'status' => 'active'])->first();
        return ($user_discount) ?: null;
    }

    public function givePrize($shares, $payment)
    {
        if (count($shares) != 0) {
            foreach($shares->shuffle() as $share) {
                if(($share->from_order >= $payment->amount) && ($payment->amount <= $share->to_order)) {
                    $prize = new UserGift();
                    $prize->payment_id = $payment->id;
                    $prize->user_id = $payment->user_id;
                    $prize->share_id = $share->id;
                    $prize->cnt = 1;
                    $prize->status = 'got';
                    $prize->save();

                    $share->cnt--;
                    $share->save();
                    break;
                }
            }
        }
    }

    public function getWarehouses()
    {
        return PartnerWarehouse::where(['partner_id' => $this->id])
            ->with('city')
            ->get();
    }

    /**
     * Получить текущий баланс (из кэшированного поля)
     */
    public function getBalanceAttribute()
    {
        return $this->attributes['balance'] ?? 0;
    }

    /**
     * Пересчитать баланс на основе истории транзакций (если случился сбой)
     */
    public function recalculateBalance()
    {
        $actualBalance = $this->transactions()->sum('amount');

        $this->update(['balance' => $actualBalance]);

        return $actualBalance;
    }

    /**
     * Универсальный метод изменения баланса с записью в историю
     * * @param float $amount Сумма (положительная для прихода, отрицательная для расхода)
     * @param TransactionEnum $type Тип операции (из Enums)
     * @param Model|null $source Объект-источник (Order, Share, Referral и т.д.)
     * @param string|null $description Комментарий для пользователя
     * @param array|null $data Дополнительные метаданные (например, ['bank_transaction_id' => '...'])
     * @return float Новый баланс
     */
    public function changeBalance(float $amount, TransactionEnum $type, ?Model $source = null, ?string $description = null, ?array $data = null): float
    {
        // Если сумма 0, ничего не делаем
        if ($amount == 0) return $this->balance;

        return DB::transaction(function () use ($amount, $type, $source, $description, $data) {
            // 1. Блокируем строку пользователя в БД для записи (защита от Race Condition)
            $user = DB::table('users')->where('id', $this->id)->lockForUpdate()->first();

            $balanceBefore = $user->balance;
            $balanceAfter = $balanceBefore + $amount;

            // 2. Обновляем баланс в таблице users
            DB::table('users')->where('id', $this->id)->update([
                'balance' => $balanceAfter,
                'updated_at' => now(),
            ]);

            // 3. Создаем запись в таблице транзакций (Аудит)
            $this->transactions()->create([
                'amount' => $amount,
                'type' => $type->value,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'source_id' => $source?->id,
                'source_type' => $source ? get_class($source) : null,
                'description' => $description,
                'data' => $data
            ]);

            // 4. Обновляем баланс в текущем объекте модели (чтобы Auth::user()->balance сразу изменился)
            $this->balance = $balanceAfter;

            return $balanceAfter;
        });
    }

    /**
     * Считает баланс пользователя, доступный только у конкретного партнера
     */
    public function getBalanceForPartner(int $partnerId): float
    {
        return (float) $this->transactions()
            ->where(function ($query) use ($partnerId) {
                $query->where(function($q) use ($partnerId) {
                    // Ищем доходы от этого партнера (source_id = partner_id)
                    $q->where('source_id', $partnerId)
                        ->where('source_type', User::class);
                })
                    ->orWhere(function($q) use ($partnerId) {
                        // Ищем уже совершенные траты у ЭТОГО же партнера
                        // (чтобы вычесть их из суммы доходов)
                        $q->where('source_id', $partnerId)
                            ->where('source_type', User::class);
                    });
            })
            ->sum('amount');
    }
}
