<?php

/*
 * This file is part of the SFAdminLTE3Bundle package.
 *
 * (c) Tecnoready <https://github.com/Tecnoready/SFAdminLTE3Bundle/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\SFAdminLTE3Bundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use ReflectionClass;
use Tecnoready\SFAdminLTE3Bundle\Service\BaseMenuBuilder;
use LogicException;
use Tecnoready\SFAdminLTE3Bundle\Service\TemplateLTEManager;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SFAdminLTE3Extension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        $locator = new FileLocator(__DIR__.'/../Resources/config');
        $loaderYml = new Loader\YamlFileLoader($container, $locator);
        $loaderYml->load("services.yaml");
        
        
        $menuBuilder = new ReflectionClass($config["menu_builder"]);
        if($menuBuilder->isSubclassOf(BaseMenuBuilder::class) === false){
            throw new LogicException(sprintf('The "%s" must inherit from "%s"',$menuBuilder->getName(),BaseMenuBuilder::class));
        }
        $menuBuilderDefinition = new Definition($menuBuilder->getName());
        $menuBuilderDefinition
                ->addTag("knp_menu.menu_builder",["method" => "createMainMenu","alias"=> "main"])
                ->addTag("knp_menu.menu_builder",["method" => "createTopMenu","alias"=> "top"])
                ->setAutowired(true)
                ;
        $container->setDefinition("sf_admin_lte3.menubuilder", $menuBuilderDefinition);
        
        $container->setParameter("sf_admin_lte3.app_name", $config["app_name"]);
        
        $templateLTEManagerDefinition = new Definition(TemplateLTEManager::class);
        $templateLTEManagerDefinition
                ->addArgument($config["template_options"])
            ;
        
        $container->setDefinition(TemplateLTEManager::class, $templateLTEManagerDefinition);
    }
}
