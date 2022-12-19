<?php

namespace App\Services;

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

    public static function getBrandByProductId($studio_uuid, $product_id)
    {
        try {
            $product = MaterialProduct::where('studio_id', $studio_uuid)
                ->where('id', $product_id)->first() ?? null;
            $brands = $product->materialBrands()->get()->toArray() ?? null;

            return $brands;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function checkBrand($studio_uuid, $brand)
    {
        try {
            $product = MaterialProduct::where('studio_id', $studio_uuid)->first();
            $response = $product->materialBrands->where('product_brand', $brand)->first() ?? null;

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
            $product = MaterialProduct::where('studio_id', $studio_uuid)
                ->where('id', $fieldset['product_id'])->first() ?? null;

            $product->materialBrands()->create([
                'id' => md5(uniqid(rand() . "", true)),
                'product_brand' => $fieldset['product_brand']
            ]);

            return $product;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
