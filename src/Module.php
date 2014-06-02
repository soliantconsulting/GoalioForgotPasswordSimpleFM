<?php

namespace Soliant\GoalioForgotPasswordSimpleFM;

use Zend\ModuleManager\ModuleManager;
use Zend\EventManager\EventManager;
use Soliant\SimpleFM;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->setAllowOverride(true);
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'Zend\Stdlib\Hydrator\ArraySerializable' => 'Zend\Stdlib\Hydrator\ArraySerializable',
            ),
            'factories' => array(
                'goalioforgotpassword_password_mapper' => function ($sm)
                {
                    $mapper = new Mapper\Password();
                    $mapper->setDbAdapter($sm->get('simplefm'));
                    $mapper->setHydrator($sm->get('Zend\Stdlib\Hydrator\ArraySerializable'));
                    $mapper->setEntityPrototype(new \Soliant\ZfcUserSimpleFm\Entity\Password);
                    $mapper->init(); # work around for constuctor params in ancestor

                    return $mapper;
                },
            ),
        );
    }
}
