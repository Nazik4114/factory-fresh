<?php


namespace Modules\Deliveries\Services;


use Modules\CityHour\Models\CityHour;
use Modules\Deliveries\Models\Delivery;
use Modules\Deliveries\Services\DeliveryItemService;
use Modules\Settings\Facades\Settings;

class DeliveryService
{

    public function __construct()
    {
        $this->deliveryItemService = new DeliveryItemService();
        $this->rugPrice = Settings::get('order.rug_price');
        $this->overlockPrice = Settings::get('order.overlock_price');
        $this->repairPrice = Settings::get('order.repair_price');
    }

    public function createModelFromRequest($requestData)
    {
        $delivery = new Delivery($requestData);
        $delivery->fill($this->getDeliveryPointHourData($requestData['city_id']));
        $delivery->qty = count($requestData['items']);
        $delivery->repair_price = $requestData['repair'] ? $this->repairPrice : 0;
        $delivery->overlock_price = $this->getOverlockPrice($this->overlockPrice, $delivery->overlock_length);
        $delivery->delivery_price = $requestData['delivery_price'] ? $requestData['delivery_price'] : '';
        $items = $this->makeNewItems($requestData['items']);
        $delivery->total_area = $items->sum('area');
        $delivery->total_items_amount = $items->sum('sum');
        $delivery->total_price = $delivery->total_items_amount + $delivery->overlock_price + $delivery->repair_price;
        $delivery->save();
        return $delivery;
    }
    public function updateModelFromRequest(Delivery $model, $requestData)
    {
        $deliveries = $model->fill($requestData);
        $deliveries->fill($this->getDeliveryPointHourData($requestData['city_id']));
        if($requestData['issuance_point_hour_id'] ?? null){
            $deliveries->fill($this->getIssuancePointHourData($requestData['issuance_point_hour_id']));
        }

        $deliveries->qty = count($requestData['items']);
        $deliveries->delivery_price = $requestData['delivery_price'];
        $deliveries->delivery_point_id = $requestData['delivery_point_id'];
        $deliveries->repair_price = $requestData['repair'] ? $this->repairPrice : 0;
        $deliveries->overlock_price = $this->getOverlockPrice($this->overlockPrice, $deliveries->overlock_length);

        $model->items()->delete();
        $items = $this->makeNewItems($requestData['items']);
        $deliveries->total_area = $items->sum('area');
        $deliveries->total_items_amount = $items->sum('sum');
        $deliveries->total_price = $deliveries->total_items_amount + $deliveries->overlock_price + $deliveries->repair_price;
        $deliveries->save();
        $deliveries->items()->saveMany($items);
        return $deliveries;
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

    protected function getOverlockPrice($overlockPrice, $overlockLength)
    {
        if ($overlockPrice) {
            return $this->overlockPrice * $overlockLength;
        }
        return 0;
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

    protected function makeNewItems($itemsData)
    {
        $items = collect();
        foreach ($itemsData as $item) {
            $items[] = $this->deliveryItemService->createNewItem(
                $item['width'],
                $item['height'],
                $this->rugPrice
            );
        }
        return $items;
    }
}
