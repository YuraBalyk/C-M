<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/phpmailer/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/phpmailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/phpmailer/src/SMTP.php';

$sitname = $_SERVER['SERVER_NAME'];
$mail = new PHPMailer(true);
$ip      = $_SERVER['REMOTE_ADDR'];
// Перевірка та очищення вхідних даних
$name = $telefon = $contact = $username = '';
if (isset($_POST['form'])) {
  $name = htmlspecialchars($_POST['form'], ENT_QUOTES, 'UTF-8');
}
if (isset($_POST['phone'])) {
  $telefon = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
}
if (isset($_POST['contact'])) {
  $contact = htmlspecialchars($_POST['contact'], ENT_QUOTES, 'UTF-8');
}
if (isset($_POST['username'])) {
  $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
}

$message = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New order from the website ' . htmlspecialchars($sitname, ENT_QUOTES, 'UTF-8') . '</title>
    <style>
        body {
            background-color: #FFFDFD;
            font-family: Arial, sans-serif;
            color: #181E34;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #F8F8F8;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #759EA0;
        }
        .details {
            background-color: #E2D9BA;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>New order from the website ' . htmlspecialchars($sitname, ENT_QUOTES, 'UTF-8') . '</h2>
        <div class="details">
            <p><strong>Name</strong> ' . $name . '</p>
            <p><strong>Phone:</strong> ' . $telefon . '</p>
            <p><strong>Contact:</strong> ' . $contact . '</p>
            <p><strong>Username:</strong> ' . $username . '</p>
            <p><strong>IP:</strong> ' . $ip . '</p>
        </div>
    </div>
</body>
</html>';

$zakaz_zvonka_admin_subject = "New order from the website {$sitname}";
header('Content-Type: application/json; charset=utf-8');
try {
    // Налаштування SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'tumchykandreycm@gmail.com';
    $mail->Password = 'akqjivdkpdopecsg';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Відправник та одержувач
    $mail->setFrom('no-reply@' . $sitname, 'website - ' . $sitname  );
    $mail->addAddress('MoveCleanLtd@gmail.com');

    // Вміст листа
    $mail->isHTML(true);
    $mail->Subject = $zakaz_zvonka_admin_subject;
    $mail->Body = $message;

    $mail->send();
    echo json_encode([
        "status" => "success",
        "message" => "The order has been successfully sent!"
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Error when sending an order: {$mail->ErrorInfo}"
    ], JSON_UNESCAPED_UNICODE);
}
