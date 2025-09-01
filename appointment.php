<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "hospital_db";

// Connect to MySQL
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data safely
$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$phone = htmlspecialchars($_POST['phone']);
$department = htmlspecialchars($_POST['department']);
$date = $_POST['date'];
$message = htmlspecialchars($_POST['message']);

// Insert into database
$sql = "INSERT INTO appointments (name, email, phone, department, date, message) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $name, $email, $phone, $department, $date, $message);

if ($stmt->execute()) {
    echo "<script>alert('Appointment booked successfully!'); window.location.href='index.html';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
