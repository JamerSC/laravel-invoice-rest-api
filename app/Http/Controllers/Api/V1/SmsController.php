<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\SmsInfobipService;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    protected SmsInfobipService $smsInfobipService;

    public function __construct(SmsInfobipService $smsInfobipService)
    {
        $this->smsInfobipService = $smsInfobipService;
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'to'      => 'required|string',
            'message' => 'required|string',
        ]);


        $response = $this->smsInfobipService->sendSms(
            $validated['to'],
            $validated['message'],
        );

        return response()->json([
            'status'   => 'success',
            'response' => $response,
        ]);
    }
}
