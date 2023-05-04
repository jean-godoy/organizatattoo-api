<?php

namespace App\Services;

use App\Models\MaterialBrand;
use App\Models\MaterialCategory;
use App\Models\MaterialProduct;
use App\Models\Studio;

class MaterialService
{
    /**
     * Checked in DB if exists the product name.
     * @return null or prodcut name
     */
    public static function checkMaterialProduct($studio_uuid, $product)
    {
        try {
            $studio = Studio::where('uuid', $studio_uuid)->first();
            $response = $studio->materialProducts->where('product_name', $product)->first() ?? null;

            if ($response) {
                return $response->product_name;
            }

            return null;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function productStore($studio_uuid, $product)
    {
        try {
            $studio = Studio::where('uuid', $studio_uuid)->first();
            $response = $studio->materialProducts()->create([
                'id' => md5(uniqid(rand() . "", true)),
                'product_name' => $product
            ]);
            return $response;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function getAllProducts($studio_uuid)
    {
        try {
            $studio = Studio::where('uuid', $studio_uuid)->first();
            $products = $studio->materialProducts()->get(['id', 'product_name'])->toArray() ?? null;
            return $products;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Retorna os produtos relacionados pela categoria.
     */
    public static function getBrandBycategoryId($studio_uuid, $category_id)
    {
        try {
            $category = MaterialCategory::where('id', $category_id)->first() ?? null;
            $brands = $category->materialBrands()->get(['id', 'product_brand'])->toArray() ?? null;

            return $brands;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Método que retorna dotod os detalhes do produto,
     * relácionado a categoria e marca
     */
    public static function getBrandByCategoryIdShow($studio_uuid, $category_id, $brand_id)
    {
        try {
            $category = MaterialCategory::where('id', $category_id)->first() ?? null;
            $brands = $category->materialBrands()->where('id', $brand_id)->first()->toArray() ?? null;

            return $brands;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function checkBrand($product_id, $category_id, $brand)
    {
        try {
            // $product = MaterialProduct::where('id', $product_id)->first();

            // $category = $product->materialCategories()->where('id', $category_id)->first() ?? null;
            $category = MaterialCategory::where('id', $category_id)->first() ?? null;
            $response = $category->materialBrands()->where('product_brand', $brand)->first() ?? null;

            if ($response) {
                return $response->product_brand;
            }

            return null;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function brandStore($studio_uuid, $fieldset)
    {
        try {
            $category = MaterialCategory::where('id', $fieldset['category_id'])->first() ?? null;

            $category->materialBrands()->create([
                'id' => md5(uniqid(rand() . "", true)),
                'product_id' => $fieldset['product_id'],
                'product_name' => $fieldset['product_name'],
                'category_name' => $fieldset['category_name'],
                'product_brand' => $fieldset['product_brand'],
                'product_measure' => $fieldset['product_measure'],
                'minimum_amount' => $fieldset['minimum_amount'],
                'descartable' => $fieldset['descartable'],
                'sterilizable' => $fieldset['sterilizable'],
            ]);

            return $category;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function checkCategory($studio_uuid, $fieldset)
    {
        try {
            $product = MaterialProduct::where('studio_id', $studio_uuid)
                ->where('id', $fieldset['material_product_id'])->first() ?? null;

            $category = $product->materialCategories()->where('material_category', $fieldset['material_category'])->first() ?? null;

            return $category;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function categoryStore($studio_uuid, $fieldset)
    {
        try {
            $product = MaterialProduct::where('studio_id', $studio_uuid)
                ->where('id', $fieldset['material_product_id'])->first() ?? null;

            $product->materialCategories()->create([
                'id' => md5(uniqid(rand() . "", true)),
                'material_category' => $fieldset['material_category']
            ]);
            return $product;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function getCategoryByProductId($product_id)
    {
        try {
            $product = MaterialProduct::where('id', $product_id)->first() ?? null;
            $category = $product->materialCategories()->get(['id', 'material_category'])->toArray() ?? null;

            if ($category) {
                return $category;
            }

            return null;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
