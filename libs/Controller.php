<?php

//NAMESPACE DE LA LIBRERIA PARA GENERER CFDI
use CFDI\CFDI;

class Controller {

    function __construct() {
//        echo "main controller <br/>";
        $this->view = new View();
	
    }
    
    public function loadModel($modelName) {
        $file = 'models/'.$modelName.'_model.php';
         if (file_exists($file)) {
            require_once $file;
            $modelName = $modelName ."_Model";
            $this->model = new $modelName;
        }
    }
    
    public function loadMail() {
	   $classPhpMailer = 'libs/PHPMailer.php';
	   $classPhpSMTP = 'libs/SMTP.php';
	   $classPhpException = 'libs/Exception.php';
	   try{
		   if (file_exists($classPhpMailer)) {
				require_once $classPhpMailer;
				require_once $classPhpSMTP;
				require_once $classPhpException;
				//CREA INSTANCIA DE LA LIBRERIA PHPMAILER
				$this->mail = new PHPMailer(true);
				// Set mailer to use SMTP 
				$this->mail->isSMTP();  		   
				//SE INDICA EL HOSTING  
				$this->mail->Host = HOST_MAIL;
				//PORT FOR GODDADY
				$this->mail->Port = 80;	
				//ONLY FOR GODADDY
				$this->mail->SMTPAutoTLS = false;
				//SET SMTPAUTH TRUE FOR GODDADY 
				$this->mail->SMTPAuth   = true;
				//ONLY FOR GODDADY
				$this->mail->SMTPSecure   = false;
				$this->mail->IsHTML(true);
				// ENABLE VERBOSe DEBUG OUTPUT
				// 1 CLIENT SIDE
				// 2 BOTH SIDE
				// 3 & 4 MORE INFORMATION		   
				$this->mail->SMTPDebug  = 0;
				$this->mail->Username   = "contacto@cica2018.mx";
				$this->mail->Password   ="ContactC2018*";
        	}
	   }catch (Exception $e){
	   	    error_log($e);
	   }
    }
    
    public function loadPDF() {
        $classfpdf = 'libs/pdf/fpdf.php';
        if (file_exists($classfpdf)) {
            require_once $classfpdf;
            $this->GeneratePDF = new FPDF();
		}
	}

//===================================================
//METODO PARA CARGAR LIBRERIA PARA GENERAR XML
//===================================================
	public function loadXmlCFDI() {
		$cer = file_get_contents('libs/CFDI/csd/CSD01_AAA010101AAA.cer.pem');
        $key = file_get_contents('libs/CFDI/csd/CSD01_AAA010101AAA.key.pem');
		$classCFDI = 'libs/CFDI/CFDI.php';
		if (file_exists($classCFDI)) {
			require_once $classCFDI;
			$this->GenerateCFDI = new CFDI($cer,$key);
		}
    }
    
	
	
//    public function loadBarCodeEAN13() {
//        $fileClass = 'libs/pdf/ean13.php';
//        if (file_exists($fileClass)) {
//            require_once $fileClass;
//            $this->BarCondeEAN13 = new PDF_EAN13();
//        }
//    }

}