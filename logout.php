<?php
require_once __DIR__ . '/includes/auth_functions.php';
logout();
header('Location: index.php');
exit;
