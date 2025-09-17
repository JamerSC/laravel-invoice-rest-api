<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\PayfusionInvoiceRequest;
use App\Http\Requests\v1\PayfusionPaymentRequest;
use App\Http\Requests\v1\PayfusionTokenRequest;
use App\Services\PayfusionService;
use Illuminate\Http\Request;

class PayfusionController extends Controller
{
    protected PayfusionService $payfusionService;

    public function __construct(PayfusionService $payfusionService)
    {
        $this->payfusionService = $payfusionService;
    }

    public function createPaymentToken(PayfusionTokenRequest $request)
    {
        $data = $request->validated();

        $createdToken = $this->payfusionService->createPaymentToken($data);

        return response()->json([
            "Token" => $createdToken,
        ],200);
    }

    public function createPaymentRequest(PayfusionPaymentRequest $request)
    {
        $data = $request->validated();

        $paymentRequest = $this->payfusionService->createPaymentRequest($data);

        return response()->json([
            "paid" => $paymentRequest,
        ],200);
    }
    
    public function listOfInvoices()
    {
        $invoices = $this->payfusionService->listOfInvoices();

        return response()->json([
            'invoices' => $invoices,
        ],200);
    }

    public function createInvoice(PayfusionInvoiceRequest $request)
    {
        $data = $request->validated();

        $createdInvoice = $this->payfusionService->createInvoice($data);

        return response()->json([
            "invoiced" => $createdInvoice,
        ],200);
    }
}
