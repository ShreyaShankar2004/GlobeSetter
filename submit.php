<?php
session_start();
include('db_connect.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer library files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and validate input values
    $name = htmlspecialchars(trim($_POST['name']));
    $destination = htmlspecialchars(trim($_POST['destination']));
    $amount = htmlspecialchars(trim($_POST['amount']));
    $mobile = htmlspecialchars(trim($_POST['mobile']));
    $Email = htmlspecialchars(trim($_POST['Email']));
    $rooms = htmlspecialchars(trim($_POST['rooms']));
    $guests = htmlspecialchars(trim($_POST['guests']));
    $checkin = htmlspecialchars(trim($_POST['checkin']));
    $checkout = htmlspecialchars(trim($_POST['checkout']));

    // Additional validations
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) die("Name should contain only letters and spaces.");
    if (empty($destination)) die("Destination cannot be empty.");
    if (!is_numeric($amount) || $amount <= 0) die("Amount must be a positive number.");
    if (!preg_match('/^\d{10}$/', $mobile)) die("Mobile number must be exactly 10 digits.");
    if (!filter_var($rooms, FILTER_VALIDATE_INT) || $rooms <= 0) die("Rooms must be a positive integer.");
    if (!filter_var($guests, FILTER_VALIDATE_INT) || $guests <= 0) die("Guests must be a positive integer.");
    if (!strtotime($checkin) || !strtotime($checkout) || strtotime($checkout) <= strtotime($checkin)) die("Check-in and check-out dates must be valid and logical.");

    try {
        // Database insertion
        $stmt = $conn->prepare("INSERT INTO users (name, destination, amount, mobile, Email, guests, rooms, checkin, checkout) VALUES (:name, :destination, :amount, :mobile, :Email, :guests, :rooms, :checkin, :checkout)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':destination', $destination);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->bindParam(':Email', $Email);
        $stmt->bindParam(':guests', $guests);
        $stmt->bindParam(':rooms', $rooms);
        $stmt->bindParam(':checkin', $checkin);
        $stmt->bindParam(':checkout', $checkout);
        $stmt->execute();

        // Store user details in session
        $_SESSION['mobile'] = $mobile;
        $_SESSION['Email'] = $Email;

        // Email setup
        $mail = new PHPMailer(true);
        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';      // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'shreya.shankar@somaiya.edu'; // Your email address
            $mail->Password = 'opgf lhdl tzog bisj';         // Your email password
            $mail->SMTPSecure = 'ssl';                 // Encryption: 'tls' or 'ssl'
            $mail->Port = 465;                         // Port: 587 for TLS, 465 for SSL

            // Recipients
            $mail->setFrom('shreya.shankar@somaiya.edu', 'Globesetter'); // From address
            $mail->addAddress($Email);                              // User's email address

           // Email content
$mail->isHTML(true);
$mail->Subject = 'Booking Confirmation';
$mail->Body    = "
    <h1>Thank you, $name!</h1>
    <p>Your booking to <strong>$destination</strong> has been confirmed.</p>
    <p>Total amount: <strong>$$amount</strong></p>
    <p>Check-in: <strong>$checkin</strong></p>
    <p>Check-out: <strong>$checkout</strong></p>
    <p style='color: red; font-weight: bold;'>⚠️ Reply to this mail if you want to cancel the booking within 24 hours.</p>
";
$mail->AltBody = "Thank you, $name! Your booking to $destination has been confirmed. 
Total amount: $$amount. 
Check-in: $checkin. 
Check-out: $checkout. 
WARNING: Reply to this mail if you want to cancel the booking within 24 hours.";

            $mail->send();
            echo "<script>window.location.href = 'pay.html';</script>";
        } catch (Exception $e) {
            echo "Error in sending email: {$mail->ErrorInfo}";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
}
?>
