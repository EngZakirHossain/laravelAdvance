<?php

namespace App\Helper;

use App\Helper\Helpers;
use App\Models\Setting;
use Twilio\Rest\Client;
use App\Model\BusinessSetting;
use Nexmo\Laravel\Facade\Nexmo;
use Illuminate\Support\Facades\Config;

class SMS_module
{
    public static function send($receiver, $otp)
    {
        $config = Helpers::get_business_settings('twilio_sms');
        if (isset($config) && $config['status'] == true) {
            $response = self::twilio($receiver, $otp);
            return $response;
        }

        // $config = Helpers::get_business_settings('nexmo_sms');
        // if (isset($config) && $config['status'] == 1) {
        //     $response = self::nexmo($receiver, $otp);
        //     return $response;
        // }

        // $config = Helpers::get_business_settings('2factor_sms');
        // if (isset($config) && $config['status'] == 1) {
        //     $response = self::two_factor($receiver, $otp);
        //     return $response;
        // }

        // $config = Helpers::get_business_settings('msg91_sms');
        // if (isset($config) && $config['status'] == 1) {
        //     $response = self::msg_91($receiver, $otp);
        //     return $response;
        // }

        // $config = Helpers::get_business_settings('signalwire_sms');
        // if (isset($config) && $config['status'] == 1) {
        //     $response = self::signalwire($receiver, $otp);
        //     return $response;
        // }

        return 'not_found';
    }

    public static function twilio($receiver, $otp)
    {
        $config = Helpers::get_business_settings('twilio_sms');
        $response = 'error';
        if (isset($config) && $config['status'] == true) {
            $message = str_replace("#OTP#", $otp, $config['otp_template']);
            $sid = $config['sid'];
            $token = $config['token'];
            try {
                $twilio = new Client($sid, $token);
                $twilio->messages
                    ->create($receiver, // to
                        array(
                            "messagingServiceSid" => $config['messaging_service_sid'],
                            "body" => $message
                        )
                    );
                $response = 'success';
            } catch (\Exception $exception) {
                $response = 'error';
            }
        }
        return $response;
    }

    // public static function nexmo($receiver, $otp)
    // {
    //     $sms_nexmo = Helpers::get_business_settings('nexmo_sms');
    //     $response = 'error';
    //     if (isset($sms_nexmo) && $sms_nexmo['status'] == 1) {
    //         $message = str_replace("#OTP#", $otp, $sms_nexmo['otp_template']);
    //         try {
    //             $config = [
    //                 'api_key' => $sms_nexmo['api_key'],
    //                 'api_secret' => $sms_nexmo['api_secret'],
    //                 'signature_secret' => '',
    //                 'private_key' => '',
    //                 'application_id' => '',
    //                 'app' => ['name' => '', 'version' => ''],
    //                 'http_client' => ''
    //             ];
    //             Config::set('nexmo', $config);
    //             Nexmo::message()->send([
    //                 'to' => $receiver,
    //                 'from' => $sms_nexmo['from'],
    //                 'text' => $message
    //             ]);
    //             $response = 'success';
    //         } catch (\Exception $exception) {
    //             $response = 'error';
    //         }
    //     }
    //     return $response;
    // }

    // public static function two_factor($receiver, $otp)
    // {
    //     $config = Helpers::get_business_settings('2factor_sms');
    //     $response = 'error';
    //     if (isset($config) && $config['status'] == 1) {
    //         $api_key = $config['api_key'];
    //         $curl = curl_init();
    //         curl_setopt_array($curl, array(
    //             CURLOPT_URL => "https://2factor.in/API/V1/" . $api_key . "/SMS/" . $receiver . "/" . $otp . "",
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_ENCODING => "",
    //             CURLOPT_MAXREDIRS => 10,
    //             CURLOPT_TIMEOUT => 30,
    //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //             CURLOPT_CUSTOMREQUEST => "GET",
    //         ));
    //         $response = curl_exec($curl);
    //         $err = curl_error($curl);
    //         curl_close($curl);

    //         if (!$err) {
    //             $response = 'success';
    //         } else {
    //             $response = 'error';
    //         }
    //     }
    //     return $response;
    // }

    // public static function msg_91($receiver, $otp)
    // {
    //     $config = Helpers::get_business_settings('msg91_sms');
    //     $response = 'error';
    //     if (isset($config) && $config['status'] == 1) {
    //         $receiver = str_replace("+", "", $receiver);
    //         $curl = curl_init();
    //         curl_setopt_array($curl, array(
    //             CURLOPT_URL => "https://api.msg91.com/api/v5/otp?template_id=" . $config['template_id'] . "&mobile=" . $receiver . "&authkey=" . $config['authkey'] . "",
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_ENCODING => "",
    //             CURLOPT_MAXREDIRS => 10,
    //             CURLOPT_TIMEOUT => 30,
    //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //             CURLOPT_CUSTOMREQUEST => "GET",
    //             CURLOPT_POSTFIELDS => "{\"OTP\":\"$otp\"}",
    //             CURLOPT_HTTPHEADER => array(
    //                 "content-type: application/json"
    //             ),
    //         ));
    //         $response = curl_exec($curl);
    //         $err = curl_error($curl);
    //         curl_close($curl);
    //         if (!$err) {
    //             $response = 'success';
    //         } else {
    //             $response = 'error';
    //         }
    //     }
    //     return $response;
    // }

    // public static function signalwire($receiver, $otp)
    // {
    //     $config = Helpers::get_business_settings('signalwire_sms');
    //     $response = 'error';
    //     if (isset($config) && $config['status'] == 1) {
    //         $message = str_replace("#OTP#", $otp, $config['otp_template']);
    //         $project_id = $config['project_id'];
    //         $token = $config['token'];
    //         $space_url = $config['space_url'];
    //         $sender = $config['from'];

    //         try {
    //             $client = new \SignalWire\Rest\Client($project_id, $token, array("signalwireSpaceUrl" => $space_url));

    //             $client->messages
    //                 ->create($receiver, // to
    //                     array("from" => $sender, "body" => $message)
    //                 );

    //             $response = 'success';
    //         } catch (\Exception $exception) {
    //             $response = 'error';
    //         }
    //     }
    //     return $response;
    // }

}
