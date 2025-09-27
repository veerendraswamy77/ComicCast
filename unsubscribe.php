<?php
require 'functions.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['unsubscribe_email'])) {
        $email = trim($_POST['unsubscribe_email']);
        $code = generateVerificationCode();
        sendVerificationEmail($email, $code);
        $msg = "Unsubscribe code sent to $email.";
    } elseif (isset($_POST['verification_code'])) {
        $email = trim($_POST['unsubscribe_verify_email']);
        $code = trim($_POST['verification_code']);
        if (verifyCode($email, $code)) {
            unsubscribeEmail($email);
            $msg = "Email $email unsubscribed successfully.";
        } else {
            $msg = "Invalid verification code.";
        }
    }
}
?>

<form method="POST">
    <h2>Unsubscribe</h2>
    <input type="email" name="unsubscribe_email" required placeholder="Enter your email">
    <button id="submit-unsubscribe">Unsubscribe</button>
</form>

<form method="POST">
    <h2>Confirm Unsubscription</h2>
    <input type="email" name="unsubscribe_verify_email" required placeholder="Enter your email again">
    <input type="text" name="verification_code" maxlength="6" required placeholder="Enter verification code">
    <button id="submit-verification">Verify</button>
</form>

<p><?= htmlspecialchars($msg) ?></p>
