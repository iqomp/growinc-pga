<?php

/**
 * PGA API Caller
 * @package iqomp/growinc-pga
 * @version 1.0.0
 */

namespace Iqomp\GrowIncPGA;

class PGA
{
    protected string $app_id;
    protected string $app_secret;

    public function __construct(string $id, string $secret)
    {
        $this->app_id = $id;
        $this->app_secret = $secret;
    }

    /**
     * Call the API with some options
     * @param array $options The call options
     *  @param string $method Request method, ex GET, POST. Required
     *  @param string $path Target path exclude prefix `/api`. Required
     *  @param array $body Request body for method POST and PUT
     *  @param array $query Request query string
     *  @param string $signature The request signature
     * @return mixed
     */
    public function call(array $options)
    {
        if (!isset($options['method'])) {
            $options['method'] = 'POST';
        }

        // required options
        $req_options = ['path', 'signature'];
        foreach ($req_options as $name) {
            if (!isset($options[$name])) {
                throw new Exception\MissingRequiredOptionException($name);
            }
        }

        $opts = [
            'json' => []
        ];
        $sign = hash_hmac('sha256', $options['signature'], $this->app_secret);

        if (isset($options['query'])) {
            $opts['query'] = $options['query'];
        }

        if (isset($options['body'])) {
            $opts['json'] = (array)$options['body'];
        }

        $opts['json']['merchant_code'] = $this->app_id;
        $opts['json']['signature'] = $sign;

        $last_exception = null;

        $guzzle = Guzzle::getClient();
        try {
            $res = $guzzle->request($options['method'], $options['path'], $opts);
        } catch (\Exception $e) {
            $res = $e->getResponse();
            $last_exception = $e;
        }

        $body = (string) $res->getBody();

        $headers = $res->getHeader('Content-Type')[0];
        if (false !== strstr($headers, 'application/json')) {
            $body = json_decode($body);

            if ($body->status !== '000') {
                $info = $body->error_message . ' ( ' . $body->status . ' )';
                throw new Exception\ErrorResponseException($info, $body->status, $last_exception);
            }
        }

        return $body;
    }

    public function checkBill(array $body): ?object
    {
        $invoice = $body['reference_no'] ?? '';

        $opts = [
            'path' => 'pay/check_b',
            'body' => $body,
            'signature' => $this->app_id . ':' . $invoice
        ];

        return $this->call($opts);
    }

    public function createBill(array $body): ?object
    {
        $invoice = $body['invoice_no'] ?? '';

        $opts = [
            'path' => 'pay/create',
            'body' => $body,
            'signature' => $this->app_id . ':' . $invoice
        ];

        return $this->call($opts);
    }

    public function getPaymentMethods(): ?object
    {
        $opts = [
            'path' => 'payment_methods',
            'signature' => $this->app_id
        ];

        return $this->call($opts);
    }

    public function getTransactions(array $body = []): ?object
    {
        $opts = [
            'path' => 'get_transaction_list',
            'signature' => $this->app_id
        ];

        if ($body) {
            $opts['body'] = $body;
        }

        return $this->call($opts);
    }
}
