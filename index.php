<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$auth = new Auth();

if ($auth->isLoggedIn()) {
    redirect('views/dashboard.php');
} else {
    redirect('views/login.php');
}
