<?php

namespace Modules\Receiving\Services;

use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Modules\DeliveryPointHours\Models\DeliveryPointHour;
use Modules\Receiving\Models\Receiving;
use Modules\Settings\Facades\Settings;

class ReceivingPrintService
{

    public function __construct()
    {

    }


    public function printOrder($params){
        $printerData = $params->all()['printer'];
        $connector = new NetworkPrintConnector('192.168.1.87', 9100);
        $profile = CapabilityProfile::load('default');
        $printer = new Printer($connector, $profile);
        $printer->initialize();
        $printValue = view('admin.printer.receiving',compact('printerData'))->render();
//        $printer->selectCharacterTable(73);
        $text = iconv('UTF-8', 'utf-8//TRANSLIT', $printValue);
//        $text = iconv('UTF-8', 'windows-1251//TRANSLIT//IGNORE', $printValue);
//        $printer -> text(iconv("UTF-8","GBK//IGNORE", "$artikli[0]") . "\n");
        $printer->textRaw($text);
     /*   $printer->text('                    FACTORY');
        $printer->text("\n");
        $printer->text('                      FRESH');
        $printer->text("\n");
        $printer->text('______________________________________________');
        $printer->text("\n");
        $printer->text("Квитанція  | " . $printerData['id']);
        $printer->text("\n");
        $printer->text("Номер тел. | " . $printerData['phone']);
        $printer->text("\n");
        $printer->text('______________________________________________');
        $printer->text("\n");
        foreach ($printerData['items'] as $item) {
            $printer->text("Килим" . $item['width'] . "x" . $item['height'] .  "| " . $item['area'] . "м²");
            $printer->text("\n");
        }
        $printer->text('______________________________________________');
        $printer->text("\n");
        $printer->text("Розмір  | " . $printerData['area'] . "м²");
        $printer->text("\n");
        $printer->text('______________________________________________');
        $printer->text("\n");
        $printer->text("Сума  | " . $printerData['total'] . "грн");
        $printer->text("\n");
        $printer->text('______________________________________________');
        $printer->text("\n");
        $printer->text($printerData['id']);*/
        $printer->cut();
        $printer->close();
        return 0;
    }

    public function printStickers(Receiving $receiving){

    }




}
