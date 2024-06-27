<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  require 'PHPMailer-master/src/Exception.php';
  require 'PHPMailer-master/src/PHPMailer.php';
  require 'PHPMailer-master/src/SMTP.php';

function send_mail($recipient,$subject,$message)
{

  $mail = new PHPMailer();
  $mail->IsSMTP();

  $mail->SMTPDebug  = 1;  
  $mail->SMTPAuth   = TRUE;
  $mail->SMTPSecure = "tls";
  $mail->Port       = 587;
  $mail->Host       = "tls://smtp.gmail.com";
  //$mail->Host       = "smtp.mail.yahoo.com";
  $mail->Username   = "nathanflorendo22@gmail.com";
  $mail->Password   = "yqez zvnm snaq piil";

  $mail->IsHTML(true);
  $mail->AddAddress($recipient, "Poop butt");
  $mail->SetFrom("nathanflorendo22@gmail.com", "Flashcard Website");
  //$mail->AddReplyTo("reply-to-email", "reply-to-name");
  //$mail->AddCC("cc-recipient-email", "cc-recipient-name");
  $mail->Subject = $subject;
  $content = $message;

  $mail->MsgHTML($content); 
  if(!$mail->Send()) {
    // echo "Error while sending Email.";
    // echo "<pre>";
    // var_dump($mail);
    return false;
  } else {
    // echo "Email sent successfully";
    return true;
  }

}

?>