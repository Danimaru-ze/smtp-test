<?php
declare(strict_types=1);
session_start();

// Simpan input supaya tetap terisi kalau reload manual
$_SESSION['smtp_tester'] = $_POST;

// Pastikan vendor autoload tersedia
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
    $payload = [
        'status' => 'fail',
        'log'    => "PHPMailer belum terpasang. Jalankan:\ncomposer require phpmailer/phpmailer",
    ];
    if (isset($_POST['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode($payload);
        exit;
    }
    $_SESSION['smtp_last'] = $payload;
    header('Location: index.php');
    exit;
}

require $autoload;
require __DIR__ . '/../src/Mailer.php';

use App\Mailer;

$mailer = new Mailer();
$result = $mailer->send($_POST);

// Simpan juga ke session (fallback non-AJAX)
$_SESSION['smtp_last'] = [
    'status' => $result['status'],
    'log'    => $result['log'],
];

// Jika AJAX → balas JSON agar log tampil di halaman tanpa reload
if (isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => $result['status'],
        'log'    => $result['log'],
    ]);
    exit;
}

// Fallback non-AJAX
header('Location: index.php');
exit;
