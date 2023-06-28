<?php

namespace Modules\Deliveries\Services;

use Modules\Deliveries\Models\DeliveryItem;

class DeliveryItemService
{


    public function createNewItem($widht, $height, $price)
    {
        $item = new DeliveryItem(
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
