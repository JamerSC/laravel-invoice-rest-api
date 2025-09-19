<?php

namespace App\Services;

use GuzzleHttp\Client;
use Infobip\Api\SendSmsApi;
use Infobip\Configuration;
use Infobip\Model\SmsAdvancedTextualRequest;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;

class SmsInfobipService 
{
    protected SendSmsApi $sendSmsApi;
    protected string $sender;

    public function __construct()
    {
        $config = new Configuration();
        $config
            ->setHost(config('services.infobip.base_url'))
            ->setApiKeyPrefix('Authorization', 'App')
            ->setApiKey('Authorization', config('services.infobip.key'));

        // ✅ In v4: pass Guzzle Client + Configuration
        $this->sendSmsApi = new SendSmsApi(new Client(), $config);
        $this->sender = config('services.infobip.sender');
    }

    public function sendSms(string $to, string $message)
    {
        $to = $this->formatLocalNumber($to);

        $destination = new SmsDestination(['to' => $to]);

        $textMessage = new SmsTextualMessage([
            'from'        => $this->sender,
            'text'        => $message,
            'destinations' => [$destination],
        ]);

        $request = new SmsAdvancedTextualRequest([
            'messages' => [$textMessage]
        ]);

        
        try {
            return $this->sendSmsApi->sendSmsMessage($request);
        } catch (\Exception $e) {
            // log raw Infobip error for debugging
            return [
                'error' => $e->getMessage(),
            ];
        }
    }

    public function formatLocalNumber(string $number)
    {
        // If number starts with 0, replace with +63
        if (preg_match('/^0[0-9]{10}$/', $number))
        {
            return '+63' . substr($number, 1);
        }
        return $number; // if valid
    }

}