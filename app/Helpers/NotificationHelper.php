<?php 
namespace App\Helpers;

use App\Helpers\Curl;
use App\Models\Notification;
class NotificationHelper {

    public static function sendShipperSignNotification($bill) {
        $carrier_address = $bill->carrier_address;
        $notification = Notification::where('address', $carrier_address)->first();
        if (!$notification || !$notification->tokens) {
            return;
        }

        $payload['notification'] = array(
            "title" => "Bill signed",
            "body"  => "Shipper signed bill " . $bill->quote_no . ". Tap here to proceed.",
            "click_action" => "FCM_PLUGIN_ACTIVITY",
            "sound" => "default"
        );
        $payload['data'] = array(
            "company_id" => $bill->company_id,
            "bill_id" => $bill->_id
        );

        $headers = ['Authorization: key=' . env('FCM_KEY'), 'Content-Type: application/json'];
        $url = env('FCM_URL');

        foreach ($notification->tokens as $key => $token) {
            $payload['to'] = $token;
            $resp = Curl::post($url , $payload , $headers, [], true);
        }
    }
}