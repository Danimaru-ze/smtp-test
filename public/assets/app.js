// Enhanced SMTP Tester JavaScript with Theme Toggle
(() => {
  "use strict";

  // ===== Theme Management =====
  function initTheme() {
    // Check for saved theme preference or default to light
    const savedTheme = localStorage.getItem('smtp-tester-theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = savedTheme || (prefersDark ? 'dark' : 'light');
    
    setTheme(theme);
    
    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
      if (!localStorage.getItem('smtp-tester-theme')) {
        setTheme(e.matches ? 'dark' : 'light');
      }
    });
  }

  function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('smtp-tester-theme', theme);
    
    // Update theme toggle button
    const toggle = document.getElementById('themeToggle');
    if (toggle) {
      toggle.setAttribute('data-theme', theme);
    }
    
    // Animate theme transition
    document.body.style.transition = 'all 0.3s ease';
    setTimeout(() => {
      document.body.style.transition = '';
    }, 300);
  }

  function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    setTheme(newTheme);
    
    // Add a nice ripple effect
    const toggle = document.getElementById('themeToggle');
    if (toggle) {
      toggle.style.transform = 'scale(0.95)';
      setTimeout(() => {
        toggle.style.transform = '';
      }, 150);
    }
  }

  // ===== Enhanced Demo Fill =====
  function demoFill() {
    const demos = {
      gmail: {
        protocol: 'smtp',
        smtp_host: 'smtp.gmail.com',
        smtp_port: '587',
        smtp_crypto: 'tls',
        smtp_user: 'your@gmail.com',
        smtp_pass: '',
        from_email: 'your@gmail.com',
        from_name: 'SMTP Tester',
        to: 'recipient@example.com',
        reply_to: '',
        subject: 'Tes SMTP – PHPMailer ✨',
        mailtype: 'html',
        charset: 'UTF-8',
        wordwrap: '78',
        msg_from: 'QA Bot',
        msg_to: 'Admin',
        body: '<div style="font-family: Inter, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(135deg, #f8fafc, #e2e8f0); border-radius: 12px;"><h2 style="color: #1e293b; margin-bottom: 16px;">🎉 Halo dari SMTP Tester!</h2><p style="color: #475569; line-height: 1.6; margin-bottom: 16px;">Ini adalah email uji kirim yang digenerate dari <strong style="color: #0ea5e9;">SMTP Tester</strong> dengan tampilan yang lebih modern dan menarik.</p><div style="background: white; padding: 16px; border-radius: 8px; border-left: 4px solid #22d3ee; margin: 20px 0;"><p style="margin: 0; color: #64748b; font-size: 14px;">💡 <strong>Tips:</strong> Jika Anda menerima email ini, berarti konfigurasi SMTP Anda sudah bekerja dengan sempurna!</p></div><p style="color: #64748b; font-size: 12px; text-align: center; margin-top: 24px;">Dibuat dengan ❤️ oleh SMTP Tester</p></div>'
      },
      outlook: {
        protocol: 'smtp',
        smtp_host: 'smtp-mail.outlook.com',
        smtp_port: '587',
        smtp_crypto: 'tls',
        smtp_user: 'your@outlook.com',
        smtp_pass: '',
        from_email: 'your@outlook.com',
        from_name: 'SMTP Tester',
        to: 'recipient@example.com'
      },
      yahoo: {
        protocol: 'smtp',
        smtp_host: 'smtp.mail.yahoo.com',
        smtp_port: '587',
        smtp_crypto: 'tls',
        smtp_user: 'your@yahoo.com',
        smtp_pass: '',
        from_email: 'your@yahoo.com',
        from_name: 'SMTP Tester',
        to: 'recipient@example.com'
      }
    };

    const demoTypes = Object.keys(demos);
    const randomDemo = demoTypes[Math.floor(Math.random() * demoTypes.length)];
    const demo = { ...demos.gmail, ...demos[randomDemo] }; // Gmail as base, override with selected

    const set = (id, val) => { 
      const el = document.getElementById(id); 
      if (el && val !== undefined) {
        el.value = val;
        // Add a subtle animation
        el.style.transform = 'scale(0.98)';
        setTimeout(() => {
          el.style.transform = '';
        }, 100);
      }
    };

    // Fill all fields
    Object.entries(demo).forEach(([key, value]) => {
      set(key, value);
    });

    // Show success notification
    showNotification('Data contoh berhasil diisi! 🎯', 'success');
  }

  // ===== Enhanced Copy & Download log =====
  function copyLog() {
    const pre = document.getElementById('log');
    const text = pre ? pre.innerText : '';
    
    if (!text || text === 'Log akan muncul setelah pengujian.') { 
      showNotification('Belum ada log untuk disalin 📝', 'warning'); 
      return; 
    }

    navigator.clipboard.writeText(text).then(() => {
      showNotification('Log berhasil disalin ke clipboard! 📋', 'success');
    }).catch(() => {
      // Fallback for older browsers
      try {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showNotification('Log berhasil disalin! 📋', 'success');
      } catch (err) {
        showNotification('Gagal menyalin log. Coba copy manual 😅', 'error');
      }
    });
  }

  function saveLog() {
    const pre = document.getElementById('log');
    const text = pre ? pre.innerText : '';
    
    if (!text || text === 'Log akan muncul setelah pengujian.') {
      showNotification('Belum ada log untuk disimpan 📄', 'warning');
      return;
    }

    try {
      const timestamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, -5);
      const filename = `smtp-tester-log-${timestamp}.txt`;
      
      const blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      
      a.href = url;
      a.download = filename;
      a.style.display = 'none';
      
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      
      URL.revokeObjectURL(url);
      
      showNotification(`Log disimpan sebagai ${filename} 💾`, 'success');
    } catch (error) {
      showNotification('Gagal menyimpan log 😞', 'error');
    }
  }

  // ===== Notification System =====
  function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existing = document.querySelector('.notification');
    if (existing) {
      existing.remove();
    }

    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
      <div class="notification-content">
        <span class="notification-message">${message}</span>
        <button class="notification-close">&times;</button>
      </div>
    `;

    // Add styles
    const styles = `
      .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        background: var(--card);
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 16px 20px;
        box-shadow: var(--shadow);
        backdrop-filter: blur(16px);
        transform: translateX(400px);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        max-width: 350px;
        font-family: Inter, sans-serif;
      }
      .notification.show {
        transform: translateX(0);
      }
      .notification-content {
        display: flex;
        align-items: center;
        gap: 12px;
      }
      .notification-message {
        color: var(--text);
        font-size: 14px;
        font-weight: 500;
        flex: 1;
      }
      .notification-close {
        background: none;
        border: none;
        color: var(--muted);
        font-size: 18px;
        cursor: pointer;
        padding: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s ease;
      }
      .notification-close:hover {
        background: var(--border);
        color: var(--text);
      }
      .notification-success {
        border-color: var(--success);
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), var(--card));
      }
      .notification-error {
        border-color: var(--danger);
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), var(--card));
      }
      .notification-warning {
        border-color: var(--warning);
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), var(--card));
      }
    `;

    // Add styles to head if not exists
    if (!document.querySelector('#notification-styles')) {
      const styleSheet = document.createElement('style');
      styleSheet.id = 'notification-styles';
      styleSheet.textContent = styles;
      document.head.appendChild(styleSheet);
    }

    document.body.appendChild(notification);

    // Show notification
    requestAnimationFrame(() => {
      notification.classList.add('show');
    });

    // Auto hide after 4 seconds
    const autoHide = setTimeout(() => {
      hideNotification(notification);
    }, 4000);

    // Close button functionality
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
      clearTimeout(autoHide);
      hideNotification(notification);
    });

    // Hide on click outside
    setTimeout(() => {
      const clickHandler = (e) => {
        if (!notification.contains(e.target)) {
          clearTimeout(autoHide);
          hideNotification(notification);
          document.removeEventListener('click', clickHandler);
        }
      };
      document.addEventListener('click', clickHandler);
    }, 100);
  }

  function hideNotification(notification) {
    notification.classList.remove('show');
    setTimeout(() => {
      if (notification.parentNode) {
        notification.parentNode.removeChild(notification);
      }
    }, 300);
  }

  // ===== Enhanced Form Validation =====
  function validateForm() {
    const requiredFields = [
      { id: 'smtp_host', name: 'SMTP Host' },
      { id: 'smtp_user', name: 'SMTP User' },
      { id: 'smtp_pass', name: 'SMTP Password' },
      { id: 'from_email', name: 'From Email' },
      { id: 'to', name: 'To Email' },
      { id: 'subject', name: 'Subject' }
    ];

    const errors = [];
    
    requiredFields.forEach(field => {
      const element = document.getElementById(field.id);
      if (element && !element.value.trim()) {
        errors.push(field.name);
        element.style.borderColor = 'var(--danger)';
        element.style.boxShadow = '0 0 0 4px rgba(239, 68, 68, 0.1)';
      } else if (element) {
        element.style.borderColor = '';
        element.style.boxShadow = '';
      }
    });

    // Email validation
    const emailFields = ['from_email', 'to', 'reply_to'];
    emailFields.forEach(fieldId => {
      const element = document.getElementById(fieldId);
      if (element && element.value.trim()) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const emails = element.value.split(',').map(e => e.trim());
        
        if (fieldId === 'to') {
          // Multiple emails allowed for 'to' field
          const invalidEmails = emails.filter(email => email && !emailRegex.test(email));
          if (invalidEmails.length > 0) {
            errors.push(`Format email tidak valid di field ${fieldId === 'to' ? 'Kepada' : 'Reply-To'}: ${invalidEmails.join(', ')}`);
            element.style.borderColor = 'var(--danger)';
          }
        } else {
          // Single email for other fields
          if (!emailRegex.test(element.value.trim())) {
            errors.push(`Format email tidak valid di field ${fieldId === 'from_email' ? 'From Email' : 'Reply-To'}`);
            element.style.borderColor = 'var(--danger)';
          }
        }
      }
    });

    return errors;
  }

  // ===== Enhanced Status Management =====
  function setStatus(state, text, details = '') {
    const statusDot = document.getElementById('statusDot');
    const statusText = document.getElementById('statusText');
    const statusRow = document.querySelector('.status');
    
    if (!statusDot || !statusText) return;

    // Remove all status classes
    statusDot.classList.remove('idle', 'success', 'fail');
    statusRow.classList.remove('sending');
    
    // Add new status
    statusDot.classList.add(state);
    statusText.innerHTML = `<strong>Status:</strong> ${text}${details ? `<br><small style="opacity: 0.7;">${details}</small>` : ''}`;

    // Add sending class if needed
    if (state === 'sending') {
      statusRow.classList.add('sending');
    }
  }

  // ===== Enhanced Form Submission =====
  function initFormSubmission() {
    const form = document.getElementById('smtpForm');
    const submitBtn = document.getElementById('submitBtn');
    const logPre = document.getElementById('log');

    if (!form || !submitBtn || !logPre) return;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      // Validate form
      const errors = validateForm();
      if (errors.length > 0) {
        showNotification(`Ada ${errors.length} error yang perlu diperbaiki`, 'error');
        setStatus('fail', 'Validasi gagal', errors.slice(0, 2).join('<br>'));
        return;
      }

      // Start sending
      setStatus('sending', 'Mengirim email...', 'Mohon tunggu sebentar');
      submitBtn.disabled = true;
      submitBtn.textContent = '🔄 Mengirim...';
      logPre.textContent = '📤 Memproses dan mengirim email...\n⏳ Mengumpulkan log SMTP...';

      const startTime = Date.now();

      try {
        const fd = new FormData(form);
        fd.append('ajax', '1');

        // Show progress
        let progress = 0;
        const progressInterval = setInterval(() => {
          progress += Math.random() * 15;
          if (progress < 90) {
            const dots = '.'.repeat((Math.floor(progress / 20) % 4) + 1);
            logPre.textContent = `📤 Memproses dan mengirim email${dots}\n⏳ Progress: ${Math.floor(progress)}%`;
          }
        }, 200);

        const response = await fetch(form.action, { 
          method: 'POST', 
          body: fd,
          timeout: 30000 // 30 second timeout
        });

        clearInterval(progressInterval);

        const duration = ((Date.now() - startTime) / 1000).toFixed(1);

        if (!response.ok) {
          const text = await response.text().catch(() => '');
          throw new Error(`HTTP ${response.status}${text ? ': ' + text.slice(0, 200) : ''}`);
        }

        const data = await response.json();
        const isSuccess = data && data.status === 'success';
        
        setStatus(
          isSuccess ? 'success' : 'fail', 
          isSuccess ? 'Email terkirim! 🎉' : 'Pengiriman gagal 😞',
          `Durasi: ${duration}s`
        );
        
        logPre.textContent = (data && data.log) ? data.log : 'Tidak ada log yang tersedia.';

        // Show notification
        showNotification(
          isSuccess 
            ? `Email berhasil dikirim dalam ${duration} detik! 🚀`
            : 'Pengiriman email gagal. Periksa konfigurasi SMTP.',
          isSuccess ? 'success' : 'error'
        );

        // Auto-scroll to log if failed
        if (!isSuccess) {
          logPre.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

      } catch (error) {
        clearInterval(progressInterval);
        const duration = ((Date.now() - startTime) / 1000).toFixed(1);
        
        setStatus('fail', 'Terjadi kesalahan', `Timeout: ${duration}s`);
        logPre.textContent = `❌ Kesalahan saat mengirim request\n\n` +
                             `⏱️  Durasi: ${duration} detik\n` +
                             `🔍 Detail: ${error && error.message ? error.message : error}\n\n` +
                             `💡 Kemungkinan penyebab:\n` +
                             `   • Koneksi internet bermasalah\n` +
                             `   • Server SMTP tidak merespons\n` +
                             `   • Konfigurasi SMTP salah\n` +
                             `   • Firewall memblokir koneksi`;

        showNotification('Terjadi kesalahan saat mengirim email 💥', 'error');
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = '🚀 Kirim Uji Email';
      }
    });
  }

  // ===== Smart Form Features =====
  function initSmartFeatures() {
    // Auto-fill port based on crypto selection
    const cryptoSelect = document.getElementById('smtp_crypto');
    const portInput = document.getElementById('smtp_port');
    
    if (cryptoSelect && portInput) {
      cryptoSelect.addEventListener('change', (e) => {
        const crypto = e.target.value;
        if (!portInput.value || portInput.value === '587' || portInput.value === '465') {
          portInput.value = crypto === 'ssl' ? '465' : '587';
          portInput.style.transform = 'scale(1.02)';
          setTimeout(() => portInput.style.transform = '', 200);
        }
      });
    }

    // Auto-suggest common SMTP hosts
    const hostInput = document.getElementById('smtp_host');
    if (hostInput) {
      const commonHosts = [
        'smtp.gmail.com',
        'smtp-mail.outlook.com', 
        'smtp.mail.yahoo.com',
        'smtp.mailgun.org',
        'smtp.sendgrid.net'
      ];

      hostInput.addEventListener('input', (e) => {
        const value = e.target.value.toLowerCase();
        const suggestions = commonHosts.filter(host => 
          host.toLowerCase().includes(value) && value.length > 2
        );
        
        // Simple autocomplete (could be enhanced with dropdown)
        if (suggestions.length === 1 && value !== suggestions[0]) {
          // Auto-complete logic could be added here
        }
      });
    }

    // Copy from_email to smtp_user if they're the same
    const fromEmailInput = document.getElementById('from_email');
    const smtpUserInput = document.getElementById('smtp_user');
    
    if (fromEmailInput && smtpUserInput) {
      fromEmailInput.addEventListener('blur', (e) => {
        if (!smtpUserInput.value && e.target.value.includes('@')) {
          smtpUserInput.value = e.target.value;
          smtpUserInput.style.transform = 'scale(1.02)';
          setTimeout(() => smtpUserInput.style.transform = '', 200);
        }
      });
    }
  }

  // ===== Keyboard Shortcuts =====
  function initKeyboardShortcuts() {
    document.addEventListener('keydown', (e) => {
      // Ctrl/Cmd + Enter to submit form
      if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        e.preventDefault();
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn && !submitBtn.disabled) {
          submitBtn.click();
        }
      }

      // Ctrl/Cmd + D to fill demo data
      if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
        e.preventDefault();
        demoFill();
      }

      // Ctrl/Cmd + Shift + L to toggle theme
      if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'L') {
        e.preventDefault();
        toggleTheme();
      }
    });
  }

  // ===== Expose functions to global scope =====
  window.demoFill = demoFill;
  window.copyLog = copyLog;
  window.saveLog = saveLog;
  window.toggleTheme = toggleTheme;

  // ===== Initialize everything when DOM is ready =====
  document.addEventListener('DOMContentLoaded', () => {
    console.log('🚀 SMTP Tester Enhanced - Initializing...');
    
    // Initialize all features
    initTheme();
    initFormSubmission();
    initSmartFeatures();
    initKeyboardShortcuts();

    // Bind theme toggle
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
      themeToggle.addEventListener('click', toggleTheme);
    }

    // Show welcome message
    setTimeout(() => {
      showNotification('SMTP Tester siap digunakan! 🎉<br><small>Tips: Tekan Ctrl+D untuk isi contoh</small>', 'success');
    }, 1000);

    console.log('✅ SMTP Tester Enhanced - Ready!');
  });

})();