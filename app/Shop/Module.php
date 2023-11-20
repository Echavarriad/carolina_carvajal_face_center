<?php

namespace App\Shop;

use App\Models\City;
use App\Models\Module as TableModule;
use App\Modules\ShippingRange;
use App\Modules\{Wompi, Paypal};

class Module {


    public static function carriers($address) {
        $carrier = null;
        $address->data_city = City::where('id', $address->city_id)->with('state')->first();
        $dataModule = TableModule::carriers()->first();
        if ($dataModule) {
           $carrier = ShippingRange::calculate($dataModule, $address);
            if ($carrier->success) {
                $carrier->total_format = core()->currency($carrier->total);
            }
        }  

        return $carrier;
    }

    //Obtener información de los medios de pago disponibles
    public static function gateways() {
        $modules = TableModule::gateways()->select(['id', 'code', 'name', 'logo', 'description', 'switch', 'type_payment'])->get();
        
        return $modules;
    }

    //Procesar un medio de pago en específico
    public static function gateway($code, $order) {
        $module = TableModule::where('id', $code)->first();
        if ($module->code == 'ac_paypal') {
            $dataPayment = Paypal::getData($module, $order);           
        }else{
           $dataPayment = Wompi::getData($module, $order);
        }
        return $dataPayment;
    }

    //Confirmar un pago
    public static function confirmPay($code) {
        $module = TableModule::whereCode($code)->first();

        if (!$module) {
            //El modulo no existe;
            return false;
        }
        //Llamar el método que confirma el pago
        $class = $module->getClass();
        $moduleClass = new $class;
        $moduleClass->setModule($module);
        return $moduleClass->confirm();

    }

    public static function processCarriers($dataCarriers) {
        $dataCarriers = $dataCarriers->sortBy('total')->values();
        $shippingFree = $dataCarriers->where('is_free', true)->first();
        if ($shippingFree) {
            $dataCarriers = $dataCarriers->filter(function ($value, $key) {
                if ($value->is_free == true) {
                    return true;
                }
            })->all();
        }
        return $dataCarriers;
    }



    public static function dispatch($event, $data) {

        $dataModule = TableModule::active()->get();

        foreach ($dataModule as $item) {
            $class = $item->getClass();

            $moduleClass = new $class;
            $moduleClass->setModule($item);
            if (isset($moduleClass->listen[$event])) {
                $methodName = $moduleClass->listen[$event];
                $moduleClass->{$methodName}($data);
            }

        }
    }

}