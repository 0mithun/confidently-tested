<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;

class PaymentGateChargeException extends \Exception{

    private $data;

    public function __construct(string $message, array $data) {
        $this->data = $data;
        parent::__construct($message);

    }

    public function getData()
    {
        return $this->data;
    }

    public function render($req)
    {
        $data = $this->getData();
        Log::error('Card failed: ', $data);
        $template = 'partials.errors.charge_failed';
        $data = $data['error'];

        return view('errors.generic', compact('template', 'data'));
    }
}
