<?php
session_start();
include('db_connect.php');

// Initialize the session variable for last activity if it's not set
if (!isset($_SESSION['last_activity'])) {
    $_SESSION['last_activity'] = time();
}

// Check if the session has timed out (more than 2 seconds)
$timeoutDuration = 200; // 2 seconds
if (time() - $_SESSION['last_activity'] > $timeoutDuration) {
    // Unset session data and destroy the session
    session_unset();
    session_destroy();
    header("Location: packages.html");
    exit();
}

// Update the last activity timestamp for the session
$_SESSION['last_activity'] = time(); // Reset session timeout timer

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process payment details
    $paymentOption = htmlspecialchars($_POST['paymentOption']);
    $cardName = htmlspecialchars($_POST['cardName']);
    $cardNumber = htmlspecialchars($_POST['cardNumber']);
    $expiryDate = htmlspecialchars($_POST['expiryDate']);
    $cvv = htmlspecialchars($_POST['cvv']);
    $billingAddress = htmlspecialchars($_POST['billingAddress']);

    // Initialize an array to store error messages
    $errors = [];

    // Validate card name (letters and spaces only)
    if (empty($cardName) || !preg_match("/^[A-Za-z\s]+$/", $cardName)) {
        $errors[] = "Card holder name should only contain letters.";
    }

    // Validate card number (16 digits)
    if (!preg_match("/^\d{16}$/", $cardNumber)) {
        $errors[] = "Card number must be 16 digits.";
    }

    // Validate expiry date (must be in the future)
    if (strtotime($expiryDate) < strtotime(date("Y-m"))) {
        $errors[] = "Expiry date should be in the future.";
    }

    // Validate CVV (3 or 4 digits)
    if (!preg_match("/^\d{3,4}$/", $cvv)) {
        $errors[] = "CVV must be 3 or 4 digits.";
    }

    // Validate billing address
    if (empty($billingAddress)) {
        $errors[] = "Billing address is required.";
    }

    // If there are errors, display them
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<script>alert('$error');</script>";
        }
        exit(); // Stop execution if validation fails
    }

    // Process payment if no validation errors exist
    try {
        // Prepare an SQL statement to insert payment details
        $stmt = $conn->prepare("INSERT INTO payments (paymentOption, cardName, cardNumber, expiryDate, cvv, billingAddress) 
            VALUES (:paymentOption, :cardName, :cardNumber, :expiryDate, :cvv, :billingAddress)");

        // Bind parameters
        $stmt->bindParam(':paymentOption', $paymentOption);
        $stmt->bindParam(':cardName', $cardName);
        $stmt->bindParam(':cardNumber', $cardNumber);
        $stmt->bindParam(':expiryDate', $expiryDate);
        $stmt->bindParam(':cvv', $cvv);
        $stmt->bindParam(':billingAddress', $billingAddress);

        // Execute the statement
        $stmt->execute();

        // Redirect to the confirmation page if the payment is successful
        echo "<script>alert('Payment successful!');</script>";
        echo "<script>window.location.href = 'thankyou.php';</script>";
        exit();

    } catch (PDOException $e) {
        // Handle any errors that occur during database interaction
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $conn = null;
}
?>
