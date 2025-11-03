<?php
require_once __DIR__ . '/../config/database.php';

function create_trip($data) {
    $pdo = getPDO();
    $sql = "INSERT INTO trips (driver_id, departure_city, arrival_city, departure_date, departure_time, available_seats, price, description) VALUES (:driver_id,:departure_city,:arrival_city,:departure_date,:departure_time,:available_seats,:price,:description)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':driver_id' => $data['driver_id'],
        ':departure_city' => $data['departure_city'],
        ':arrival_city' => $data['arrival_city'],
        ':departure_date' => $data['departure_date'],
        ':departure_time' => $data['departure_time'],
        ':available_seats' => $data['available_seats'],
        ':price' => $data['price'],
        ':description' => $data['description']
    ]);
    return $pdo->lastInsertId();
}

function search_trips($filters = []) {
    $pdo = getPDO();
    $sql = "SELECT t.*, u.first_name, u.last_name FROM trips t LEFT JOIN users u ON t.driver_id = u.id WHERE t.status = 'active'";
    $params = [];
    if (!empty($filters['departure_city'])) {
        $sql .= " AND t.departure_city LIKE :departure_city";
        $params[':departure_city'] = "%" . $filters['departure_city'] . "%";
    }
    if (!empty($filters['arrival_city'])) {
        $sql .= " AND t.arrival_city LIKE :arrival_city";
        $params[':arrival_city'] = "%" . $filters['arrival_city'] . "%";
    }
    if (!empty($filters['departure_date'])) {
        $sql .= " AND t.departure_date = :departure_date";
        $params[':departure_date'] = $filters['departure_date'];
    }
    if (!empty($filters['min_seats'])) {
        $sql .= " AND t.available_seats >= :min_seats";
        $params[':min_seats'] = (int)$filters['min_seats'];
    }

    $sql .= " ORDER BY t.departure_date, t.departure_time";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function get_trip_by_id($id) {
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT t.*, u.first_name, u.last_name FROM trips t LEFT JOIN users u ON t.driver_id = u.id WHERE t.id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

function reserve_trip($trip_id, $passenger_id, $seats) {
    $pdo = getPDO();
    try {
        $pdo->beginTransaction();
        
        // Check if user already has a reservation for this trip
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE trip_id = :trip_id AND passenger_id = :passenger_id AND status = 'confirmed'");
        $stmt->execute([':trip_id' => $trip_id, ':passenger_id' => $passenger_id]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception('Vous avez déjà une réservation pour ce trajet');
        }
        
        // Check trip availability
        $stmt = $pdo->prepare("SELECT available_seats FROM trips WHERE id = :id FOR UPDATE");
        $stmt->execute([':id' => $trip_id]);
        $trip = $stmt->fetch();
        if (!$trip) throw new Exception('Trajet introuvable');
        if ($trip['available_seats'] < $seats) throw new Exception('Pas assez de places disponibles');

        $stmt = $pdo->prepare("INSERT INTO reservations (trip_id, passenger_id, seats_reserved) VALUES (:trip_id, :passenger_id, :seats)");
        $stmt->execute([':trip_id' => $trip_id, ':passenger_id' => $passenger_id, ':seats' => $seats]);

        $stmt = $pdo->prepare("UPDATE trips SET available_seats = available_seats - :s WHERE id = :id");
        $stmt->execute([':s' => $seats, ':id' => $trip_id]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return $e->getMessage();
    }
}

function get_user_trips($user_id) {
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT * FROM trips WHERE driver_id = :id ORDER BY created_at DESC");
    $stmt->execute([':id' => $user_id]);
    return $stmt->fetchAll();
}

function get_user_reservations($user_id) {
    $pdo = getPDO();
    $stmt = $pdo->prepare("
        SELECT r.*, 
               t.departure_city, t.arrival_city, t.departure_date, t.departure_time,
               u.first_name as driver_first_name, u.last_name as driver_last_name,
               CONCAT(u.first_name, ' ', u.last_name) as driver_name
        FROM reservations r 
        JOIN trips t ON r.trip_id = t.id 
        LEFT JOIN users u ON t.driver_id = u.id 
        WHERE r.passenger_id = :id 
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([':id' => $user_id]);
    return $stmt->fetchAll();
}

function get_user_trips_count($user_id) {
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM trips WHERE driver_id = :id");
    $stmt->execute([':id' => $user_id]);
    return (int)$stmt->fetchColumn();
}

function get_user_reservations_count($user_id) {
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE passenger_id = :id AND status = 'confirmed'");
    $stmt->execute([':id' => $user_id]);
    return (int)$stmt->fetchColumn();
}

function add_review($data) {
    $pdo = getPDO();
    try {
        $pdo->beginTransaction();

        // Check if already reviewed
        $stmt = $pdo->prepare("SELECT id FROM reviews WHERE trip_id = :trip_id AND reviewer_id = :reviewer_id");
        $stmt->execute([':trip_id' => $data['trip_id'], ':reviewer_id' => $data['reviewer_id']]);
        if ($stmt->fetch()) {
            throw new Exception('Vous avez déjà donné votre avis pour ce trajet');
        }

        // Add review
        $stmt = $pdo->prepare("INSERT INTO reviews (trip_id, reviewer_id, driver_id, rating, comment) 
                              VALUES (:trip_id, :reviewer_id, :driver_id, :rating, :comment)");
        $stmt->execute([
            ':trip_id' => $data['trip_id'],
            ':reviewer_id' => $data['reviewer_id'],
            ':driver_id' => $data['driver_id'],
            ':rating' => $data['rating'],
            ':comment' => $data['comment']
        ]);

        // Update driver's average rating
        $stmt = $pdo->prepare("
            UPDATE users u 
            SET average_rating = (
                SELECT AVG(rating) 
                FROM reviews 
                WHERE driver_id = u.id
            ),
            total_reviews = (
                SELECT COUNT(*) 
                FROM reviews 
                WHERE driver_id = u.id
            )
            WHERE id = :driver_id
        ");
        $stmt->execute([':driver_id' => $data['driver_id']]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return $e->getMessage();
    }
}

function get_user_reviews($user_id) {
    $pdo = getPDO();
    $stmt = $pdo->prepare("
        SELECT r.*, 
               t.departure_city, t.arrival_city, t.departure_date,
               u.first_name as reviewer_first_name, u.last_name as reviewer_last_name
        FROM reviews r
        JOIN trips t ON r.trip_id = t.id
        JOIN users u ON r.reviewer_id = u.id
        WHERE r.driver_id = :id
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([':id' => $user_id]);
    return $stmt->fetchAll();
}

function get_trip_reviews($trip_id) {
    $pdo = getPDO();
    $stmt = $pdo->prepare("
        SELECT r.*, 
               u.first_name as reviewer_first_name, u.last_name as reviewer_last_name
        FROM reviews r
        JOIN users u ON r.reviewer_id = u.id
        WHERE r.trip_id = :trip_id
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([':trip_id' => $trip_id]);
    return $stmt->fetchAll();
}

function can_review_trip($trip_id, $user_id) {
    $pdo = getPDO();
    // Check if user has a confirmed reservation and hasn't reviewed yet
    $stmt = $pdo->prepare("
        SELECT 1
        FROM reservations r
        LEFT JOIN reviews rv ON rv.trip_id = r.trip_id AND rv.reviewer_id = r.passenger_id
        WHERE r.trip_id = :trip_id 
        AND r.passenger_id = :user_id 
        AND r.status = 'confirmed'
        AND rv.id IS NULL
    ");
    $stmt->execute([':trip_id' => $trip_id, ':user_id' => $user_id]);
    return (bool)$stmt->fetch();
}

