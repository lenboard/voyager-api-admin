<?php

namespace App;

class ApiPaymentForm
{
    protected $paymentHandler;
    protected $paymentType;
    protected $paymentId;

    public function __construct($paymentHandler, $paymentType, $paymentId)
    {
        $this->paymentHandler = $paymentHandler;
        $this->paymentType    = $paymentType;
        $this->paymentId      = $paymentId;
    }

    public function getPaymentHandler()
    {
        return $this->transformPaymentHandler();
    }

    public function getPaymentType()
    {
        return $this->paymentType;
    }

    public function getPaymentId()
    {
        return $this->paymentId;
    }

    private function transformPaymentHandler()
    {
        return $this->paymentHandler;
    }
}