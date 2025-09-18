<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PayfusionService 
{
    protected $baseUrl;
    protected $mode;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.payfusion.base_url');
        $this->mode = config('services.payfusion.mode');
        $this->apiKey = config('services.payfusion.key');
    }

    private function headers($idempotencyKey = null)
    {
        return [
            'Authorization'   => 'Bearer ' . $this->apiKey,
            'Accept'          => 'application/json',
            'Content-Type'    => 'application/json',
            'Idempotency-Key' => $idempotencyKey ?? (string) Str::uuid(),
        ];
    }
    
    /** TOKEN */

    // Create Payment Token
    public function createPaymentToken(array $data)
    {
        $url = "{$this->baseUrl}/{$this->mode}/payment-tokens";
        return Http::withHeaders($this->headers())->post($url, $data)->json();
    }

    // Get Payment Token
    public function getPaymentToken($tokenId)
    {
        $url = "{$this->baseUrl}/{$this->mode}/payment-tokens/{$tokenId}";
        return Http::withHeaders($this->headers())->get($url)->json();
    }

    /** PAYMENT */

    // Create Payment
    public function createPaymentRequest(array $data)
    {
        $url = "{$this->baseUrl}/{$this->mode}/payment-requests";
        
        // generate UUID v4 for idempotency key
        $idempotencyKey = (string) Str::uuid();

        return Http::withHeaders($this->headers($idempotencyKey)
        )->post($url, $data)->json();
    }

    // Get Payment
    public function getPaymentRequest($requestId)
    {
        $url = "{$this->baseUrl}/{$this->mode}/payment-requests/{$requestId}";
        return Http::withHeaders($this->headers())->get($url)->json();
    } 

    // Capture Payment
    public function capturePaymentRequest($requestId)
    {
        $url = "{$this->baseUrl}/{$this->mode}/payment-requests/{$requestId}/capture";
        return Http::withHeaders($this->headers())->post($url)->json();
    }

    /** INVOICE */

    // Get List of Invoice
    public function listOfInvoices()
    {
        $url = "{$this->baseUrl}/{$this->mode}/invoices";
        return Http::withHeaders($this->headers())->get($url)->json();
    }

    // Get Invoice by ID
    public function showInvoice($invoiceId)
    {
        $url = "{$this->baseUrl}/{$this->mode}/invoices/{$invoiceId}";
        return Http::withHeaders($this->headers())->get($url)->json();
    }

    // Create Invoice
    public function createInvoice(array $data)
    {
        $url = "{$this->baseUrl}/{$this->mode}/invoices/create";
        return Http::withHeaders($this->headers())->post($url, $data)->json();
    }
}