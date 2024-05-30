<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
// reference the Dompdf namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
            
if (isset($_POST['submitcv'])) {
            $path = 'upload/' . $_FILES["attachment"]["name"];
            move_uploaded_file($_FILES["attachment"]["tmp_name"], $path);
            $data = $_POST;

            $mail = new PHPMailer(true);

            //Server settings
            $mail->isSMTP();
            $mail->Host = 'mail.aitech.lk';
            $mail->SMTPAuth = true;
            $mail->Username = 'gayanc@aitech.lk';
            $mail->Password = 'gayan@1234';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            //Recipient
            $mail->From = $_POST["email"];     //Sets the From email address for the message
            $mail->FromName = $_POST["name"];    //Sets the From name of the message
            $mail->addAddress('contact@aitech.lk', 'A I TECH');

            //Content
            $mail->isHTML(true);
            $mail->AddAttachment($path);
            $mail->Subject = 'AI TECH - New Career Application';
            $emailBody = '
            <h3 align="center">Applier Details</h3>
            <table border="1" width="100%" cellpadding="5" cellspacing="5">
                <tr>
                    <td width="30%">Name</td>
                    <td width="70%">' . $_POST["name"] . '</td>
                </tr>
                <tr>
                    <td width="30%">Email Address</td>
                    <td width="70%">' . $_POST["email"] . '</td>
                </tr>
                <tr>
                    <td width="30%">Position</td>
                    <td width="70%">' . $_POST["position"] . '</td>
                </tr>
                <tr>
                    <td width="30%">Message</td>
                    <td width="70%">' . $_POST["message"] . '</td>
                </tr>
            </table>
            ';

            $secretKey = "6LfBhJIpAAAAAFJpD2mTEDnqcsJgbjsLk5S3dtEg";
            $responseKey = $_POST['g-recaptcha-response'];

            $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey";

            $response = file_get_contents($url);
            $response = json_decode($response);

            $mail->Body = $emailBody;



            if ($response->success) {
                if ($mail->Send()) {
                   $msg='<div class="alert alert-success" style="text-align: center;">Email Sent Successfully</div>';
                   unlink($path);
                }
                else{
                   $msg='<div class="alert alert-danger" style="text-align: center;">Failed to send the message</div>';
                }
             }
             else{
                $msg='<div class="alert alert-danger" style="text-align: center;">Verification failed</div>';
             }
}

?>