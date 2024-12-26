<?php
/**
 * This file provides an API endpoint to fetch coach data from the database.
 * The data can be filtered by search terms and sorted by rate.
 *
 * Query Parameters:
 * - search (optional): A string to search for in the coach's name or location. The search is case-insensitive.
 * - sort (optional): A string to sort the results by rate. Possible values are:
 *   - rate-asc: Sort by rate in ascending order.
 *   - rate-desc: Sort by rate in descending order.
 *
 * Response:
 * - On success: Returns a JSON array of coach objects.
 * - On error: Returns a JSON object with an error key containing the error message.
 *
 * Example Usage:
 * - Fetch all coaches:
 *   GET /coaches.php
 *
 * - Search for coaches with "john" in their name or location:
 *   GET /coaches.php?search=john
 *
 * - Fetch all coaches sorted by rate in ascending order:
 *   GET /coaches.php?sort=rate-asc
 *
 * - Fetch all coaches with "john" in their name or location, sorted by rate in descending order:
 *   GET /coaches.php?search=john&sort=rate-desc
 *
 * Example Response:
 * [
 *     {
 *         "id": 1,
 *         "name": "John Doe",
 *         "experience": 10,
 *         "rate": 50,
 *         "location": "New York",
 *         "joined": "2020-01-01"
 *     },
 *     {
 *         "id": 2,
 *         "name": "Jane Smith",
 *         "experience": 5,
 *         "rate": 40,
 *         "location": "Los Angeles",
 *         "joined": "2019-05-15"
 *     }
 * ]
 *
 * Error Response:
 * {
 *     "error": "Error message"
 * }
 */


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