<?php
class Notificaciones extends Controller {

    function __construct() {
        parent::__construct();
        Session::init();
        $logged = Session::get("sesion");
        if (!$logged) {
            Session::destroy();
            header("location: index");
            exit;
        }
        $this->view->css = array(
			'public/bootstrap/css/bootstrap.min.css',
            'public/fontawesome/css/font-awesome.min.css',
            'public/css/animate.min.css',
            'public/css/fluidbox.min.css',
            'views/notificaciones/css/notificaciones.css',
			'public/plugins/datatable/jquery.datatables.min.css',
			'views/notificaciones/css/menu.css'
        );
				
        $this->view->js = array(
			'public/js/jquery-2.1.4.min.js',
			'public/bootstrap/js/bootstrap.min.js',
            'views/notificaciones/js/notificaciones.js',
			'public/plugins/datatable/jquery.datatables.min.js'
        );

    }



    function index() {
         $this->view->render("notificaciones/index");
    }

    function enviarNotificacion(){
        $response = "";
        if(!isset($_POST)){
            $response = false;
        }else{
            define( 'API_ACCESS_KEY', 'AAAATiYCqwA:APA91bFdSd7N5IGBa6FpigWfvK8OCen2QLZbaq_DOxQlhV86I4VRPSSMo2jaS71HF9GV8m_Pt0YxUJXy3qDlbMJHwW6ReCpiWKwoeXH944er9zT5CU2eOMlGewwe0mMJOsrRmgzywVwf');
            $titulo = $_POST['titulo'];
            $mensaje = $_POST['mensaje'];
            $dispositivos = $this->model->get_dispositivos();
            $registrationIDs = array();
            if($dispositivos != false){
                foreach($dispositivos as $dispositivo){
                    array_push($registrationIDs, $dispositivo['token']);
                }
            }
            // prep the bundle
            // to see all the options for FCM to/notification payload: 
            // https://firebase.google.com/docs/cloud-messaging/http-server-ref#notification-payload-support 

            // 'vibrate' available in GCM, but not in FCM
            $fcmMsg = array(
                'body' => $mensaje,
                'title' => $titulo
            );
            // I haven't figured 'color' out yet.  
            // On one phone 'color' was the background color behind the actual app icon.  (ie Samsung Galaxy S5)
            // On another phone, it was the color of the app icon. (ie: LG K20 Plush)

            // 'to' => $singleID ;  // expecting a single ID
            // 'registration_ids' => $registrationIDs ;  // expects an array of ids
            // 'priority' => 'high' ; // options are normal and high, if not set, defaults to high.
            $fcmFields = array(
                'registration_ids' => $registrationIDs,
                    'priority' => 'high',
                'notification' => $fcmMsg
            );

            $headers = array(
                'Authorization: key=' . API_ACCESS_KEY,
                'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
            $result = curl_exec($ch );
            $json_result = json_decode($result);
            curl_close( $ch );
            if($json_result->success > 0){
                $this->model->guardar_notificacion($titulo, $mensaje);
                echo "true";
            }else{
                echo "false";
            }
        }
    }
}

