<?php


namespace App\Services;

use App\Models\Category;
use App\Models\Studio;
use App\Models\SubCategory;

class CategoryService
{

    public static function checkCategory($studio_uuid, $category)
    {

        try {
            $cat = Category::where('studio_id', $studio_uuid)
                ->where('category', $category)
                ->first() ?? null;
            return $cat->category;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public static function checkSubCategory($category_id, $sub_category)
    {
        try {
            $sub = SubCategory::where('category_id', $category_id)
                ->where('sub_category', $sub_category)
                ->first() ?? null;

                return $sub->sub_category;

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Serviço que registra a categorya na DB.
     */
    public static function registerCategory($category, $studio_uuid)
    {
        try {
            $studio = Studio::where('uuid', $studio_uuid)->first();

            $response = $studio->categories()->create([
                'id' => md5(uniqid(rand() . "", true)),
                'category' => $category['category']
            ]);

            if ($response) {
                return $response;
            }

            return false;
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public static function registerSubCategory($fildset)
    {
        try {
            $sub_category = new SubCategory;
            $sub_category->create([
                'id' => md5(uniqid(rand() . "", true)),
                'sub_category' => $fildset['sub_category'],
                'category_id' => $fildset['category_id']
            ]);
            return $sub_category;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
