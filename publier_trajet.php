<?php
require_once __DIR__ . '/includes/trip_functions.php';
require_once __DIR__ . '/includes/auth_functions.php';
require_login();
$user = current_user();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: propose.php');
    exit;
}
$data = [
    'driver_id' => $user['id'],
    'departure_city' => trim($_POST['departure_city'] ?? ''),
    'arrival_city' => trim($_POST['arrival_city'] ?? ''),
    'departure_date' => $_POST['departure_date'] ?? '',
    'departure_time' => $_POST['departure_time'] ?? '',
    'available_seats' => (int)($_POST['available_seats'] ?? 0),
    'price' => (float)($_POST['price'] ?? 0),
    'description' => trim($_POST['description'] ?? '')
];
try {
    create_trip($data);
    header('Location: my-trajet.php');
    exit;
} catch (Exception $e) {
    header('Location: propose.php?error=' . urlencode($e->getMessage()));
    exit;
}
