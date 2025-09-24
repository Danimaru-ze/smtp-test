# 📧 SMTP Tester

<div align="center">

**Alat testing email SMTP modern dengan UI yang menawan dan dukungan mode gelap/terang**

[🚀 Demo Langsung](#demo) • [📖 Dokumentasi](https://github.com/Danimaru-ze/smtp-test) • [🎨 Fitur]((https://github.com/Danimaru-ze/smtp-test)) • [⚡ Panduan Cepat]((https://github.com/Danimaru-ze/smtp-test))

</div>

---

## ✨ Fitur Utama

### 🎨 **UI/UX Modern**
- 🌟 **Desain Glassmorphism** - Efek kaca buram yang indah dengan backdrop blur
- 🎭 **Mode Gelap/Terang** - Deteksi tema otomatis dengan transisi yang halus
- 📱 **Fully Responsive** - Sempurna di desktop, tablet, dan ponsel
- 🎬 **Animasi Halus** - Micro-interactions dan transisi yang elegan
- 🎯 **Form** - Validasi real-time dengan feedback visual

### ⚡ **Fitur Cerdas**
- 🔄 **Konfigurasi Otomatis** - Pemilihan port cerdas berdasarkan jenis enkripsi
- 📧 **Validasi Email** - Validasi format email secara real-time
- 🎛️ **Multi Protocol** - Dukungan SMTP, Sendmail, dan PHP mail()
- 🔍 **Saran Cerdas** - Auto-complete untuk provider SMTP umum
- 📊 **Progress Live** - Progress pengiriman real-time dengan log detail

### ⌨️ **Shortcut Keyboard**
- `Ctrl + D` - Isi data demo
- `Ctrl + Enter` - Kirim email test
- `Ctrl + Shift + L` - Toggle tema

### 🛠️ **Fitur Developer**
- 📜 **Log Detail** - Informasi debugging SMTP yang komprehensif
- 💾 **Export Log** - Copy ke clipboard atau download sebagai file
- 🎯 **Error Handling** - Deteksi error pintar dan tips troubleshooting
- 🔧 **Mode Debug** - Metrik performa dan informasi sistem

---

## 🚀 Panduan Cepat

### Persyaratan
- PHP 7.4 atau lebih tinggi
- Composer (untuk PHPMailer)
- Web server (Apache/Nginx) atau PHP built-in server

### Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/yourusername/smtp-tester.git
   cd smtp-tester
   ```

2. **Install dependencies**
   ```bash
   composer require phpmailer/phpmailer
   ```

3. **Setup web server**
   ```bash
   # Menggunakan PHP built-in server
   php -S localhost:8000 -t public
   
   # Atau copy ke direktori web Anda
   cp -r . /var/www/html/smtp-tester
   ```

4. **Buka di browser**
   ```
   http://localhost:8000
   ```

---

## 📖 Panduan Penggunaan

### Konfigurasi Dasar

1. **Pilih Protocol**: Pilih SMTP untuk kompatibilitas terbaik
2. **Pengaturan SMTP**:
   - **Host**: Server SMTP Anda (misal: `smtp.gmail.com`)
   - **Port**: Biasanya 587 (TLS) atau 465 (SSL)
   - **Enkripsi**: TLS direkomendasikan untuk sebagian besar provider
   - **Username/Password**: Kredensial email Anda

3. **Detail Email**:
   - **From**: Alamat email pengirim
   - **To**: Email penerima - pisahkan multiple dengan koma
   - **Subject**: Judul email
   - **Body**: Konten HTML atau plain text

### Provider SMTP Populer

| Provider | Host | Port | Enkripsi | Catatan |
|----------|------|------|----------|---------|
| Gmail | `smtp.gmail.com` | 587 | TLS | Gunakan App Password |
| Outlook | `smtp-mail.outlook.com` | 587 | TLS | Hotmail/Live/Outlook |
| Yahoo | `smtp.mail.yahoo.com` | 587 | TLS | Yahoo Mail |
| Mailgun | `smtp.mailgun.org` | 587 | TLS | Service transactional |
| SendGrid | `smtp.sendgrid.net` | 587 | TLS | Service transactional |

### Setup Gmail

1. Aktifkan 2-Factor Authentication
2. Generate App Password:
   - Masuk ke pengaturan Google Account
   - Keamanan → App passwords
   - Generate password untuk "Mail"
   - Gunakan password ini di SMTP Tester

---

## 🏗️ Struktur Project

```
smtp-tester/
├── vendor/                 # Dependencies Composer (auto-generated)
├── composer.json           # Konfigurasi dependencies
├── src/
│   ├── Mailer.php         # Class utama untuk pengiriman email
│   └── helpers.php        # Fungsi helper dan utility
├── storage/
│   └── logs/              # Folder log (dibuat otomatis saat kirim)
├── public/
│   ├── index.php          # Interface utama aplikasi
│   ├── send.php           # Logic pengiriman email
│   └── assets/
│       ├── style.css      # Styles enhanced dengan dukungan tema
│       └── app.js         # JavaScript functionality
└── README.md              # File dokumentasi ini
```

---

## 🔧 Konfigurasi

### File Environment
Buat file `.env` untuk konfigurasi custom:

```env
# Pengaturan SMTP default
DEFAULT_SMTP_HOST=smtp.gmail.com
DEFAULT_SMTP_PORT=587
DEFAULT_ENCRYPTION=tls

# Pengaturan UI
DEFAULT_THEME=auto
ENABLE_DEBUG_MODE=false

# Pengaturan Log
LOG_RETENTION_DAYS=30
MAX_LOG_SIZE_MB=10
```

### Custom Themes
Extend CSS variables untuk membuat tema custom:

```css
[data-theme="custom"] {
  --bg-1: #your-bg-color;
  --text: #your-text-color;
  --accent: #your-accent-color;
  /* Tambahkan variabel custom lainnya */
}
```

---

## 🚀 Fitur Advanced

### Mode API
Kirim email secara programmatic via AJAX:

```javascript
const formData = new FormData();
formData.append('ajax', '1');
formData.append('smtp_host', 'smtp.gmail.com');
formData.append('smtp_port', '587');
formData.append('smtp_user', 'email@gmail.com');
// ... parameter lainnya

fetch('send.php', {
  method: 'POST',
  body: formData
})
.then(response => response.json())
.then(data => {
  console.log('Status:', data.status);
  console.log('Log:', data.log);
});
```

### Batch Testing
Test multiple konfigurasi SMTP:

```php
$configs = [
  [
    'host' => 'smtp.gmail.com', 
    'port' => 587,
    'user' => 'test@gmail.com'
  ],
  [
    'host' => 'smtp.mailgun.org', 
    'port' => 587,
    'user' => 'test@yourdomain.com'
  ],
  // ... konfigurasi lainnya
];

foreach ($configs as $config) {
  // Test setiap konfigurasi
  $result = testSMTPConfig($config);
  echo "Config {$config['host']}: " . ($result ? 'OK' : 'FAILED') . "\n";
}
```

---

## 🛠️ Troubleshooting

### Masalah Umum

**"SMTP connect() failed"**
- ✅ Periksa pengaturan host dan port
- ✅ Pastikan firewall/antivirus tidak memblokir
- ✅ Pastikan koneksi internet stabil
- ✅ Coba gunakan port alternatif (465 untuk SSL)

**"SMTP Error: Could not authenticate"**
- ✅ Verifikasi username/password
- ✅ Gunakan App Password untuk Gmail
- ✅ Periksa apakah akun tidak terkunci
- ✅ Pastikan "Less secure apps" diaktifkan (tidak direkomendasikan)

**"Could not instantiate mail function"**
- ✅ Install PHPMailer: `composer require phpmailer/phpmailer`
- ✅ Periksa konfigurasi PHP
- ✅ Verifikasi file permissions
- ✅ Pastikan direktori vendor/ ada

**"Permission denied untuk logs"**
- ✅ Buat folder storage/logs/ secara manual
- ✅ Set permission: `chmod 755 storage/logs/`
- ✅ Pastikan web server bisa menulis ke folder tersebut

### Mode Debug
Tambahkan `?debug=1` ke URL untuk informasi debugging detail.

Contoh: `http://localhost:8000/?debug=1`

---

## 🎨 Kustomisasi

### Mengubah Tema Default
Edit di `assets/style.css`:

```css
:root {
  /* Ubah warna default */
  --acc-1: #your-primary-color;
  --acc-2: #your-secondary-color;
  --acc-3: #your-tertiary-color;
}
```

### Menambah Provider SMTP
Edit di `assets/app.js` pada function `demoFill()`:

```javascript
const demos = {
  gmail: { /* konfigurasi gmail */ },
  outlook: { /* konfigurasi outlook */ },
  custom_provider: {
    protocol: 'smtp',
    smtp_host: 'mail.yourprovider.com',
    smtp_port: '587',
    smtp_crypto: 'tls',
    // ... konfigurasi lainnya
  }
};
```

### Custom Email Template
Edit default template di `index.php`:

```php
$defaultTemplate = '
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
  <h1>Email Custom Anda</h1>
  <p>Konten email template custom...</p>
</div>
';
```

---

## 🤝 Kontribusi

Kami menerima kontribusi! Silakan ikuti langkah berikut:

1. Fork repository ini
2. Buat feature branch: `git checkout -b fitur-baru`
3. Lakukan perubahan Anda
4. Tambahkan tests jika diperlukan
5. Commit: `git commit -am 'Tambah fitur baru'`
6. Push: `git push origin fitur-baru`
7. Buat Pull Request

### Setup Development

```bash
# Clone fork Anda
git clone https://github.com/yourusername/smtp-tester.git
cd smtp-tester

# Install dependencies
composer install

# Start development server
php -S localhost:8000 -t public

# Buka di browser
open http://localhost:8000
```

### Guidelines Kontribusi
- Gunakan PSR-4 untuk autoloading
- Ikuti PSR-12 coding standards
- Tulis komentar yang jelas dalam Bahasa Indonesia
- Test perubahan Anda di multiple PHP versions
- Update dokumentasi jika diperlukan

---

## 📝 Changelog

### Version 2.0
- ✨ Overhaul UI lengkap dengan desain glassmorphism
- 🌙 Mode gelap/terang dengan deteksi cerdas
- ⚡ Fungsionalitas JavaScript yang ditingkatkan
- 📱 Responsiveness mobile yang diperbaiki
- 🔧 Error handling dan validasi yang advanced
- ⌨️ Dukungan keyboard shortcuts
- 📊 Indikator progress real-time
- 🎨 Animasi dan transisi yang halus
- 📜 Sistem log yang diperbaiki
- 🛠️ Mode debug dengan informasi performa

### Version 1.0 - Initial Release
- 📧 Fungsionalitas testing SMTP dasar
- 🔧 Dukungan multiple protocol
- 📜 Sistem logging sederhana
- 🎨 UI dasar dengan Bootstrap

---

## 🔒 Keamanan

### Best Practices
- ❌ Jangan pernah commit kredensial ke version control
- ✅ Gunakan environment variables untuk data sensitif
- ✅ Implementasikan rate limiting untuk production
- ✅ Update keamanan secara berkala
- ✅ Gunakan HTTPS di production

### Melaporkan Masalah Keamanan
Laporkan kerentanan keamanan ke: security@danysetiyawan.com

### Fitur Keamanan
- Input sanitization dan validation
- CSRF protection (implementasi manual)
- Rate limiting untuk mencegah spam
- Log audit untuk tracking aktivitas

---

## 📄 Lisensi

MIT License

Copyright (c) 2025 **Dany Setiyawan**

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

---

## 🙏 Acknowledgments

- [PHPMailer](https://github.com/PHPMailer/PHPMailer) - Library email PHP terbaik
- [Inter Font](https://rsms.me/inter/) - Typography yang indah
- [Google Fonts](https://fonts.google.com/) - Font web gratis
- Komunitas developer Indonesia yang luar biasa
- Beta testers dan kontributor

---

## 📞 Dukungan

- 🐛 **Bug Reports**: [GitHub Issues](https://github.com/yourusername/smtp-tester/issues)
- 💡 **Feature Requests**: [GitHub Discussions](https://github.com/yourusername/smtp-tester/discussions)
- 📧 **Email**: support@danysetiyawan.com
- 📖 **Dokumentasi**: [Wiki](https://github.com/yourusername/smtp-tester/wiki)
- 💬 **Community**: [Telegram Group](https://t.me/smtp-tester-id)

### FAQ

**Q: Apakah bisa digunakan untuk production?**
A: Ya, tapi tambahkan fitur keamanan seperti authentication dan rate limiting.

**Q: Apakah mendukung attachment?**
A: Saat ini belum, tapi bisa ditambahkan di versi selanjutnya.

**Q: Apakah gratis?**
A: Ya, 100% gratis dan open source dengan lisensi MIT.

**Q: Minimum requirement PHP?**
A: PHP 7.4+, tapi direkomendasikan PHP 8.0+ untuk performa optimal.

---

## 🎯 Roadmap

### Version 2.1 (Q2 2025)
- 📎 Dukungan attachment files
- 🔐 Authentication system
- 📊 Dashboard analytics
- 🌍 Multi-language support

### Version 2.2 (Q3 2025)
- 📧 Email templates library
- 🔄 Scheduled emails
- 📈 Performance monitoring
- 🎨 Theme marketplace

### Version 3.0 (Q4 2025)
- 🚀 API REST lengkap
- 📱 Mobile app companion
- ☁️ Cloud integration
- 🤖 AI-powered suggestions

---

<div align="center">

**⭐ Star repository ini jika bermanfaat!**

Dibuat dengan ❤️ oleh [Dany Setiyawan](https://danimaru.site)

[⬆️ Kembali ke atas](#-smtp-tester)

</div>
