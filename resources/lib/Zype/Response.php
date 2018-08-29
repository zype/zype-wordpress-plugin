<?php

class Response
{
    public $data;
    public $code;

    public function __construct($response, $code)
    {
        $this->data = json_decode($response);
        $this->code = $code;
        return $this->data;
    }

    public function __get($key = false)
    {
        if (!$key) {
            return $this->data;
        }

        if (property_exists($this->data, $key)) {
            return $this->data->{$key};
        }

        throw new \Exception("Property not exists");
    }

    public function __isset($key = false)
    {
        return $key && property_exists($this->data, $key);
    }

    public function getMessage()
    {
        if (!empty($this->data->error)) {
            return [
                'type' => $this->data->error,
                'description' => $this->data->error_description
            ];
        } else if (!empty($this->data->message)) {
            return [
                'type' => 'Request error',
                'description' => $this->data->message
            ];
        }

        return false;
    }

    public function getBody()
    {
        return $this->data;
    }

    public function success()
    {
        return $this->code >= 200 && $this->code < 300;
    }
}
