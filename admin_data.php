<?php
session_start();
include('db_connect.php'); // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // User details from form submission
    $full_name = isset($_POST['full_name']) ? htmlspecialchars(trim($_POST['full_name'])) : null;
    $employee_id = isset($_POST['employee_id']) ? htmlspecialchars(trim($_POST['employee_id'])) : null;
    $mobile = isset($_POST['mobile']) ? htmlspecialchars(trim($_POST['mobile'])) : null;
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : null;
    $password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : null;
    $access_code = isset($_POST['access_code']) ? htmlspecialchars(trim($_POST['access_code'])) : null;
    $security_question = isset($_POST['security_question']) ? htmlspecialchars(trim($_POST['security_question'])) : null;
    $security_answer = isset($_POST['security_answer']) ? htmlspecialchars(trim($_POST['security_answer'])) : null;

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Prepare SQL statement to insert a new admin record
        $stmt = $conn->prepare("INSERT INTO admin (full_name, employee_id, mobile, email, password, access_code, security_question, security_answer) 
                                 VALUES (:full_name, :employee_id, :mobile, :email, :password, :access_code, :security_question, :security_answer)");

        // Bind parameters
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':employee_id', $employee_id);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password); // Use the hashed password here
        $stmt->bindParam(':access_code', $access_code);
        $stmt->bindParam(':security_question', $security_question);
        $stmt->bindParam(':security_answer', $security_answer);

        // Execute the statement
        $stmt->execute();
        $_SESSION['success_message'] = "Admin registration successful.";

        // Redirect to a success page (e.g., admin_action.html)
        header("Location: admin_action.html");
        exit(); // Ensure no further code is executed after the redirect

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }

    // Close the connection
    $conn = null;
}
?>

