<?php

   $fName = $_REQUEST["firstName"];
   $lName = $_REQUEST["lastName"];
   $email = $_REQUEST["email"];
   $whatsUrQuestion = $_REQUEST["whatsUrQuestion"];
   $phoneNumber = $_REQUEST["phoneNumber"];
   $country = $_REQUEST["countryInput"];
   $comments = $_REQUEST["comments"];

   print($fName);
   $to = "info@pbs-edu.fr";
   $subject = "Contact details of $fName";
   
   $message = "<table>";

   $message .= "<tr><td>First Name</td><td>:</td><td>$fName</td></tr>";
   $message .= "<tr><td>Last Name</td><td>:</td><td>$lName</td></tr>";

   $message .= "<tr><td>Email</td><td>:</td><td>$email</td></tr>";
   $message .= "<tr><td>Programme</td><td>:</td><td>$whatsUrQuestion</td></tr>";

   $message .= "<tr><td>Phone Number</td><td>:</td><td>$phoneNumber</td></tr>";
   $message .= "<tr><td>Country</td><td>:</td><td>$country</td></tr>";

   $message .= "<tr><td>Comments</td><td>:</td><td>$comments</td></tr>";

   $message .= "</table>";

   $headers = "Reply-To: The Sender info@pbs-edu.fr\r\n"; 
   $headers .= "Return-Path: The Sender info@pbs-edu.fr\r\n"; 
   $headers .= "From: info@pbs-edu.fr" ."\r\n" .
   $headers .= "Organization: PBS\r\n";
   $headers .= "MIME-Version: 1.0\r\n";
   $headers .= "Content-type: text/html; charset=utf-8\r\n";
   $headers .= "X-Priority: 3\r\n";
   $headers .= "X-Mailer: PHP". phpversion() ."\r\n" ;

   
   $retval = mail ($to,$subject,$message,$headers);
   
   if( $retval == true ) {
      echo "Message sent successfully...";
   }else {
      echo "Message could not be sent...";
   }
?>