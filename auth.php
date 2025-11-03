<?php
// auth.php - small helper (optional). You can include this to ensure sessions are started.
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/includes/auth_functions.php';
?>
