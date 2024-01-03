<?php
$configFile = __DIR__ . '/../config.json';
$config = json_decode(file_get_contents($configFile), true);

// Establish a database connection (replace with your database credentials)

$mysqli = new mysqli($config['DB_HOST'], $config['DB_USER'], $config['DB_PASSWORD'], $config['DB_NAME']);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve messages from the database based on the specified category
$stmt = $mysqli->prepare("SELECT * FROM channels");
$stmt->execute();
$result = $stmt->get_result();

$channels = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Add each message to the array
        $channels[] = array(
            'name' => $row['name'],
            'id' => $row['id'],
            'is_private' => $row['is_private'],
            'participants'  => json_decode($row['participants'])
        );
    }
}

// Close the database connection
$stmt->close();
$mysqli->close();

// Return messages as JSON
header('Content-Type: application/json');
echo json_encode($channels);
