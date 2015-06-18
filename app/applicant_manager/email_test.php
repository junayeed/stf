<?php
/**
 * This is the application file that is invoked to
 * instantiate the application manager application
 */

   // include the main configuration file
   require_once($_SERVER['DOCUMENT_ROOT'] .'/app/common/conf/main.conf.php');
   require_once(LOCAL_CONFIG_DIR          .'/dp.conf.php');
   require_once(LOCAL_LIB_DIR             .'/dp.lib.php');
   require_once(EXT_DIR                   .'/excel/PHPExcel.php');

        //Instantiate the phpMailer class
        $mail                 = new phpmailer();
        $mail->Host           = 'mail.plandiv.gov.bd';
        $mail->Mailer         = "sendmail";
        $mail->SMTPAuth       = true;
        $mail->Username       = 'junayeed@ide.plandiv.gov.bd';
        $mail->Password       = 'Ide2015';
        $mail->SMTPDebug      = true;
        $mail->FromName       = 'BSTF Team';
        $mail->From           = 'sa@erd.gov.bd';
        $mail->Subject        = 'BSTF Grant Accpeted';//$data['subject'];
        $body                 = 'Harun mail paiso??';
        $mail->Body           = nl2br(html_entity_decode($body));

        $mail->IsHTML(true);

        //$mail->AddAddress($data['email'], '');
        $mail->AddAddress('junayeed@gmail.com');

//        $mailSent = $mail->Send();
//        dumpVar($mail->ErrorInfo);
//        echo_br('Mail Sent = ' . $mailSent);
        if(!$mail->Send()) 
        {
            echo "Mailer Error: " . $mail->ErrorInfo; 
        } 
        else 
        {
            echo "Message sent!";
        }
?>