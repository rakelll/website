<?php
	header('Content-type: application/json');
	$status = array(
		'type'=>'success',
		'message'=>'Email sent!'
	);

    $name = @trim(stripslashes($_POST['contactName']));
    $email = @trim(stripslashes($_POST['contactEmailAddress']));
    $subject = @trim(stripslashes($_POST['subject']));
    $message = @trim(stripslashes($_POST['contactTextArea']));

    $email_from = $email;
    $email_to = 'dibgeovani@gmail.com';

    $body = 'Name: ' . $name . "\n\n" . 'Email: ' . $email . "\n\n" . 'Subject: ' . $subject . "\n\n" . 'Message: ' . $message;

    $success = @mail($email_to, $subject, $body, 'From: <'.$email_from.'>');

    echo json_encode($status);
    die;
