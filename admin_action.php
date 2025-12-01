<?php
session_start();
include('db_connect.php'); // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $updateField = isset($_POST['updateField']) ? $_POST['updateField'] : null;
    $newValue = isset($_POST['newValue']) ? htmlspecialchars(trim($_POST['newValue'])) : null;

    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : null;
    $destination = isset($_POST['destination']) ? htmlspecialchars(trim($_POST['destination'])) : null;
    $amount = isset($_POST['amount']) ? htmlspecialchars(trim($_POST['amount'])) : null;
    $mobile = isset($_POST['mobile']) ? htmlspecialchars(trim($_POST['mobile'])) : null;
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : null;
    $rooms = isset($_POST['rooms']) ? htmlspecialchars(trim($_POST['rooms'])) : null;
    $guests = isset($_POST['guests']) ? htmlspecialchars(trim($_POST['guests'])) : null;
    $checkin = isset($_POST['checkin']) ? htmlspecialchars(trim($_POST['checkin'])) : null;
    $checkout = isset($_POST['checkout']) ? htmlspecialchars(trim($_POST['checkout'])) : null;

    try {
        if ($action === "insert") {
            // Insert a new user
            $stmt = $conn->prepare("INSERT INTO users (name, destination, amount, mobile, email, rooms, guests, checkin, checkout) 
                                    VALUES (:name, :destination, :amount, :mobile, :email, :rooms, :guests, :checkin, :checkout)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':destination', $destination);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':mobile', $mobile);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':rooms', $rooms);
            $stmt->bindParam(':guests', $guests);
            $stmt->bindParam(':checkin', $checkin);
            $stmt->bindParam(':checkout', $checkout);
            $stmt->execute();
            echo "User record inserted successfully.";
        
        } elseif ($action === "update") {
            // Update a specific field for an existing user
            if ($id && $updateField && $newValue) {
                $stmt = $conn->prepare("UPDATE users SET $updateField = :newValue WHERE id = :id");
                $stmt->bindParam(':newValue', $newValue);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                echo "User record updated successfully.";
            } else {
                echo "User ID, field, and new value are required for updating.";
            }
        
        } elseif ($action === "delete") {
            // Delete a user based on ID
            if ($id) {
                $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                echo "User record deleted successfully.";
            } else {
                echo "User ID is required for deletion.";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the connection
    $conn = null;
}
?>

