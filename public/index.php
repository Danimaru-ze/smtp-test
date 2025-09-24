<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../src/helpers.php';

$hasComposer = file_exists(__DIR__ . '/../vendor/autoload.php');
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>SMTP Tester</title>
  <meta name="description" content="Modern SMTP email testing tool with beautiful UI and dark/light mode support">
  <meta name="theme-color" content="#22d3ee">
  
  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📧</text></svg>">
  
  <!-- Preload critical resources -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  
  <!-- Stylesheets -->
  <link rel="stylesheet" href="assets/style.css">
  
  <!-- Performance optimizations -->
  <meta name="robots" content="noindex, nofollow">
</head>
<body>
<div class="wrap">
  <div class="title">
    <div class="title-left">
      <div class="logo loading">📧</div>
      <div>
        <h1>SMTP Tester</h1>
        <div class="small">Uji kirim email via SMTP / Sendmail / mail() dengan tampilan modern</div>
      </div>
    </div>
    <div class="theme-toggle" id="themeToggle" title="Toggle Dark/Light Mode (Ctrl+Shift+L)">
      <span class="theme-icon sun">☀️</span>
      <span class="theme-icon moon">🌙</span>
    </div>
  </div>

  <div class="card">
    <div class="header">
      <div class="inline">
        <span class="badge">✨ Enhanced Edition</span>
        <span class="small">
          <?= $hasComposer 
            ? '<span style="color: var(--success)">✅ PHPMailer siap digunakan</span>' 
            : '<span style="color: var(--warning)">⚠️ PHPMailer belum terpasang. Jalankan: <code style="background: var(--bg-2); padding: 2px 6px; border-radius: 4px; font-size: 11px;">composer require phpmailer/phpmailer</code></span>' 
          ?>
        </span>
      </div>
    </div>

    <form id="smtpForm" method="post" action="send.php" autocomplete="on" novalidate>
      <div class="grid g-3">
        <div class="form-group">
          <label for="protocol">🔧 Protocol</label>
          <select id="protocol" name="protocol">
            <?php
              $protocols = [
                'smtp' => '📡 SMTP (disarankan)',
                'sendmail' => '📮 Sendmail',
                'mail' => '📬 PHP mail()'
              ];
              $pold = oldf('protocol','smtp');
              foreach ($protocols as $val => $label) {
                $sel = $pold === $val ? 'selected' : '';
                echo "<option value=\"$val\" $sel>$label</option>";
              }
            ?>
          </select>
          <div class="hint">Untuk pengujian yang akurat, gunakan SMTP.</div>
        </div>

        <div class="form-group">
          <label for="smtp_host">🌐 SMTP Host</label>
          <input id="smtp_host" name="smtp_host" placeholder="smtp.gmail.com / smtp.mailgun.org" value="<?= oldf('smtp_host') ?>" autocomplete="off">
        </div>

        <div class="form-group">
          <label for="smtp_port">🔌 SMTP Port</label>
          <input id="smtp_port" name="smtp_port" type="number" placeholder="587 / 465" value="<?= oldf('smtp_port','587') ?>" min="1" max="65535" autocomplete="off">
        </div>

        <div class="form-group">
          <label for="smtp_crypto">🔐 SMTP Crypto</label>
          <select id="smtp_crypto" name="smtp_crypto">
            <?php
              $cryptos = [
                'tls' => '🔒 TLS (Port 587)',
                'ssl' => '🛡️ SSL (Port 465)',
                'none' => '❌ Tidak ada enkripsi'
              ];
              $cold = oldf('smtp_crypto','tls');
              foreach ($cryptos as $val => $label) {
                $sel = $cold === $val ? 'selected' : '';
                echo "<option value=\"$val\" $sel>$label</option>";
              }
            ?>
          </select>
        </div>

        <div class="form-group">
          <label for="smtp_user">👤 SMTP User</label>
          <input id="smtp_user" name="smtp_user" placeholder="email@domain.com / username" value="<?= oldf('smtp_user') ?>" autocomplete="email">
        </div>

        <div class="form-group">
          <label for="smtp_pass">🔑 SMTP Password</label>
          <input id="smtp_pass" name="smtp_pass" type="password" placeholder="Password / App Password" value="<?= oldf('smtp_pass') ?>" autocomplete="current-password">
          <div class="hint">Untuk Gmail, gunakan App Password bukan password akun utama.</div>
        </div>
      </div>

      <hr class="divider" />

      <div class="grid g-2">
        <div class="form-group">
          <label for="from_email">📧 From Email</label>
          <input id="from_email" name="from_email" type="email" placeholder="no-reply@domain.com" value="<?= oldf('from_email') ?>" autocomplete="email">
        </div>
        <div class="form-group">
          <label for="from_name">👨‍💼 From Name</label>
          <input id="from_name" name="from_name" placeholder="Nama Pengirim" value="<?= oldf('from_name') ?>" autocomplete="name">
        </div>
        <div class="form-group">
          <label for="to">📬 Kepada (To)</label>
          <input id="to" name="to" placeholder="penerima@domain.com, another@domain.com" value="<?= oldf('to') ?>" autocomplete="email">
          <div class="hint">Pisahkan beberapa email dengan koma atau baris baru.</div>
        </div>
        <div class="form-group">
          <label for="reply_to">↩️ Reply-To (opsional)</label>
          <input id="reply_to" name="reply_to" type="email" placeholder="balas-ke@domain.com" value="<?= oldf('reply_to') ?>" autocomplete="email">
        </div>
      </div>

      <div class="grid g-3" style="margin-top:8px">
        <div class="form-group">
          <label for="mailtype">📄 Mail Type</label>
          <select id="mailtype" name="mailtype">
            <?php
              $types = [
                'html' => '🌐 HTML (Rich Text)',
                'text' => '📝 Plain Text'
              ];
              $told = oldf('mailtype','html');
              foreach ($types as $val => $label) {
                $sel = $told === $val ? 'selected' : '';
                echo "<option value=\"$val\" $sel>$label</option>";
              }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="charset">🔤 Charset</label>
          <input id="charset" name="charset" placeholder="UTF-8" value="<?= oldf('charset','UTF-8') ?>">
        </div>
        <div class="form-group">
          <label for="wordwrap">📏 Word Wrap</label>
          <input id="wordwrap" name="wordwrap" type="number" placeholder="78" value="<?= oldf('wordwrap','78') ?>" min="0" max="200">
        </div>
      </div>

      <div class="grid g-2" style="margin-top:8px">
        <div class="form-group">
          <label for="msg_from">🏷️ Message From (opsional)</label>
          <input id="msg_from" name="msg_from" placeholder="Keterangan pengirim (untuk log/body)" value="<?= oldf('msg_from') ?>">
          <div class="hint">Digunakan untuk identifikasi di log.</div>
        </div>
        <div class="form-group">
          <label for="msg_to">🎯 Message To (opsional)</label>
          <input id="msg_to" name="msg_to" placeholder="Keterangan penerima (untuk log/body)" value="<?= oldf('msg_to') ?>">
          <div class="hint">Digunakan untuk identifikasi di log.</div>
        </div>
      </div>

      <div class="form-group" style="margin-top:8px">
        <label for="subject">📋 Subject</label>
        <input id="subject" name="subject" placeholder="Judul email yang menarik" value="<?= oldf('subject','🧪 Tes SMTP – PHPMailer') ?>">
      </div>

      <div class="form-group" style="margin-top:8px">
        <label for="body">✍️ Body</label>
        <textarea id="body" name="body" placeholder="Tulis isi pesan HTML atau text sesuai mail type yang dipilih" rows="8"><?= oldf('body', '<div style="font-family: Inter, Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(135deg, #f8fafc, #e2e8f0); border-radius: 12px;">
  <h2 style="color: #1e293b; margin-bottom: 16px;">🎉 Halo dari SMTP Tester!</h2>
  <p style="color: #475569; line-height: 1.6; margin-bottom: 16px;">Ini adalah email uji kirim yang digenerate dari <strong style="color: #0ea5e9;">SMTP Tester</strong> dengan tampilan yang lebih modern dan menarik.</p>
  
  <div style="background: white; padding: 16px; border-radius: 8px; border-left: 4px solid #22d3ee; margin: 20px 0;">
    <p style="margin: 0; color: #64748b; font-size: 14px;">💡 <strong>Tips:</strong> Jika Anda menerima email ini, berarti konfigurasi SMTP Anda sudah bekerja dengan sempurna!</p>
  </div>
  
  <div style="text-align: center; margin: 24px 0;">
    <div style="display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #22d3ee, #60a5fa); color: white; border-radius: 8px; font-weight: 600;">
      ✅ Email Terkirim Sukses!
    </div>
  </div>
  
  <p style="color: #64748b; font-size: 12px; text-align: center; margin-top: 24px;">Dibuat dengan ❤️ oleh Dany Setiyawan<br>
  <small>' . date('d M Y H:i') . ' WIB</small></p>
</div>') ?></textarea>
        <div class="hint">Gunakan HTML untuk tampilan yang lebih menarik, atau plain text untuk kesederhanaan.</div>
      </div>

      <div class="actions">
        <button type="button" class="btn secondary" onclick="demoFill()">🎯 Isi Contoh Data</button>
        <button id="submitBtn" type="submit" class="btn">🚀 Kirim Uji Email</button>
      </div>

      <div class="status">
        <span id="statusDot" class="dot idle"></span>
        <div id="statusText"><strong>Status:</strong> <span class="small">Menunggu pengujian email</span></div>
      </div>

      <div class="log">
        <div class="inline space-between">
          <div class="small">📜 Log Output</div>
          <div class="inline">
            <button type="button" class="btn secondary" onclick="copyLog()" title="Copy log ke clipboard (Ctrl+C)">📋 Copy Log</button>
            <button type="button" class="btn secondary" onclick="saveLog()" title="Download log sebagai file txt">💾 Download Log</button>
          </div>
        </div>
        <pre id="log">🏁 Log akan muncul di sini setelah pengujian email dilakukan.

💡 Tips penggunaan:
• Isi semua field yang diperlukan
• Untuk Gmail, gunakan App Password (bukan password biasa)
• Port 587 untuk TLS, Port 465 untuk SSL  
• Pastikan "Less secure app access" diaktifkan jika menggunakan password biasa
• Tekan Ctrl+D untuk mengisi data contoh
• Tekan Ctrl+Enter untuk langsung kirim

🔧 Troubleshooting umum:
• "SMTP connect() failed" → Periksa host dan port
• "SMTP AUTH failed" → Periksa username/password
• "Could not instantiate mail function" → Periksa konfigurasi server</pre>
      </div>
    </form>
  </div>

  <!-- Enhanced Footer -->
  <div class="note">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
      <div>
        <strong>💡 Tips Professional:</strong> 
        <span>Untuk Gmail gunakan App Password + TLS (587). Untuk production gunakan layanan seperti Mailgun, SendGrid, atau Amazon SES.</span>
      </div>
      <div class="inline" style="font-size: 11px; opacity: 0.7;">
        <span>⌨️ Ctrl+D: Demo</span>
        <span>⌨️ Ctrl+Enter: Kirim</span>
        <span>⌨️ Ctrl+Shift+L: Theme</span>
      </div>
    </div>
  </div>

  <!-- Copyright Footer -->
  <div style="text-align: center; margin-top: 32px; padding: 20px 0; border-top: 1px solid var(--border); background: linear-gradient(135deg, rgba(255,255,255,0.02), transparent);">
    <div style="color: var(--muted); font-size: 13px; margin-bottom: 8px;">
      <strong style="background: linear-gradient(135deg, var(--acc-1), var(--acc-2)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">SMTP Tester</strong>
    </div>
    <div style="color: var(--muted); font-size: 11px; opacity: 0.8;">
      © 2025 <strong>Dany Setiyawan</strong>. All Rights Reserved.
    </div>
    <div style="margin-top: 8px; font-size: 10px; opacity: 0.6;">
      <span style="margin: 0 8px;">Made with ❤️</span>
      <span style="margin: 0 8px;">•</span>
      <span style="margin: 0 8px;">Version 2.0</span>
    </div>
  </div>

  <!-- Performance info (hidden in production) -->
  <?php if (isset($_GET['debug'])): ?>
  <div style="margin-top: 20px; padding: 12px; background: var(--field-bg); border: 1px solid var(--border); border-radius: 8px; font-size: 11px; color: var(--muted);">
    <strong>🔧 Debug Info:</strong>
    PHP <?= PHP_VERSION ?> | 
    Memory: <?= round(memory_get_usage(true)/1024/1024, 2) ?>MB |
    Composer: <?= $hasComposer ? '✅' : '❌' ?> |
    Time: <?= date('Y-m-d H:i:s') ?>
  </div>
  <?php endif; ?>
</div>

<!-- Enhanced JavaScript -->
<script src="assets/app.js"></script>

<!-- Analytics placeholder (replace with your tracking if needed) -->
<!-- 
<script>
  // Google Analytics, etc.
</script>
-->

<!-- Service Worker for offline capability (optional) -->
<script>
  // Register service worker for enhanced performance
  if ('serviceWorker' in navigator && window.location.protocol === 'https:') {
    navigator.serviceWorker.register('/sw.js').catch(() => {
      // Service worker registration failed, no problem
    });
  }
</script>

</body>
</html>