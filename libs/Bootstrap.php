<?php

class Bootstrap {

    function __construct() {
        $url = isset($_GET["url"]) ? $_GET["url"]: NULL;
        $url = rtrim($url, "/");
        $url = explode("/", $url);
        if (empty($url[0])) {
            require_once 'controllers/index.php'; 
            $controller = new Index();
            $controller->loadModel('index');
            $controller->loadMail();
            $controller->loadPDF();
//            $controller->loadBarCodeEAN13();
            $controller->index();
            return FALSE;
        }
        
        $file = 'controllers/' . $url[0] . '.php';
        
        if (file_exists($file)) {
            require_once $file;
        }else{
            require_once './controllers/error.php';
            $controller = new Error();
            return FALSE;
        }
        
        $controller = new $url[0];
        $controller->loadModel($url[0]);
        $controller->loadMail();
        $controller->loadPDF();
        $controller->loadXmlCFDI();
//      $controller->loadBarCodeEAN13();
//      Llamado de métodos
        if (isset($url[2])) {
            if (method_exists($controller, $url[1])) {
                  $controller->{$url[1]}($url[2]);
            }else{
//                Vista de error
                echo "error";
            }
        } else {
            if (isset($url[1])) {
                $controller->{$url[1]}();
                return FALSE;
            }else{
                $controller->index();
            }
        }
    }

}
