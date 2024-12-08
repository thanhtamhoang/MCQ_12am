<?php
// Database configuration
$host = 'localhost';
$dbname = 'MultipleChoiceExam';
$username = 'root';
$password = '';

// Establish database connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Query the database
    $stmt = $conn->prepare("SELECT * FROM Users WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $input_username);
    $stmt->bindParam(':password', $input_password);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Start session and redirect based on role
        session_start();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        //Bat dau in trang do ne
        if ($user['role'] === 'student') {
            header('Location: Student/student_home.html');
        } else if ($user['role'] === 'teacher') {
            header('Location: Teacher/teacher_home.html');
        }
        exit();
    } else {
        echo "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="styles/sign-in.css">
</head>
<body>
    <form action="app.php" method="POST">
        <h1>Sign In</h1>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Sign In</button>
    </form>
</body>
<script src="script/sign-in.js"></script>
</html>
