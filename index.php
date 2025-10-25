<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$auth = new Auth();

if ($auth->isLoggedIn()) {
    redirect('/dashboard.php');
} else {
    redirect('/login.php');
}
