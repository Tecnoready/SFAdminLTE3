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

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Manejador de eventos de composer
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ScriptHandler
{
    public static function postInstall(Event $event)
    {
        self::handle($event);
    }

    public static function postUpdate(Event $event)
    {
        self::handle($event);
    }
    
    protected static function handle(Event $event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $fs = new Filesystem();
        self::installFOSUserBundle($vendorDir,$fs);
    }
    
    protected static function installFOSUserBundle($vendorDir,Filesystem $fs)
    {
        
        $folderDir = __DIR__."/../Resources/views/FOSUserBundle/layout.html.twig";

        echo "*** tecnoready/sf-adminlte3-bundle: Instalando enlace simbolico a plantilla FOSUserBundle... \t";
        $targetDir = $vendorDir."/../templates/bundles/FOSUserBundle";
        $fs->symlink($folderDir, $targetDir);

        echo 'SUCCESS: ';
        echo sprintf("Se hizo un enlace simbolico de %s a %s \n");
    }
}
