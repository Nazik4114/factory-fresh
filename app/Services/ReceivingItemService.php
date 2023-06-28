<?php

namespace Modules\Receiving\Services;

use Modules\Receiving\Models\ReceivingItem;

class ReceivingItemService
{


    public function createNewItem($widht, $height, $price)
    {
        $item = new ReceivingItem(
            [
                'width' => $widht,
                'height' => $height,
                'price' => $price,
            ]
        );
        $item->area = round($widht * $height, 2);
        $item->sum = round($item->area * $price);
        return $item;
    }


    public function updateItem($model, $widht, $height, $price){


        $model->area = round($widht * $height, 2);
        $model->sum = round($model->area * $price);
        $model->save();
        return $model;
    }



}
