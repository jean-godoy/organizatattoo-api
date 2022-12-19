<?php

namespace App\Services;

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
            $response = $studio->materialProducts->where('product_name', $product)->first()->toArray() ?? null;
            return $response;
        } catch (\Throwable $th) {
            //throw $th;
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
}
