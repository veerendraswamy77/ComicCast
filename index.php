<?php
require 'functions.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        $code = generateVerificationCode();
        sendVerificationEmail($email, $code);
        $msg = "Verification code sent to $email.";
    } elseif (isset($_POST['verification_code'])) {
        $email = trim($_POST['verify_email']);
        $code = trim($_POST['verification_code']);
        if (verifyCode($email, $code)) {
            registerEmail($email);
            $msg = "Email $email verified and subscribed.";
        } else {
            $msg = "Invalid verification code.";
        }
    }
}
?>

<form method="POST">
    <h2>Subscribe</h2>
    <input type="email" name="email" required placeholder="Enter your email">
    <button id="submit-email">Submit</button>
</form>

<form method="POST">
    <h2>Verify Code</h2>
    <input type="email" name="verify_email" required placeholder="Enter your email again">
    <input type="text" name="verification_code" maxlength="6" required placeholder="Enter verification code">
    <button id="submit-verification">Verify</button>
</form>

<p><?= htmlspecialchars($msg) ?></p>
