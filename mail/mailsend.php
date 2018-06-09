<?php>

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include './Akismet.class.php'
$config = include('config.php')

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

$name = $_POST["name"]
$email = $_POST["email"]
$content = $_POST["message"]

$akismet = new Akismet($config['akismet_site_url'], $config['akismet_api_key']);

$akismet->setCommentAuthor($name);
$akismet->setCommentAuthorEmail($email);
$akismet->setCommentContent($content);

if ($akismet->isCommentSpam()) {
    // byeeeee
} else {
    $success = smtp_mail($name, $email, $content);
    if ($success) {
        readfile('index.html');
    } else {

    }
}

function smtp_mail($name, $email, $content) {
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host = $config['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['smtp_username'];
        $mail->Password = $config['smtp_password'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = '587';

        $mail->setFrom('mailing@connoor.io', 'Contact Form @ connoor.io');
        $mail->addAddress($config['smtp_destination_address']);
        $mail->addReplyTo($email);

        $mail->isHTML(false);
        $mail->Subject('New contact from ' . $name)
        $mail->Body = $content;

        $mail->send();
        return true;
    } catch(Exception $e) {
        error_log($mail->ErrorInfo);
        return false;
    }
}
?>