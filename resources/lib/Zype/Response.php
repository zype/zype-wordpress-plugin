<?php

class Response
{
    public $data;

    public function __construct($response)
    {
        $this->data = json_decode($response);
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
}
