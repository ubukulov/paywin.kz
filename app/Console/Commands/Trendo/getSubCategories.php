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

            foreach ($subCategories as $subCategoryArr) {
                if (empty($subCategoryArr) || !is_array($subCategoryArr)) {
                    continue;
                }

                foreach ($subCategoryArr as $index=>$categoryName) {
                    if ($index == 0) {
                        $category = ProductCategory::firstOrCreate([
                            'name' => (string)$categoryName,
                            'parent_id' => $productCategory->id
                        ]);
                    } else {
                        $subSubCategory = ProductCategory::whereName($subCategories[$index-1])->first();
                        $category = ProductCategory::firstOrCreate([
                            'name' => (string)$categoryName,
                            'parent_id' => $subSubCategory->id
                        ]);
                    }
                }
            }
            DB::commit();
            $this->info("Все категории и подкатегории успешно импортированы.");
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("Error: " . $exception->getMessage(). "Line: " . $exception->getLine());
            $this->error("Ошибка: " . $exception->getMessage());
        }
    }
}
