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

            for ($i = 0; $i < count($subCategories); $i++) {
                if ($i == 0 && !ProductCategory::whereName($subCategories[0])->exists()) {
                    ProductCategory::create([
                        'name' => $subCategories[0],
                        'parent_id' => $productCategory->id
                    ]);
                    continue;
                }

                $subSubCategory = ProductCategory::whereName($subCategories[$i-1])->first();

                ProductCategory::create([
                    'name' => $subCategories[$i],
                    'parent_id' => $subSubCategory->id
                ]);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("Error: " . $exception->getMessage(). "Line: " . $exception->getLine());
        }
    }
}
