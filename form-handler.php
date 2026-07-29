<!-- <?php
// ============================================================
//  form-handler.php — Soko Cleaning Maids Ltd
//  Handles contact form submissions from Contact.html
// ============================================================

// ---------- CONFIGURATION — edit these values ----------
$to_email      = "info@sokocleaningmaids.co.uk";   // Where emails are sent
$subject_prefix = "[Soko Cleaning Maids] ";          // Prefix for email subjects
$success_page  = "contact.html";                     // Redirect after success
$error_page    = "contact.html";                     // Redirect after failure
// -------------------------------------------------------

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: contact.html");
    exit;
}

// ---------- SANITIZE & VALIDATE INPUT ----------

function clean($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$name    = clean($_POST["name"]    ?? "");
$email   = clean($_POST["email"]   ?? "");
$subject = clean($_POST["subject"] ?? "");
$message = clean($_POST["message"] ?? "");

$errors = [];

// Name validation
if (empty($name)) {
    $errors[] = "Name is required.";
} elseif (strlen($name) < 2) {
    $errors[] = "Name must be at least 2 characters.";
}

// Email validation
if (empty($email)) {
    $errors[] = "Email is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

// Subject validation
if (empty($subject)) {
    $errors[] = "Subject is required.";
}

// Message validation
if (empty($message)) {
    $errors[] = "Message cannot be empty.";
} elseif (strlen($message) < 10) {
    $errors[] = "Message must be at least 10 characters.";
}

// ---------- SPAM PROTECTION (Honeypot) ----------
// Add a hidden field called "website" to your form — bots fill it, humans don't
if (!empty($_POST["website"])) {
    // Silently reject — looks like success to the bot
    header("Location: " . $success_page . "?status=sent");
    exit;
}

// ---------- IF ERRORS, REDIRECT BACK ----------
if (!empty($errors)) {
    $error_string = urlencode(implode("|", $errors));
    header("Location: " . $error_page . "?status=error&msg=" . $error_string);
    exit;
}

// ---------- BUILD THE EMAIL ----------
$email_subject = $subject_prefix . $subject;

$email_body = "
==============================================
  NEW ENQUIRY — Soko Cleaning Maids Ltd
==============================================

Name:     $name
Email:    $email
Subject:  $subject

Message:
--------------
$message
--------------

Sent:     " . date("d M Y, H:i:s") . "
IP:       " . $_SERVER["REMOTE_ADDR"] . "

==============================================
";

// Email headers
$headers  = "From: noreply@sokocleaningmaids.co.uk\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// ---------- SEND EMAIL ----------
$mail_sent = mail($to_email, $email_subject, $email_body, $headers);

// ---------- ALSO SEND AUTO-REPLY TO SENDER ----------
if ($mail_sent) {
    $auto_reply_subject = "Thank you for contacting Soko Cleaning Maids Ltd";
    $auto_reply_body = "
Dear $name,

Thank you for getting in touch with Soko Cleaning Maids Ltd!

We have received your message and will get back to you as soon as possible.
Our team is available Monday to Saturday, 09:00 – 16:30.

Your enquiry details:
-----------------------
Subject: $subject
Message: $message
-----------------------

If your matter is urgent, please call us directly on +44 7545 24711.

Kind regards,
The Soko Cleaning Maids Team
info@sokocleaningmaids.co.uk
+44 7545 24711
www.sokocleaningmaids.co.uk
";

    $auto_headers  = "From: info@sokocleaningmaids.co.uk\r\n";
    $auto_headers .= "Reply-To: info@sokocleaningmaids.co.uk\r\n";
    $auto_headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $auto_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    mail($email, $auto_reply_subject, $auto_reply_body, $auto_headers);
}

// ---------- REDIRECT WITH STATUS ----------
if ($mail_sent) {
    header("Location: " . $success_page . "?status=sent");
} else {
    header("Location: " . $error_page . "?status=error&msg=" . urlencode("Mail could not be sent. Please try again."));
}
exit;
?> -->
