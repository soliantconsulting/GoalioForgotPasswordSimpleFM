<?php

namespace Soliant\GoalioForgotPasswordSimpleFM\Entity;

use Soliant\SimpleFM\ZF2\Entity\AbstractEntity;
#use ZfcUser\Entity\UserInterface;
#use Zend\Stdlib\ArraySerializableInterface;
use GoalioForgotPassword\Entity\Password as GoalioForgotPasswordEntity;

class Password extends AbstractEntity
{
    public function __construct($data = null)
    {
        if ($data) {
            $this->exchangeArray($data);
        }
    }

    protected $user_id;
    protected $requestKey;
    protected $requestTime;

    public function getArrayCopy()
    {
        return array(
            'user_id' => $this->getUser_id(),
            'requestKey' => $this->getRequstKey(),
            'requestTime' => $this->getRequestTime(),
        );
    }

    public function exchangeArray(array $data)
    {
        foreach ($data as $field => $value) {
            $field = strtolower($field);
            switch ($field) {
                case 'user_id':
                    $this->setUser_id($value);
                    break;

                case 'requestkey':
                    $this->setRequestKey($value);
                    break;

                case 'requesttime':
                    $this->setRequestTime($value);
                    break;
            }
        }

        return $this;
    }

    public function getFieldMapWriteable()
    {
        return [
            'user_id' => 'user_id',
            'requestKey' => 'request_key',
            'requestTime' => 'request_time',
       ];
    }

    public function getFieldMapReadOnly()
    {
        return [
        ];
    }

    public function getDefaultWriteLayoutName()
    {
        throw new \Exception('This function belongs on the mapper and should not be called '
        . 'ever');
    }

    public function getDefaultControllerRouteSegment()
    {
        throw new \Exception('This function belongs on the mapper and should not be called '
        . 'ever');
    }

    public function getUser_id()
    {
        return $this->user_id;
    }

    public function setUser_id($id)
    {
        $this->user_id = $id;

        return $this;
    }

    public function getRequestKey()
    {
        return $this->requestKey;
    }

    public function setRequestKey($value)
    {
        $this->requestKey = $value;

        return $this;
    }

    public function getRequestTime()
    {
        return $this->requestTime;
    }

    public function setRequestTime($value)
    {
        $this->requestTime = $value;

        return $this;
    }
}
