<?php
try {
    // Create (or open) the SQLite database
    $db = new PDO('sqlite:coaches.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the coaches table
    $db->exec("CREATE TABLE IF NOT EXISTS coaches (
        id INTEGER PRIMARY KEY,
        name TEXT,
        experience INTEGER,
        rate INTEGER,
        location TEXT,
        joined TEXT
    )");

    // Read the JSON file
    $json = file_get_contents('coaches.json');
    $coaches = json_decode($json, true);

    // Insert the coaches data into the database
    $stmt = $db->prepare("INSERT INTO coaches (name, experience, rate, location, joined) VALUES (:name, :experience, :rate, :location, :joined)");
    foreach ($coaches as $coach) {
        $stmt->execute([
            ':name' => $coach['name'],
            ':experience' => $coach['experience'],
            ':rate' => $coach['rate'],
            ':location' => $coach['location'],
            ':joined' => $coach['joined']
        ]);
    }

    echo "Database setup and data insertion completed successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
