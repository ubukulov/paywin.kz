<?php

namespace App\Console\Commands\Trendo;

use App\Classes\Trendo;
use App\Models\ProductCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class getCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trendo:get-categories';

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
        $categories = $trendo->getCategories();
        DB::beginTransaction();
        try {
            foreach ($categories as $category) {
                $productCategory = ProductCategory::whereName($category)->first();
                if (!$productCategory) {
                    ProductCategory::create([
                        'name' => $category,
                        'parent_id' => 0
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("Error: " . $exception->getMessage());
        }
    }
}
