<?php
/**
 * File: registration.class.php
 * This application is used to authenticate users
 *
 * @package LoginApp
 * @author  php@softbizsolution.com
 * @version $Id$
 *
 */

class RegistrationApp extends DefaultApplication
{
    /**
    * This is the "main" function which is called to run the application
    *
    * @param none
    * @return true if successful, else returns false
    */
    function run()
    {
        $cmd = getUserField('cmd');  
      
        switch ($cmd)
        {
            case 'checkuser'  : $screen = $this->isUsernameExists(); break;
            case 'checkemail' : $screen = $this->isEmailExists(); break;
            case 'save'       : $screen = $this->save(); break;
            default           : $screen = $this->showEditor(); break;
        }

        // Set the current navigation item
        $this->setNavigation('user');
      
        if ($cmd == 'checkuser' || $cmd == 'checkemail')
        {
            return;
        }
      
        echo $screen;
   }
   
    function showEditor()
    {
        return createPage(REGISTRATION_TEMPLATE, $data);
    }

    function save()
    {
        $thisUser = new User();

        if($thisUser->addUser())
        {
            $msg         = $this->getMessage(USER_SAVE_SUCCESS_MSG);
            $data['msg'] = 'Success'; 
            
            $this->sendMail();
        }
        else
        {
            $msg = $this->getMessage(USER_SAVE_ERROR_MSG);
            $data['msg'] = 'Error';
        }
         
        echo createPage(LANDING_TEMPLATE, $data);
    }
   
    function sendMail()
    {
        $data['firstname']   = getUserField('first_name');
        $data['lastname']    = getUserField('last_name');
        $data['username']    = getUserField('username');
        $data['email']       = getUserField('email');
        $data['password']    = getUserField('password');
     
        //Instantiate the phpMailer class
        $mail                 = new phpmailer();
        $mail->Host           = '123.49.44.3';
        $mail->Mailer         = "sendmail";
        $mail->SMTPAuth       = false;
        $mail->FromName       = 'BSTF Team';
        $mail->From           = 'sa@erd.gov.bd';
        $mail->Subject        = 'BSTF Registration Successfull';
     
        $body = createPage(EMAIL_TEMPLATE, $data);

        $mail->Body           = nl2br(html_entity_decode($body));
        $mail->IsHTML(true);

        $mail->AddAddress($data['email']);
        
        $mailSent = $mail->Send();
        
        return $mailSent;
    }
   
    function isUsernameExists()
    {
        $username = getUserField('username');
       
        $info['table'] = USER_TBL;
        $info['debug'] = false;
        $info['where'] = 'username = ' . q($username);
       
        $result = select($info);
       
        if ( !empty($result) )
        {
            echo json_encode('1');
        }
        else
        {
            echo json_encode('');
        }
    }
   
    function isEmailExists()
    {
        $email = getUserField('email');

        $info['table'] = USER_TBL;
        $info['debug'] = false;
        $info['where'] = 'email = ' . q($email);

        $result = select($info);

        if ( !empty($result) )
        {
           echo json_encode('1');
        }
        else
        {
           echo json_encode('');
        }       
    }
} // End class

?>