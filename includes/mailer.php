<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Send email using PHPMailer with Gmail SMTP.
 * Returns true on success, false on failure.
 */
function sendMail($to, $subject, $htmlBody, $settings = []) {
    $siteName  = $settings['site_name'] ?? 'MakaanDekho';
    $fromEmail = $settings['smtp_user'] ?? $settings['email'] ?? 'noreply@makaandekho.in';

    // Full HTML email template
    $fullHtml = '<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
    <body style="margin:0;padding:0;background:#f0f2f5;font-family:Arial,Helvetica,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f2f5;padding:30px 0;">
    <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08);">
        <tr><td style="background:linear-gradient(135deg,#0f172a,#1e40af);padding:28px 36px;text-align:center;">
            <h1 style="margin:0;color:#fff;font-size:24px;font-weight:800;">' . htmlspecialchars($siteName) . '</h1>
            <p style="margin:6px 0 0;color:rgba(255,255,255,.6);font-size:12px;">Real Estate Platform</p>
        </td></tr>
        <tr><td style="padding:32px 36px;font-size:14px;color:#374151;line-height:1.7;">
            ' . $htmlBody . '
        </td></tr>
        <tr><td style="background:#f8fafc;padding:20px 36px;text-align:center;border-top:1px solid #e5e7eb;">
            <p style="margin:0;font-size:12px;color:#9ca3af;">&copy; ' . date('Y') . ' ' . htmlspecialchars($siteName) . '. All rights reserved.</p>
            <p style="margin:4px 0 0;font-size:11px;color:#9ca3af;">This is an automated email. Please do not reply.</p>
        </td></tr>
    </table>
    </td></tr></table>
    </body></html>';

    $mail = new PHPMailer(true);

    try {
        // SMTP credentials from Admin > Settings (no hardcoded creds)
        $smtpHost = $settings['smtp_host'] ?? '';
        $smtpUser = $settings['smtp_user'] ?? '';
        $smtpPass = $settings['smtp_pass'] ?? '';
        $smtpPort = (int)($settings['smtp_port'] ?? 587);

        if (!$smtpHost || !$smtpUser || !$smtpPass) {
            // Log and fail if SMTP not configured
            $logDir = dirname(__DIR__) . '/logs';
            if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
            @file_put_contents($logDir . '/email.log', date('Y-m-d H:i:s') . " | To: $to | Subject: $subject | Status: FAILED - SMTP not configured in Admin Settings\n", FILE_APPEND);
            return false;
        }

        // SMTP config
        $mail->isSMTP();
        $mail->Host       = $smtpHost;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUser;
        $mail->Password   = $smtpPass;
        // Port 465 = SMTPS (SSL), anything else = STARTTLS (587 etc.)
        $mail->SMTPSecure = ($smtpPort === 465)
            ? PHPMailer::ENCRYPTION_SMTPS
            : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $smtpPort;

        // From / To
        $mail->setFrom($smtpUser, $siteName);
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $fullHtml;
        $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>', '</div>', '</li>'], "\n", $htmlBody));
        $mail->CharSet = 'UTF-8';

        $mail->send();
        $sent = true;
    } catch (Exception $e) {
        $sent = false;
    }

    // Log email
    $logDir = dirname(__DIR__) . '/logs';
    if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
    $logEntry = date('Y-m-d H:i:s') . " | To: $to | Subject: $subject | Status: " . ($sent ? 'SENT' : 'FAILED: ' . ($mail->ErrorInfo ?? 'unknown')) . "\n";
    @file_put_contents($logDir . '/email.log', $logEntry, FILE_APPEND);

    return $sent;
}

/**
 * Send approval email with password set link
 */
function sendApprovalEmail($userEmail, $userName, $resetToken, $settings = []) {
    $siteName = $settings['site_name'] ?? 'MakaanDekho';

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $siteUrl  = $protocol . '://' . $host . '/makaandekho.in/';

    $setPasswordLink = $siteUrl . "forgot-password.php?token=$resetToken";

    $subject = "Account Approved - Welcome to $siteName!";

    $body = '
    <h2 style="color:#1e40af;margin:0 0 16px;font-size:20px;">🎉 Congratulations, ' . htmlspecialchars($userName) . '!</h2>
    <p>Your account on <strong>' . htmlspecialchars($siteName) . '</strong> has been <span style="color:#059669;font-weight:700;">approved</span> by our team.</p>

    <div style="background:#ecfdf5;border:1px solid #a7f3d0;border-radius:10px;padding:18px;margin:20px 0;">
        <p style="margin:0 0 6px;font-weight:700;color:#065f46;font-size:15px;">✅ Your Account is Active!</p>
        <p style="margin:0;font-size:13px;color:#047857;">You can now login and start posting properties.</p>
    </div>

    <p style="font-weight:600;">Click the button below to set your password and login:</p>

    <div style="text-align:center;margin:24px 0;">
        <a href="' . htmlspecialchars($setPasswordLink) . '"
           style="display:inline-block;background:linear-gradient(135deg,#1e40af,#1d4ed8);color:#ffffff;text-decoration:none;padding:14px 36px;border-radius:10px;font-weight:700;font-size:15px;letter-spacing:0.5px;">
            Set Password & Login →
        </a>
    </div>

    <p style="font-size:12px;color:#6b7280;margin-top:20px;">If the button doesn\'t work, copy and paste this link in your browser:</p>
    <p style="font-size:12px;color:#1e40af;word-break:break-all;">' . htmlspecialchars($setPasswordLink) . '</p>

    <div style="background:#fef3c7;border-radius:8px;padding:12px;margin-top:20px;font-size:12px;color:#92400e;">
        <strong>⏰ Note:</strong> This link is valid for <strong>48 hours</strong>. If it expires, use "Forgot Password" on the login page.
    </div>';

    return sendMail($userEmail, $subject, $body, $settings);
}

/**
 * Send registration confirmation email
 */
function sendRegistrationEmail($userEmail, $userName, $settings = []) {
    $siteName = $settings['site_name'] ?? 'MakaanDekho';

    $subject = "Registration Received - $siteName";

    $body = '
    <h2 style="color:#1e40af;margin:0 0 16px;font-size:20px;">Welcome, ' . htmlspecialchars($userName) . '!</h2>
    <p>Thank you for registering on <strong>' . htmlspecialchars($siteName) . '</strong>.</p>

    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:18px;margin:20px 0;">
        <p style="margin:0 0 6px;font-weight:700;color:#1e40af;font-size:15px;">⏳ Account Under Review</p>
        <p style="margin:0;font-size:13px;color:#1e3a5f;">Your account is pending admin approval. This usually takes 1-24 hours.</p>
    </div>

    <p>Once approved, you will receive an email with a link to set your password and start using the platform.</p>

    <p style="font-size:13px;color:#6b7280;">If you have any questions, contact us at <strong>' . htmlspecialchars($settings['email'] ?? 'info@makaandekho.in') . '</strong></p>';

    return sendMail($userEmail, $subject, $body, $settings);
}
