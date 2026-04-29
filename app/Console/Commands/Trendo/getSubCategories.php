<?php

namespace App\Console\Commands\Trendo;

use App\Classes\Trendo;
use App\Models\ProductCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class getSubCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trendo:get-sub-categories {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $trendo = new Trendo();
        $id = $this->argument('id');
        DB::beginTransaction();
        try {
            $productCategory = ProductCategory::findOrFail($id);

            $subCategories = $trendo->getSubCategories($productCategory->name);

            if (empty($subCategories)) {
                $this->warn("Подкатегории для '{$productCategory->name}' не найдены.");
                return;
            }

            $currentParentId = $productCategory->id;

            foreach ($subCategories as $categoryName) {
                // Ищем категорию или создаем её, если её нет
                // Это избавит от ошибок дублирования и проблем с индексами
                $category = ProductCategory::firstOrCreate(
                    ['name' => $categoryName],
                    ['parent_id' => $currentParentId]
                );

                // Для следующей итерации текущая категория становится родительской
                $currentParentId = $category->id;
            }

            DB::commit();
            $this->info("Цепочка категорий успешно обработана.");
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("Error: " . $exception->getMessage(). "Line: " . $exception->getLine());
            $this->error("Ошибка при выполнении команды. Проверьте логи.");
        }
    }
}
