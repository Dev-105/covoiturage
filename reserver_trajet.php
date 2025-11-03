<?php
require_once __DIR__ . '/includes/auth_functions.php';
require_once __DIR__ . '/includes/trip_functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}
$trip_id = (int)($_POST['trip_id'] ?? 0);
$seats = (int)($_POST['seats'] ?? 1);
$user = current_user();

$result = reserve_trip($trip_id, $user['id'], $seats);
if ($result === true) {
    header('Location: my-trajet.php?reserved=1');
    exit;
} else {
    // error message stored in $result
    header('Location: reserver.php?id=' . $trip_id . '&error=' . urlencode($result));
    exit;
}
