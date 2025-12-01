<?php
session_start();
include('db_connect.php');  // Include database connection file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer library files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $mobile = htmlspecialchars(trim($_POST['mobile']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

    // Validation
    $errors = [];
    if (!preg_match("/^[a-zA-Z\s]+$/", $first_name)) $errors[] = "First name should contain only letters.";
    if (!preg_match("/^[a-zA-Z\s]+$/", $last_name)) $errors[] = "Last name should contain only letters.";
    if (!preg_match("/^\d{10}$/", $mobile)) $errors[] = "Mobile number must be 10 digits.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
    if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $password)) {
        $errors[] = "Password must be at least 8 characters long, contain an uppercase letter, and a special character.";
    }
    if ($password !== $confirm_password) $errors[] = "Passwords do not match.";

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<script>alert('$error');</script>";
        }
        exit;
    }

    // Hash password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tourists WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $email_exists = $stmt->fetchColumn();

    if ($email_exists) {
        echo "<script>alert('This email is already registered. Please use a different email.');</script>";
        exit;
    }

    // Insert user into the database
    try {
        $stmt = $conn->prepare("INSERT INTO tourists (first_name, last_name, mobile, email, password) 
                                VALUES (:first_name, :last_name, :mobile, :email, :password)");
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        // Email setup
        $mail = new PHPMailer(true);
        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'shreya.shankar@somaiya.edu'; // Your email address
            $mail->Password = 'opgf lhdl tzog bisj';         // Your email password
            $mail->SMTPSecure = 'ssl';                       // Encryption: 'tls' or 'ssl'
            $mail->Port = 465;                               // Port: 587 for TLS, 465 for SSL

            // Recipients
            $mail->setFrom('shreya.shankar@somaiya.edu', 'Globesetter'); // From address
            $mail->addAddress($email);                              // User's email address

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Registration';
            $mail->Body    = "<h1>Thank you, $first_name!</h1><p>You have successfully registered on our website.</p>";

            $mail->send();

            echo 'Confirmation email has been sent.';
        } catch (Exception $e) {
            echo "Error in sending email: {$mail->ErrorInfo}";
        }

        echo "<script>window.location.href = 'Home.html';</script>";
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null; // Close the database connection
}
?>

