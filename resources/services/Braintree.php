<?php

namespace ZypeMedia\Services;

class Braintree extends Component {

    public function __construct()
    {
        parent::__construct();

        \Braintree_Configuration::environment($this->options['braintree_environment']);
        \Braintree_Configuration::merchantId($this->options['braintree_merchant_id']);
        \Braintree_Configuration::publicKey($this->options['braintree_public_key']);
        \Braintree_Configuration::privateKey($this->options['braintree_private_key']);
    }

    public function generateBraintreeToken($customerId = null)
    {
        $braintree_token = '';

        $params = [];

        try {
            if ($customerId) {
                \Braintree_Customer::find($customerId);
                $params['customerId'] = $customerId;
            }

            $braintree_token = \Braintree_ClientToken::generate($params);
        }
        catch (\Braintree_Exception_NotFound $e) {
            return false;
        }

        return $braintree_token;
    }

    public function createPaymentMethod($paymentMethodNonce, $cutomerId)
    {

    }

}