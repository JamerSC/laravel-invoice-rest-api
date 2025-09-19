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


        try 
        {
            $response = Http::withHeaders($this->headers($idempotencyKey))
                ->post($url, [
                    'reference_id'       => $data['reference_id'],
                    'channel_code'       => $data['channel_code'],
                    'first_name'         => $data['first_name'],
                    'last_name'          => $data['last_name'],
                    'email'              => $data['email'],
                    'payment_token_id'   => $data['payment_token_id'] ?? null,
                    'amount'             => $data['amount'],
                    'success_return_url' => $data['success_return_url'],
                    'failure_return_url' => $data['failure_return_url'],
                    'cancel_return_url'  => $data['cancel_return_url'],
                    'description'        => $data['description'] ?? null,
                ]);

            if ($response->failed()) {
                // You can also log the error here for debugging
                throw new \Exception("Payfusion API error: " . $response->body());
            }

            return $response->json();
        } 
        catch (\Exception $e) 
        {
            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
        // return Http::withHeaders($this->headers($idempotencyKey)
        // )->post($url, $data)->json();
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