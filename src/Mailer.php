<?php
declare(strict_types=1);

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public string $debugLog = '';

    /**
     * Kirim email.
     * @param array $p Data dari form
     * @return array{ok:bool,status:string,log:string}
     */
    public function send(array $p): array
    {
        $protocol  = trim($p['protocol']   ?? 'smtp');  // smtp | sendmail | mail
        $host      = trim($p['smtp_host']  ?? '');
        $user      = trim($p['smtp_user']  ?? '');
        $pass      = trim($p['smtp_pass']  ?? '');
        $port      = (int)($p['smtp_port'] ?? 587);
        $crypto    = trim($p['smtp_crypto']?? 'tls');   // none | ssl | tls
        $fromEmail = trim($p['from_email'] ?? '');
        $fromName  = trim($p['from_name']  ?? 'SMTP Tester');
        $mailtype  = trim($p['mailtype']   ?? 'html');  // html | text
        $charset   = trim($p['charset']    ?? 'UTF-8');
        $wordwrap  = (int)($p['wordwrap']  ?? 78);
        $msgFrom   = trim($p['msg_from']   ?? '');
        $msgTo     = trim($p['msg_to']     ?? '');
        $to        = trim($p['to']         ?? '');
        $subject   = trim($p['subject']    ?? 'Tes SMTP – PHPMailer');
        $body      = $p['body'] ?? '';
        $replyTo   = trim($p['reply_to']   ?? '');

        $mail = new PHPMailer(true);

        // Tangkap log SMTP
        $mail->SMTPDebug  = 2; // 0=off, 1=client, 2=client+server
        $mail->Debugoutput = function($str, $level) {
            $this->debugLog .= '[' . date('Y-m-d H:i:s') . "][$level] $str\n";
        };

        // Transport
        if ($protocol === 'smtp') {
            $mail->isSMTP();
            $mail->Host       = $host;
            $mail->Port       = $port;
            $mail->SMTPAuth   = ($user !== '' || $pass !== '');
            if ($mail->SMTPAuth) {
                $mail->Username = $user;
                $mail->Password = $pass;
            }
            if ($crypto === 'ssl' || $crypto === 'tls') {
                $mail->SMTPSecure = $crypto;
            } else {
                $mail->SMTPSecure = false; // plain
            }
        } elseif ($protocol === 'sendmail') {
            $mail->isSendmail();
        } else {
            $mail->isMail();
        }

        $mail->CharSet = $charset !== '' ? $charset : 'UTF-8';
        $mail->setFrom($fromEmail !== '' ? $fromEmail : 'no-reply@example.com', $fromName);
        if ($replyTo !== '') {
            $mail->addReplyTo($replyTo);
        }

        // Penerima (comma / newline / semicolon)
        $toList = preg_split('/[,;\r\n]+/', $to);
        foreach ($toList as $addr) {
            $addr = trim($addr);
            if ($addr !== '') {
                $mail->addAddress($addr);
            }
        }

        // Header ekstra
        if ($msgFrom !== '') $mail->addCustomHeader('X-Message-From', $msgFrom);
        if ($msgTo   !== '') $mail->addCustomHeader('X-Message-To', $msgTo);

        $mail->Subject = $subject;
        if ($wordwrap > 0) $mail->WordWrap = $wordwrap;

        if ($mailtype === 'html') {
            $prefixInfo = '';
            if ($msgFrom || $msgTo) {
                $prefixInfo = "<p style=\"margin:0 0 12px 0;opacity:.8;\"><strong>Message From:</strong> "
                    . htmlspecialchars($msgFrom, ENT_QUOTES, 'UTF-8')
                    . " &nbsp; | &nbsp; <strong>Message To:</strong> "
                    . htmlspecialchars($msgTo, ENT_QUOTES, 'UTF-8') . "</p><hr />";
            }
            $mail->isHTML(true);
            $mail->Body    = $prefixInfo . $body;
            $mail->AltBody = strip_tags(($msgFrom||$msgTo) ? "Message From: $msgFrom | Message To: $msgTo\n\n" : '') . strip_tags($body);
        } else {
            $prefixInfo = ($msgFrom||$msgTo) ? "Message From: $msgFrom | Message To: $msgTo\n----------------------------------------\n" : '';
            $mail->isHTML(false);
            $mail->Body = $prefixInfo . $body;
        }

        try {
            $ok = $mail->send();
            $status = $ok ? 'success' : 'fail';
        } catch (Exception $e) {
            $ok = false;
            $status = 'fail';
            $this->debugLog .= "\n[EXCEPTION] " . $e->getMessage();
        }

        // Simpan log ke file
        $this->saveLog($this->debugLog);

        return ['ok'=>$ok, 'status'=>$status, 'log'=>$this->debugLog];
    }

    private function saveLog(string $content): void
    {
        $dir = dirname(__DIR__) . '/storage/logs';
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        $file = $dir . '/smtp-' . date('Ymd-His') . '.log';
        @file_put_contents($file, $content);
    }
}
