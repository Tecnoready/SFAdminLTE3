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

### 2) Instalar paquete

    php -d memory_limit=-1 /usr/local/bin/composer require tecnoready/sf-adminlte3-bundle

### 3) Configurar bundle:

    sf_admin_lte3:
        menu_builder: App\Service\MenuBuilder
        app_name: Mi aplicacion


- Crear clase Menu: `App\Service\MenuBuilder` debe heredar de `Tecnoready\SFAdminLTE3Bundle\Service\BaseMenuBuilder`
- Edite su `webpack.config.js` y agregue las lineas:
      .addEntry('dependencies', './assets/js/dependencies.js')
      .copyFiles([
        {from: './node_modules/ckeditor/', to: 'ckeditor/[path][name].[ext]', pattern: /\.(js|css)$/, includeSubdirectories: false},
        {from: './node_modules/ckeditor/adapters', to: 'ckeditor/adapters/[path][name].[ext]'},
        {from: './node_modules/ckeditor/lang', to: 'ckeditor/lang/[path][name].[ext]'},
        {from: './node_modules/ckeditor/plugins', to: 'ckeditor/plugins/[path][name].[ext]'},
        {from: './node_modules/ckeditor/skins', to: 'ckeditor/skins/[path][name].[ext]'}
      ])
      .autoProvideVariables({
          moment: "moment"
      })
      .addPlugin(new MomentLocalesPlugin({
          localesToKeep: ['es'],
      }));
- Agregar los temas a `twig.yaml`:
      twig:
          form_themes:
              - '@SFAdminLTE3/default/theme.html.twig'
              - '@TetranzSelect2Entity/Form/fields.html.twig'
              - '@FOSCKEditor/Form/ckeditor_widget.html.twig'
