<?php
namespace BeeDelivery\Benjamin\Services\Validators;

use BeeDelivery\Benjamin\Models\Configs\Config;

abstract class BaseValidator
{
    protected $config;

    private $errors = [];

    abstract public function validate();

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    protected function addAllErrors($errors = [])
    {
        $this->errors = array_merge($this->errors, $errors);
    }

    protected function addError($message)
    {
        $this->errors[] = $message;
    }
}
