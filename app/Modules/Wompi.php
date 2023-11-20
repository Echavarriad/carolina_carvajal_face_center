<?php 

namespace App\Modules;

use App\Models\Payment;

class Wompi {
	

    public static function getData($module, $order) {
        $urlWompi= "https://checkout.wompi.co/p/";
        $params = (object) $module->settings; 
        $confirmationUrl = route('shop.return');
        $test = $params->test;
        $total = round($order->grand_total) . '00';
        $amount = intval(str_replace(".", "", $total));
        $public_key = $test ? $params->key_test : $params->key_prod;
        $dataWompi = array(
            'public-key' => $public_key,
            'reference' => $order->reference,
            'amount-in-cents' => $amount,
            'currency' => 'COP',
            'redirect-url' => $confirmationUrl,
        );
        $urlPay = $urlWompi;
        $data['type'] = 'checkout';
        $data['url_pay'] = $urlPay;
        $data['fields'] = $dataWompi;

        return $data;
    }

    //Cuando el cliente regresa al comercio, NO esta programada
    public static function returnPay($module){
        $data = request()->all();
        $id_transaction = $data['id'];
        $params =  (object) $module->settings; 
        $test = $params->test;
        $url = $test ? $params->url_test : $params->url_prod;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '/transactions/' .$id_transaction);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
        //     'Authorization: Bearer ' . $public_key));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        $result = curl_exec($ch);
        $data = json_decode($result); 
        return self::getDataTransaction($data->data);
         
    }

    //Evento ejecutado en Wompi
    public static function confirmPay() {
        // return self::getDataTransactionTest();
        $response = json_decode(file_get_contents('php://input'));
        $data['order_id'] = 1;
        $data['params'] = $response;
        Payment::create($data);
        if ($response->event != "transaction.updated") {
            \Log::info('TRANSACTION Event Not Found: Wompi');
            return false;
        }
        $data = $response->data->transaction;

        return self::getDataTransaction($data);
    }

    private static function getDataTransaction($data){
        $referenceCode = $data->reference;
        $dataTx['order_reference'] = $referenceCode;
        $dataTx['transaction_id'] = $data->id;
        $datePayment = $data->created_at;
        $datePayment = date('Y-m-d H:i:s', strtotime($datePayment));
        $dataTx['payment_date'] = $datePayment;
        $dataTx['amount'] = $data->amount_in_cents;
        $dataTx['currency'] = $data->currency;
        $dataTx['payment_method'] = $data->payment_method_type;

        $stateTx = $data->status;
        //Retorna valores con la infromación de la transacción
        if ($stateTx == 'APPROVED') {
            $status = 2;
        }elseif($stateTx == 'DECLINED'){
            $status = 5;
        }elseif($stateTx == 'ERROR'){
            $status = 5;
        }else{
            $status = 1;
        }
        //Devolver la referencia del pedido, el estado y los datos de la transaccion
        return [
            'order' => $referenceCode,
            'status' => $status,
            'params' => $dataTx
        ];
    }
    
    private static  function getDataTransactionTest(){        
        $referenceCode = 'PVE400000016';
        $dataTx['order_reference'] = $referenceCode;
        $dataTx['transaction_id'] = '1104423-1650890468-31843';
        $datePayment = '2022-04-25 07:41:08';
        $datePayment = date('Y-m-d H:i:s', strtotime($datePayment));
        $dataTx['payment_date'] = $datePayment;
        $dataTx['amount'] = 15000000;
        $dataTx['currency'] = 'COP';
        $dataTx['payment_method'] = 'PSE';

        $stateTx = 'APPROVED';
        //Retorna valores con la infromación de la transacción
        if ($stateTx == 'APPROVED') {
            $status = 2;
        }elseif($stateTx == 'DECLINED'){
            $status = 5;
        }elseif($stateTx == 'ERROR'){
            $status = 5;
        }else{
            $status = 1;
        }
        //Devolver la referencia del pedido, el estado y los datos de la transaccion
        return [
            'order' => $referenceCode,
            'status' => $status,
            'params' => $dataTx
        ];
    }
	
}