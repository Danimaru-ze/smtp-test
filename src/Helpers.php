<?php
declare(strict_types=1);

function oldf(string $key, string $default = ''): string {
    $v = $default;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $v = $_POST[$key] ?? $default;
    } elseif (isset($_SESSION['smtp_tester'][$key])) {
        $v = $_SESSION['smtp_tester'][$key];
    }
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
