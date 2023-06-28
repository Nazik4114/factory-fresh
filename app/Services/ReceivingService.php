<?php

namespace Modules\Receiving\Services;

use Modules\CityHour\Models\CityHour;
use Modules\DeliveryPointHours\Models\DeliveryPointHour;
use Modules\Receiving\Models\Receiving;
use Modules\Settings\Facades\Settings;

class ReceivingService
{

    public function __construct()
    {
        $this->receivingItemService = new ReceivingItemService();
        $this->rugPrice = Settings::get('order.rug_price');
        $this->overlockPrice = Settings::get('order.overlock_price');
        $this->repairPrice = Settings::get('order.repair_price');
    }


    public function createModelFromRequest($requestData)
    {
        $receiving = new Receiving($requestData);
        $receiving->fill($this->getDeliveryPointHourData($requestData['city_id']));
        $receiving->qty = count($requestData['items']);
        $receiving->repair_price = $requestData['repair'] ? $this->repairPrice : 0;
        $receiving->overlock_price = $this->getOverlockPrice($this->overlockPrice, $receiving->overlock_length);
        $receiving->delivery_price = $requestData['delivery_price'];
        $items = $this->makeNewItems($requestData['items']);
        $receiving->total_area = $items->sum('area');
        $receiving->total_items_amount = $items->sum('sum');
        $receiving->total_price = $receiving->total_items_amount + $receiving->overlock_price + $receiving->repair_price;
        $receiving->save();
        $receiving->items()->saveMany($items);
        return $receiving;
    }


    public function updateModelFromRequest(Receiving $model, $requestData)
    {
        $receiving = $model->fill($requestData);
        $receiving->fill($this->getDeliveryPointHourData($requestData['city_id']));
        if($requestData['issuance_point_hour_id'] ?? null){
            $receiving->fill($this->getIssuancePointHourData($requestData['issuance_point_hour_id']));
        }

        $receiving->qty = count($requestData['items']);
        $receiving->delivery_price = $requestData['delivery_price'];
        $receiving->delivery_point_id = $requestData['delivery_point_id'];
        $receiving->repair_price = $requestData['repair'] ? $this->repairPrice : 0;
        $receiving->overlock_price = $this->getOverlockPrice($this->overlockPrice, $receiving->overlock_length);

        $model->items()->delete();
        $items = $this->makeNewItems($requestData['items']);
        $receiving->total_area = $items->sum('area');
        $receiving->total_items_amount = $items->sum('sum');
        $receiving->total_price = $receiving->total_items_amount + $receiving->overlock_price + $receiving->repair_price;
        $receiving->save();
        $receiving->items()->saveMany($items);
        return $receiving;
    }


    protected function getDeliveryPointHourData($id)
    {
        $cityHour = CityHour::where('city_id', $id)->first();
        return [
            'start' => $cityHour->start,
            'finish' => $cityHour->finish,
            'day' => $cityHour->day,
        ];
    }


    protected function getIssuancePointHourData($id)
    {
        $cityHour = CityHour::where('city_id', $id)->first();
        return [
            'issuance_start' => $cityHour->start,
            'issuance_finish' => $cityHour->finish,
            'issuance_day' => $cityHour->day,
        ];
    }


    protected function getOverlockPrice($overlockPrice, $overlockLength)
    {
        if ($overlockPrice) {
            return $this->overlockPrice * $overlockLength;
        }
        return 0;
    }


    protected function makeNewItems($itemsData)
    {
        $items = collect();
        foreach ($itemsData as $item) {
            $items[] = $this->receivingItemService->createNewItem(
                $item['width'],
                $item['height'],
                $this->rugPrice
            );
        }
        return $items;
    }

    protected function getItemsIds($items)
    {
        return collect($items)->filter(function ($item) {
            return isset($items['id']);
        })->pluck('id')->toArray();
    }


    protected function deleteMissedItems(Receiving $model, $items)
    {
        $ids = $this->getItemsIds($items);
        if (empty($ids)) {
            return;
        }
        $model->items()->whereNotIn('id', $ids)->delete();
    }


}
