<?php
namespace BeeDelivery\Benjamin\Models\Responses;

use BeeDelivery\Benjamin\Models\BaseModel;

class Response extends BaseModel
{
    /**
     * @var ErrorResponse[]
     */
    protected $errors = null;

    /**
     * @var mixed
     */
    protected $body;

    /**
     * @return ErrorResponse[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }
}
