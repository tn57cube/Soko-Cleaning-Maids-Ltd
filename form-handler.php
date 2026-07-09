<?php
$name = $_POST['name'];
$visitor_email = $_POST['email'];
$subject_email = $_POST['subject'];
$message_email = $_POST['message'];

$email_from = 'info@sokocleaningmaids.co.uk';

$email_subject = 'New Form Submission';

$email_body = "User Name:  $name.\n" .
  "User Email:  $visitor_email.\n" .
  "Subject: $subject_email.\n" .
  "User Message: $message_email.\n";

$to = 'tinashencube95@gmail.com';

$headers = "From:$email_from \r\n";

$headers .= "Reply-To:  $visitor_email \r\n ";


mail($to, $email_subject, $email_body, $headers);
header("Locattion: contact.html");
