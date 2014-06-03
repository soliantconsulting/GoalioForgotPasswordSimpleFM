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

    protected $userId;
    protected $requestKey;
    protected $requestTime;

    public function getArrayCopy()
    {
        return array(
            'userId' => $this->getUserid(),
            'requestKey' => $this->getRequstKey(),
            'requestTime' => $this->getRequestTime(),
        );
    }

    public function exchangeArray(array $data)
    {
        foreach ($data as $field => $value) {
            $field = strtolower($field);
            switch ($field) {
                case 'userid':
                    $this->setUserId($value);
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

    public function generateRequestKey()
    {
        $this->setRequestKey(strtoupper(substr(sha1(
            $this->getUserId() .
            '####' .
            $this->getRequestTime()->format('U')
        ),0,15)));
    }

    public function getFieldMapWriteable()
    {
        return [
            'userId' => 'user_id',
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

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($id)
    {
        $this->userId = $id;

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
