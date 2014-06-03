<?php

namespace Soliant\GoalioForgotPasswordSimpleFM\Mapper;

use Soliant\SimpleFM\ZF2\Gateway\AbstractGateway;
use Soliant\GoalioForgotPasswordSimpleFM\Entity\Password as PasswordEntity;
use Traversable;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Soliant\SimpleFM\Adapter as DbAdapter;
use GoalioForgotPassword\Mapper\PasswordMapperInterface;

class Password extends AbstractGateway implements EventManagerAwareInterface, PasswordMapperInterface
{
    protected $tableName  = 'web_password';
    protected $dbAdapter;
    protected $entity;
    protected $hydrator;

    public function __construct()
    {
        // Clear abstract gateway and use in init
    }

    public function init()
    {
        $this->setEntityName(get_class($this->entity));
        $this->setSimpleFMAdapter($this->getDbAdapter());
        $this->setEntityLayout($this->getTableName());
    }

    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;

        return $this;
    }

    public function getHydrator()
    {
        return $this->hydrator;
    }

    public function setEntityPrototype(PasswordEntity $entity)
    {
        $this->entity = $entity;

        return $this;
    }

    public function getDbAdapter()
    {
        return $this->dbAdapter;
    }

    public function setDbAdapter(DbAdapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;

        return $this;
    }

    public function remove($passwordModel)
    {
#        $sql = new Sql($this->getDbAdapter(), $this->tableName);
#        $delete= $sql->delete();
#        $delete->where->equalTo($this->keyField, $passwordModel->getRequestKey());

        return true;
    }

    public function findByUserId($userId)
    {
        $entity = $this->findOneBy(array(
            'user_id' => $userId,
        ));

        return $entity;
    }

    public function findByRequestKey($key)
    {
        $entity = $this->findOneBy(array(
            'request_key' => $key,
        ));

        return $entity;
    }

    public function cleanExpiredForgotRequests($expiryTime=86400)
    {
        $now = new \DateTime((int)$expiryTime . ' seconds ago');

#        $sql = new Sql($this->getDbAdapter(), $this->tableName);
#        $delete = $sql->delete();
#        $delete ->where->lessThanOrEqualTo($this->reqtimeField, $now->format('Y-m-d H:i:s'));

        return true;
    }

    public function cleanPriorForgotRequests($userId)
    {
        $oldEntities = $this->findBy(array(
            'user_id' => $userId
        ));

        foreach ((array) $oldEntites as $entity) {
            $this->delete($entity);
        }

        return true;
    }

    public function findByUserIdRequestKey($userId, $token)
    {
        $entity = $this->findOneBy(array(
            'user_id' => $userId,
            'request_key' => $token,
        ));

        return $entity;
    }

    public function fromRow($row)
    {
throw new \Exception('fromRow not handled');
        if (!$row) return false;
        $evr = Model::fromArray($row->getArrayCopy());
        return $evr;
    }

    public function toScalarValueArray($passwordModel)
    {
        $hydrator = new \Zend\Stblib\Hydrator\ArraySerializable;

        return $hydrator->extract($passwordModel);
    }

    public function findByEmail($email)
    {
die('find by email');

        $entity = $this->findOneBy(array(
            'email' => $email
        ));

        return $entity;
    }

    public function findByUsername($username)
    {
        $entity = $this->findOneBy(array(
            'username' => $username
        ));

        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));

        return $entity;
    }

    public function findById($id)
    {
        $entity = $this->find($id);

        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));

        return $entity;
    }

    public function persist($entity)
    {
        $this->insert($entity);
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function insert($entity, $tableName = null, HydratorInterface $hydrator = null)
    {
        $dbAdapter = $this->getDbAdapter();

        return $this->create($entity);
    }

    public function update($entity, $where = null, $tableName = null, HydratorInterface $hydrator = null)
    {
        $newEntity = $this->edit($entity);

#        if (!$where) {
#            $where = array('user_id' => $entity->getId());
#        }

        return $newEntity;
    }

    /**
     * @var EventManagerInterface
     */
    protected $events;

    /**
     * Set the event manager instance used by this context
     *
     * @param  EventManagerInterface $events
     * @return mixed
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $identifiers = array(__CLASS__, get_called_class());
        if (isset($this->eventIdentifier)) {
            if ((is_string($this->eventIdentifier))
                || (is_array($this->eventIdentifier))
                || ($this->eventIdentifier instanceof Traversable)
            ) {
                $identifiers = array_unique(array_merge($identifiers, (array) $this->eventIdentifier));
            } elseif (is_object($this->eventIdentifier)) {
                $identifiers[] = $this->eventIdentifier;
            }
            // silently ignore invalid eventIdentifier types
        }
        $events->setIdentifiers($identifiers);
        $this->events = $events;

        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (!$this->events instanceof EventManagerInterface) {
            $this->setEventManager(new EventManager());
        }

        return $this->events;
    }
}
