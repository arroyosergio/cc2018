<?php

class Guia extends Controller {

    function __construct() {
        parent::__construct();
        $this->view->css = array(
            //'public/plugins/toastr/toastr.min.css',
            //'public/plugins/datatable/jquery.datatables.min.css',
            //'public/plugins/datapicker/bootstrap-datepicker.min.css',
            'public/bootstrap/css/bootstrap.min.css',
            'public/fontawesome/css/font-awesome.min.css',
            'public/css/animate.min.css',
            'public/css/fluidbox.min.css',
            'public/css/style.css',
			'views/guia/css/guia.css'
        );
        $this->view->js = array(
            //'views/hospedaje/js/hospedaje.js'
            'public/js/jquery-2.1.4.min.js',
            'public/js/bootstrap.min.js',
            'public/js/index.js',
            'public/js/jquery.fluidbox.min.js',
        );
    }

    function index() {
        $this->view->render("guia/index");
    }

}
