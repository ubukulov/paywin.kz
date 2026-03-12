<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Обновление данных профиля.
     */
    public function updateProfile(User $user, array $data): bool
    {
        $user->update(['email' => $data['email']]);
        return $user->userProfile->update($data);
    }

    /**
     * Смена пароля пользователя.
     */
    public function changePassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword)
        ]);
    }
}
