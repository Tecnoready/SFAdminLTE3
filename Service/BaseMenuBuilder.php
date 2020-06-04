<?php

/*
 * This file is part of the SFAdminLTE3Bundle package.
 *
 * (c) Tecnoready <https://github.com/Tecnoready/SFAdminLTE3Bundle/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\SFAdminLTE3Bundle\Service;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base para menu
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class BaseMenuBuilder
{

    use \Symfony\Component\DependencyInjection\ContainerAwareTrait;

    /**
     * @var \Knp\Menu\MenuFactory
     */
    protected $factory;
    
    /**
     * Menu principal
     * @param array $options
     */
    public abstract function createMainMenu(array $options);
    
    /**
     * Menu superior
     * @param array $options
     */
    public abstract function createTopMenu(array $options);

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }
    
    /**
     * 
     * @param array $options
     * @return \Knp\Menu\MenuItem
     */
    protected function createRootMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root', [
            "childrenAttributes" => [
                "class" => "nav nav-pills nav-sidebar flex-column",
                "data-widget" => "treeview",
                "role" => "menu",
                "data-accordion" => "false",
            ],
            "options" => [
                'ancestorClass' => 'active',
                'currentClass' => 'active',
            ]
        ]);
        return $menu;
    }
    
    /**
     * 
     * @param array $options
     * @return \Knp\Menu\MenuItem
     */
    public function createRootTopMenu(array $options)
    {
        $menu = $this->factory->createItem('root', [
            "childrenAttributes" => [
                "class" => "navbar-nav",
            ],
            "options" => [
                'ancestorClass' => 'active',
                'currentClass' => 'active',
            ],
        ]);
        return $menu;
    }
    
    /**
     * Checks if the attributes are granted against the current authentication token and optionally supplied subject.
     *
     * @throws \LogicException
     *
     * @final
     */
    protected function isGranted($attributes, $subject = null): bool
    {
        if (!$this->container->has('security.authorization_checker')) {
            throw new \LogicException('The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".');
        }

        return $this->container->get('security.authorization_checker')->isGranted($attributes, $subject);
    }

    /**
     * @required
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
