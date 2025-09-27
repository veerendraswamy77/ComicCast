<?php

function generateVerificationCode() {
    return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

function registerEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    }
}

function unsubscribeEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES);
    $emails = array_filter($emails, fn($e) => trim($e) !== trim($email));
    file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL);
}

function sendVerificationEmail($email, $code) {
    $subject = "Your Verification Code";
    $message = "<p>Your verification code is: <strong>$code</strong></p>";
    $headers = "MIME-Version: 1.0\r\nContent-type: text/html\r\nFrom: no-reply@example.com";
    mail($email, $subject, $message, $headers);
    file_put_contents(__DIR__ . "/temp_codes/" . md5($email) . ".txt", $code);
}

function verifyCode($email, $code) {
    $path = __DIR__ . "/temp_codes/" . md5($email) . ".txt";
    if (file_exists($path) && trim(file_get_contents($path)) === $code) {
        unlink($path);
        return true;
    }
    return false;
}

function fetchAndFormatXKCDData() {
    $randomId = random_int(1, 3000);
    $json = @file_get_contents("https://xkcd.com/$randomId/info.0.json");
    if (!$json) return "<p>Failed to load comic.</p>";

    $data = json_decode($json, true);
    $img = htmlspecialchars($data['img']);
    return "<h2>XKCD Comic</h2><img src=\"$img\" alt=\"XKCD Comic\"><p><a href=\"http://localhost/src/unsubscribe.php\">Unsubscribe</a></p>";
}

function sendXKCDUpdatesToSubscribers() {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) return;
    $emails = file($file, FILE_IGNORE_NEW_LINES);
    $content = fetchAndFormatXKCDData();

    foreach ($emails as $email) {
        $subject = "Your XKCD Comic";
        $headers = "MIME-Version: 1.0\r\nContent-type: text/html\r\nFrom: no-reply@example.com";
        mail($email, $subject, $content, $headers);
    }
}
