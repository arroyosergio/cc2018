<?php

define('URL', "http://localhost/sensontl/"); //"http://localhost/cica2016/" "http://higo-software.com/controlCongresos/"
define('DOCS', $_SERVER['DOCUMENT_ROOT'] .'/sensontl/docs/');///congreso/docs/  /cica2016/docs/
define('DOCSDEPOSITOSPUBLICOS', $_SERVER['DOCUMENT_ROOT'] .'/sensontl/docs/dep_publico/');
define('MENULATERAL',$_SERVER['DOCUMENT_ROOT'] .'/sensontl/views/menu/menu.html');
define('MENUADMIN',$_SERVER['DOCUMENT_ROOT'] .'/sensontl/views/menu/menuadmin.html');
define('MENUEDITOR',$_SERVER['DOCUMENT_ROOT'] .'/sensontl/views/menu/menueditor.html');

/*
* Parametros para conexion al Webservice  del timbrado de los cfdi
* [Timbox Pruebas]     https://staging.ws.timbox.com.mx/timbrado_cfdi33/wsdl
* [Timbox Producción]  https://sistema.timbox.com.mx/timbrado_cfdi33/wsdl
*/
define('WSDL_URL','https://staging.ws.timbox.com.mx/timbrado_cfdi33/wsdl');
define('WSDL_USUARIO','AAA010101000');
define('WSDL_CONTRASENA','h6584D56fVdBbSmmnB');

/*
* Configuraciones de las fechas del sistema.
*/
define('FECHAAPERTURA',"2018-04-01");
define('FECHACIERRE',"2018-06-30");
define('FECHALIMITEDESCUENTOGENERAL',"2018-06-30");

/*
* 
* Fechas de los descuesto aplicables a las cuotas.
*/
define('FECHADESCUENTOAUTORES',"2018-05-20");
define('FECHADESCUENTOPUBLICO',"2018-05-20");

/*
* Parametro de cuotas.
*/
define('CUOTAAUTORDESCUENTO',2600.0);
define('CUOTAAUTOR',2950.0);
define('CUOTAPUBLICODESCUENTO',2200.00);
define('CUOTAPUBLICO',2650.0);




