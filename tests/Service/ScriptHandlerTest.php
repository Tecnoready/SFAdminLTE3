<?php

/*
 * This file is part of the SFAdminLTE3Bundle package.
 *
 * (c) Tecnoready <https://github.com/Tecnoready/SFAdminLTE3Bundle/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\SFAdminLTE3Bundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Tecnoready\SFAdminLTE3Bundle\Service\ScriptHandler;
use Composer\Script\Event;

/**
 * Test de Manejador de eventos de composer
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ScriptHandlerTest extends TestCase
{
    public function testPostInstall()
    {
        $event = new Event();
        
        ScriptHandler::postInstall($event);
    }
}
