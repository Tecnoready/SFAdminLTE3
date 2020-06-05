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
//        self::installFOSUserBundle($vendorDir, $fs);
        self::menu($vendorDir, $fs);
//        self::installGitIgnore($vendorDir, $fs);
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
     * Coloca la base del menu
     * @param type $vendorDir
     * @param Filesystem $fs
     */
    protected static function menu($vendorDir, Filesystem $fs)
    {
        self::write("Instalando enlace simbolico a plantilla FOSUserBundle");
        $targetDir = $vendorDir . "/../src/Service/MenuBuilder.php";
        if (!$fs->exists($targetDir)) {
            $menuSkeleton = 
<<<EOF
<?php

/*
 * This file is part of the SFAdminLTE3Bundle package.
 *
 * (c) Tecnoready <https://github.com/Tecnoready/SFAdminLTE3Bundle/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
                    
namespace App\Service;

use Tecnoready\SFAdminLTE3Bundle\Service\BaseMenuBuilder;

/**
 * Constructor del menu
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class MenuBuilder extends BaseMenuBuilder
{
    public function createMainMenu(array \$options)
    {
        \$menu = \$this->createRootMainMenu(\$options);
        
        //TODO agrege sus menus
        /*
        \$menu->addChild('menu.home', [
            'route' => 'p_main_index',
        ]);
        if (\$this->isGranted("ROLE_AREA_ADMIN_PAGE")) {
            \$menu->addChild('menu.admin', [
                'route' => 'easyadmin',
                "extras" => [
                    "icon" => "fa fa-fw fa-cogs"
                ]
            ]);
        }
        */
        
        \$menu->addChild('menu.logout', [
            'route' => 'fos_user_security_logout',
            "extras" => [
                "icon" => "fas fa-fw fa-sign-out-alt"
            ]
        ]);
        return \$menu;
    }

    public function createTopMenu(array \$options)
    {
        \$menu = \$this->createRootTopMenu(\$options);

        \$menu->addChild('menu.top.pushmenu', [
            'uri' => '#',
            "linkAttributes" => [
                "data-widget" => "pushmenu",
            ],
             "extras" => [
                "icon" => "fas fa-bars",
            ]
        ]);
        //TODO agrege sus menus
        //\$menu->addChild('menu.home', [
        //    'route' => 'p_main_index',
        //]);
        return \$menu;
    }
}
EOF;
            
            $fs->dumpFile($targetDir, $menuSkeleton);
            self::write("Se creo un esqueleto del menu en '%s'", $targetDir);
        } else {
            self::write("El menu '%s' ya existe y se ignoro", $targetDir);
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
