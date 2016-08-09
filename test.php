<?php
require './vendor/autoload.php';

if (isset($_POST['send'])) {
    if ($_POST['service'] == 'mailgun') {
        $domain = "sandbox47f4f4b97e964d7c8bf878b54b484322.mailgun.org";
        $apiKey = 'key-a9510d048d394a269aa20f4de229e094';
        $sm = new \SendMail\SendMail('', $apiKey, 'mailgun', $domain);
        //PostArray for MailGun
        $post = ['from' => isset($_POST['from']) && !empty($_POST['from'])? $_POST['from'] : 'Mailgun Sandbox <v.ganzyuk@hexa.com.ua>',
            'to' => isset($_POST['to']) && !empty($_POST['to']) ? $_POST['to'] : 'User name <v.ganzyuk@hexa.com.ua>',
            'subject' => isset($_POST['subject']) && !empty($_POST['to'])? $_POST['subject'] : 'Hello',
            'html' => isset($_POST['html']) && !empty($_POST['subject'])? $_POST['html'] : 'Congratulations, you just sent an email with Mailgun!  You are truly awesome!  You can see a record of this email in your logs: https://mailgun.com/cp/log .  You can send up to 300 emails/day from this sandbox server.  Next, you should add your own domain so you can send 10,000 emails/month for free.',
            'cc' => isset($_POST['cc']) ? $_POST['cc'] : [],
            'bcc' => isset($_POST['bcc']) ? $_POST['bcc'] : [],
            'files' => $_FILES['files'],
        ];
    } elseif ($_POST['service'] == 'sendgrid') {
        $sm = new \SendMail\SendMail('http://mailservice.local/api/emails/send', 'f8MOZ9dYU4ap5F12m95PIPAA5AJG3Sh6');
        //PostArray for SendGrid
        $post = [
            'from' => isset($_POST['from']) && !empty($_POST['from'])? $_POST['from'] : 'v.ganzyuk@hexa.com.ua',
            'to' => isset($_POST['to']) && !empty($_POST['to'])? $_POST['to'] : 'v.ganzyuk@hexa.com.ua',
            'subject' => isset($_POST['subject']) && !empty($_POST['subject'])? $_POST['subject'] : 'Subject',
            'name' => isset($_POST['name']) && !empty($_POST['name'])? $_POST['name'] : 'Sender name',
            'cc' => isset($_POST['cc']) && !empty($_POST['cc'])? $_POST['cc'] : 'v.ganzyuk@hexa.com.ua',
            'bcc' => isset($_POST['bcc']) && !empty($_POST['bcc'])? $_POST['bcc'] : 'v.ganzyuk@hexa.com.ua',
            'origin' => isset($_POST['origin']) && !empty($_POST['origin'])? $_POST['origin'] : 'Defines the origin of the email',
            'send_type' => 'now',
            'reply_to' => isset($_POST['reply_to']) && !empty($_POST['reply_to'])? $_POST['reply_to'] : 'reply_to',
            'html' => isset($_POST['html']) && !empty($_POST['html'])? $_POST['html'] : 'Text HTML',
            'files' => $_FILES['files'],
        ];
    }
    try {
        $httpResponseCode = $sm->send($post);
        if ($httpResponseCode == 200 || $httpResponseCode == 201) {
            echo $httpResponseCode . ' - OK';
        }
    } catch (Exception $e) {
        echo "<pre>";
        echo $e;
        echo 'Msg:' . $e->getMessage() . '<br>';
        echo 'Line: ' . $e->getLine() . '<br>';
        echo 'File: ' . $e->getFile() . '<br>';
    }
}

?>

<form action="" method="post" enctype="multipart/form-data">
    <p>
        <label for="service">Service:</label>
        <select name="service" id="service">
            <option value="sendgrid">Sendgrid</option>
            <option value="mailgun">MailGun</option>
        </select>
    </p>
    <p>
        <label for="from">From:</label>
        <input type="text" name="from" id="from">
    </p>
    <p>
        <label for="name">Sender name:</label>
        <input type="text" name="name" id="name">
    </p>
    <p>
        <label for="to">To:</label>
        <input type="text" name="to" id="to">
    </p>
    <p>
        <label for="subject">Subject:</label>
        <input type="text" name="subject" id="subject">
    </p>
    <p>
        <label for="html">Text HTML:</label><br>
        <textarea id="html" cols="30" name="html"></textarea>
    </p>
    <p>
        <input type="file" name="files[]"><br>
        <input type="file" name="files[]">
    </p>
    <input type="hidden" name="send" value="1">
    <input type="submit">
</form>