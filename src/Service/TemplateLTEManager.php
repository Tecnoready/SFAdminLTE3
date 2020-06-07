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

use Symfony\Component\OptionsResolver\OptionsResolver;
use RuntimeException;

/**
 * Configuraciones del template manager
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class TemplateLTEManager
{
    /**
     * Opcines
     * @var array
     */
    private $options;
    
    
    public function __construct(array $options)
    {
        $this->setOptions($options);
    }
    
    public function setOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "user_profile_photo_property" => "photoFile",
            "default_avatar" => "bundles/sfadminlte3/img/avatar_m.png",
            "logo" => "build/images/logo.png",
            "route_runtime_js" => null,
        ]);
        $resolver->setDefined(["menu_builder","app_name","user_profile_photo_property","default_avatar","logo","route_main","route_runtime_js"]);
        $resolver->setRequired(["route_main","footer_version","footer_name","footer_href","footer_year"]);
        $this->options = $resolver->resolve($options);
        return $this;
    }
    
    /**
     * Buscar una opcion definida
     * @param type $option
     * @return type
     * @throws RuntimeException
     */
    public function getOption($option)
    {
        if(!isset($this->options[$option])){
            throw new RuntimeException(sprintf("La opción '%s' no existe. Las definidas son %s",$option, implode(", ",array_keys($this->options))));
        }
        
        return $this->options[$option];
    }



}
