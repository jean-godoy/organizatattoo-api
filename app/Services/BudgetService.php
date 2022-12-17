<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\Studio;

class BudgetService
{
    public static function registerBudget($fieldset, $user_id, $url_image = null)
    {
        try {

            $budget = Budget::create([
                'id' => md5(uniqid(rand() . "", true)),
                'user_id' => $user_id,
                'name' => $fieldset['name'],
                'studio_id' => $fieldset['studio_id'],
                'costumer_id' => $fieldset['costumer_id'],
                'costumer_name' => $fieldset['costumer_name'],
                'professional_id' => $fieldset['professional_id'],
                'professional_name' => $fieldset['professional_name'],
                'type_service' => $fieldset['type_service'],
                'style_service' => $fieldset['style_service'],
                'body_region' => $fieldset['body_region'],
                'sessions' => $fieldset['sessions'],
                'width' => $fieldset['width'],
                'heigth' => $fieldset['heigth'],
                'price' => $fieldset['price'],
                'validated_at' => $fieldset['validated_at'],
                'note' => $fieldset['note'],
                'url_image' => $url_image
            ]);

            if ($budget) {
                return $budget;
            }
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public static function getBudgetByStudioId($studio_uuid)
    {

        $studio = Budget::where('studio_id', $studio_uuid)->orderBy('created_at', 'DESC')->get() ?? null;
        // dd($studio);
        return $studio->toArray();
        if (!$studio) {
            return false;
        }

        return $studio->budgets->toArray();
    }

    public static function searchBudget($studio_uuid, $costumer_name)
    {
        try {
            $budget = Budget::where('studio_id', $studio_uuid)
                ->where('costumer_name', 'LIKE', "%$costumer_name%")
                ->get()
                ->toArray() ?? null;
            return $budget;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
