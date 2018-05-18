<?php

class View {

    function __construct() {
//        echo "this is the view<br/>";
    }
    
    public function render($name, $noInclude= FALSE) {
        if ($noInclude) {
            require_once 'views/'.$name.'.php';
        }else{
            require_once 'views/header.php';
            require_once 'views/'.$name.'.php';
            require_once 'views/footer.php';
        }
        
    }
}