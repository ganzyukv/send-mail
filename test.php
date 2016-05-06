<?php
require './vendor/autoload.php';

//$sm = new \SendMail\SendMail('http://mailservice.local/send-mail/x.php', 'f8MOZ9dYU4ap5F12m95PIPAA5AJG3Sh6');
$sm = new \SendMail\SendMail('http://mailservice.local/api/emails/send', 'f8MOZ9dYU4ap5F12m95PIPAA5AJG3Sh6');
$post =
    ['from' => 'bob@example.com',
        'to' => 'sally@example.com',
        'reply_to' => 'sally@example.com',
        'subject' => 'The PHP SDK is awesome!',
        'html' => 'It is so simple to send a message.',
        'send_type' => 'now',
        'cc' => ['email1', 'email3', 'email2'],
        'bcc' => ['email1', 'email3', 'email2'],
        'origin' => 'Defines the origin of the email'

    ];
try {
    $httpResponseCode = $sm->send($post);
    if($httpResponseCode == 200 || $httpResponseCode == 201){
        echo $httpResponseCode. ' - OK';
    }
} catch (Exception $e) {
    //var_dump($e);
    echo 'Msg:'.$e->getMessage().'<br>';
    echo 'Line: '.$e->getLine().'<br>';
    echo 'File: '.$e->getFile().'<br>';


}


