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
            foreach ($subCategories as $subCategory) {
                if (count($subCategory) == 1) {
                    if (!ProductCategory::whereName($subCategory[0])->exists()) {
                        ProductCategory::create([
                            'name' => $subCategory[0],
                            'parent_id' => $productCategory->id
                        ]);
                    }
                } elseif (count($subCategory) == 0) {
                    continue;
                }else {
                    $subSubCategory = ProductCategory::whereName($subCategory[0])->first();

                    if (!($subSubCategory && $subSubCategory->parent->parent_id == 0)) {
                        $subSubCategory = ProductCategory::create([
                            'name' => $subCategory[0],
                            'parent_id' => $productCategory->id
                        ]);
                    }

                    ProductCategory::create([
                        'name' => $subCategory[1],
                        'parent_id' => $subSubCategory->id
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("Error: " . $exception->getMessage(). "Line: " . $exception->getLine());
        }
    }
}
