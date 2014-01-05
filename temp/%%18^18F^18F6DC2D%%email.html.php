<?php /* Smarty version 2.6.6, created on 2014-01-05 06:59:53
         compiled from E:/xampp/htdocs/stf/app_contents/standard/registration/email.html */ ?>
<html>
    <body>
        <p>Dear <?php echo $this->_tpl_vars['first_name']; ?>
 <?php echo $this->_tpl_vars['last_name']; ?>
,</p>
        <p>Thanks for registration in Sweden Bangladesh Trust Fund. Now you can login and submit your application for STF scholarship. Below is you user information</p>
        <table border="0">
            <tr>
                <td>Name: </td>
                <td><?php echo $this->_tpl_vars['first_name']; ?>
 <?php echo $this->_tpl_vars['last_name']; ?>
</td>
            </tr>
            <tr>
                <td>Email: </td>
                <td><?php echo $this->_tpl_vars['email']; ?>
</td>
            </tr>
            <tr>
                <td>Username: </td>
                <td><?php echo $this->_tpl_vars['username']; ?>
</td>
            </tr>
            <tr>
                <td>Password: </td>
                <td><?php echo $this->_tpl_vars['password']; ?>
</td>
            </tr>
        </table>
        
        <p>If you need any further information please contact sa@erd.gov.bd.</p>
        <p>Thanks<br>STF Team</p>
    </body>
</html>