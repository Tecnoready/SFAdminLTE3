import '../../../../../../../node_modules/@fortawesome/fontawesome-free/css/all.min.css';


import '../../../../../../../node_modules/icheck-bootstrap/icheck-bootstrap.min.css';
import '../../../../../../../node_modules/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css';

//Select2
import '../../../../../../../node_modules/select2/dist/css/select2.min.css';
import '../../../../../../../node_modules/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css';
import '../../../../../../../node_modules/select2/dist/js/select2.full.min.js';
import '../../../../../../../node_modules/select2/dist/js/i18n/es.js';

//Select2 entity
import '../../../../../../../vendor/tetranz/select2entity-bundle/Resources/public/js/select2entity.js';

//Sweetalert2
import '../../../../../../../node_modules/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css';
import Swal from '../../../../../../../node_modules/sweetalert2/dist/sweetalert2.min.js';
//import Swal from '../../../../../../../node_modules/sweetalert2/dist/sweetalert2.js';

//https://github.com/ninsuo/symfony-collection
//import '../../vendor/ninsuo/symfony-collection/jquery.collection.js';


global.jQuery = require('jquery');
global.$ = global.jQuery;

import '../../../../../../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js';
import '../../../../../../../node_modules/inputmask/dist/jquery.inputmask.min.js';//Mascaras para inputs (fecha)
import '../../../../../../../node_modules/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js';//Selector de tiempo (hora)

//Admin lte
import '../../../../../../../node_modules/admin-lte/dist/css/adminlte.min.css';
import '../../../../../../../node_modules/admin-lte/dist/js/adminlte.min.js';

//Vue.options.delimiters = ['{$', '$}'];

import { generateUrl, log } from './functions.js';


const routes = require('../../../../../../../public/assets/js/fos_js_routes.json');
import Routing from '../../../../../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);
Routing.generateUrl = generateUrl;

//global.Vue = Vue;
global.Routing = Routing;
global.Log = log;
global.Swal = Swal;