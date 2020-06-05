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
        self::handle($event->getComposer()->getConfig()->get('vendor-dir'));
    }

    public static function postUpdate(Event $event)
    {
        self::handle($event->getComposer()->getConfig()->get('vendor-dir'));
    }

    public static function handle($vendorDir)
    {
        self::write("###> tecnoready/sf-adminlte3-bundle ###");
        $fs = new Filesystem();
        self::installFOSUserBundle($vendorDir, $fs);
        self::installGitIgnore($vendorDir, $fs);
        self::write("###< tecnoready/sf-adminlte3-bundle ###");
    }

    /**
     * Colcoar plantillas de fos
     * @param type $vendorDir
     * @param Filesystem $fs
     */
    protected static function installFOSUserBundle($vendorDir, Filesystem $fs)
    {
        $folderDir = __DIR__ . "/../Resources/views/FOSUserBundle";

        self::write("Instalando enlace simbolico a plantilla FOSUserBundle");
        $targetDir = $vendorDir . "/../templates/bundles/FOSUserBundle";
        if (!$fs->exists($targetDir)) {
            $fs->symlink($folderDir, $targetDir);

            self::write("Se hizo un enlace simbolico de %s a %s", $folderDir, $targetDir);
        } else {
            self::write("El path '%s' ya existe y se ignoro", $targetDir);
        }
    }
    
    /**
     * Completa el git igonore
     * @param type $vendorDir
     * @param Filesystem $fs
     */
    protected static function installGitIgnore($vendorDir, Filesystem $fs)
    {
        $targetDir = $vendorDir . "/../.gitignore";
        
        $toIgnore = ["templates/bundles/FOSUserBundle"];
        if($fs->exists($targetDir)){
            $handle = fopen($targetDir, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    $line = str_replace("\n", "", $line);
//                    var_dump($line);
                    if(in_array($line, $toIgnore)){
                        $index = array_search($line, $toIgnore);
                        unset($toIgnore[$index]);
                    }
                }
                fclose($handle);
            }
        }else{
            $fs->dumpFile($targetDir, "");
        }
        
        //Si falta algunas lineas hay que agregarlas
        if(count($toIgnore) > 0){
            $fp = fopen($targetDir, 'a');//opens file in append mode
            fwrite($fp,"\n\n"."###> tecnoready/sf-adminlte3-bundle ###");  
            foreach ($toIgnore as $line) {
                fwrite($fp,"\n".$line);  
            }
            fwrite($fp,"\n"."###< tecnoready/sf-adminlte3-bundle ###\n");
            fclose($fp);
            self::write("Se actualizo el archivo '%s'",$targetDir);
        }
    }
    
    protected static function write()
    {
        $args = func_get_args();
//        $message = $args[0];
//        unset($args[0]);
        $message = call_user_func_array("sprintf", $args);
//        sprintf($message, $args);
        //var_dump($args);
        echo "\n********** ".$message." **********";
    }

}
