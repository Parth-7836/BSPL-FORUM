<?php
include 'partials/_dbconnect.php'; // Include the database connection file
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Validate the form data
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: contact.php?status=error");
        exit;
    }

    // Escape the form data to prevent SQL injection
    $email = mysqli_real_escape_string($conn, $email);
    $subject = mysqli_real_escape_string($conn, $subject);
    $message = mysqli_real_escape_string($conn, $message);

    // Prepare the SQL query
    $sql = "INSERT INTO contacts (email, subject, message) VALUES ('$email', '$subject', '$message')";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        header("Location: contact.php?status=success");
    } else {
        header("Location: contact.php?status=error");
    }

    // Close the connection
    mysqli_close($conn);
} else {
    header("Location: contact.php?status=error");
}
?>