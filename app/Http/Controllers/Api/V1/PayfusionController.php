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

    /** TOKENS */
    
    public function createPaymentToken(PayfusionTokenRequest $request)
    {
        $data = $request->validated();

        $createdToken = $this->payfusionService->createPaymentToken($data);

        return response()->json([
            'Token' => $createdToken,
        ],200);
    }

    public function getPaymentToken($tokenId)
    {
        $listOfTokens = $this->payfusionService->getPaymentToken($tokenId);
        
        return response()->json([
            'tokens' => $listOfTokens,
        ],200);
    }

    /** PAYMENT  */

    public function createPaymentRequest(PayfusionPaymentRequest $request)
    {
        $data = $request->validated();

        $paymentRequest = $this->payfusionService->createPaymentRequest($data);

        return response()->json([
            'payment' => $paymentRequest,
        ],200);
    }

    public function getPaymentRequest($requestId)
    {
        $paymentRequested = $this->payfusionService->getPaymentRequest($requestId);

        return response()->json([
            'payment'=> $paymentRequested
        ],200);
    }

    public function capturePaymentRequest($requestId)
    {
        $capturedPayment = $this->payfusionService->capturePaymentRequest($requestId);

        return response()->json([
            'captured'=> $capturedPayment
        ],200);
    }

    /** INVOICES */
    
    public function listOfInvoices()
    {
        $invoices = $this->payfusionService->listOfInvoices();

        return response()->json([
            'invoices' => $invoices,
        ],200);
    }

    public function showInvoice($invoiceId)
    {
        $invoice = $this->payfusionService->showInvoice($invoiceId);

        return response()->json([
            'invoice' => $invoice,
        ],200);
    }

    public function createInvoice(PayfusionInvoiceRequest $request)
    {
        $data = $request->validated();

        $createdInvoice = $this->payfusionService->createInvoice($data);

        return response()->json([
            'invoiced' => $createdInvoice,
        ],200);
    }
}
