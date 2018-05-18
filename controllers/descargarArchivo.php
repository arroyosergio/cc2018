<?php    
        $filename='adrian.docx';
        $ruta=$_SERVER['DOCUMENT_ROOT'] .'/controlCongresos/docs/'.$filename;
        if (file_exists($ruta)) {
            // download file
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=".$filename."");
            header("Content-Transfer-Encoding: binary");
            header("Content-Type: binary/octet-stream");

            ob_clean();
            flush();
            readfile($ruta);
            echo 'ok';
            exit();
       }
