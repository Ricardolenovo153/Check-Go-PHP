<?php
// Check-Go/php-backend/views/logout.php

require_once __DIR__ . '/../controllers/AuthController.php';

$auth = new AuthController();
$auth->logout();

header('Location: ../public/login.php');
exit();
?>