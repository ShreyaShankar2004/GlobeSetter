<?php
// create_db.php
$host = 'localhost'; // Your database host
$db = 'travel_db'; // Database name
$user = 'your_username'; // Your database username
$pass = 'your_password'; // Your database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $db");
    echo "Database '$db' created successfully.<br>";

    // Use the created database
    $pdo->exec("USE $db");

    // Create the users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        mobile VARCHAR(15) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )";
    
    $pdo->exec($sql);
    echo "Table 'users' created successfully.";
} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}
?>
