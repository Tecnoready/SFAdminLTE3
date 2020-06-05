# SFAdminLTE3
Plantilla de AdminLTE 3 para symfony 4.x

### 1) Actualizar composer.json la seccion de "scripts"

    "scripts": {
            "local-scripts-post": [
                "Tecnoready\\SFAdminLTE3Bundle\\Service\\ScriptHandler::postInstall"
            ],
            "post-install-cmd": [
                "@local-scripts-post"
            ],
            "post-update-cmd": [
                "@local-scripts-post"
            ]
        }

### 2) .gitignore
Ignorar el path `templates/bundles/FOSUserBundle`

### 2) Instalar paquete

    composer require tecnoready/sf-adminlte3-bundle

### 3) Configurar bundle:

    sf_admin_lte3:
        menu_builder: App\Service\MenuBuilder

Crear clase Menu: `App\Service\MenuBuilder` debe heredar de `Tecnoready\SFAdminLTE3Bundle\Service\BaseMenuBuilder`
