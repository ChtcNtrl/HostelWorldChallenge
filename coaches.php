<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $db = new PDO('sqlite:coaches.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $search = isset($_GET['search']) ? strtolower($_GET['search']) : '';
    $sort = $_GET['sort'] ?? '';

    $query = 'SELECT * FROM coaches';
    $params = [];

    if ($search) {
        $query .= ' WHERE LOWER(name) LIKE :search OR LOWER(location) LIKE :search';
        $params[':search'] = '%' . $search . '%';
    }

    if ($sort === 'rate-asc') {
        $query .= ' ORDER BY rate ASC';
    } elseif ($sort === 'rate-desc') {
        $query .= ' ORDER BY rate DESC';
    }

    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $coaches = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($coaches);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}