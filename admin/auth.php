<?php
session_start();
include '../config.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'signup') {
        // Signup 
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($username) || empty($email) || empty($password)) {
            echo "<script>alert('All fields are required!');</script>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO myadmin (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                echo "<script>alert('Signup successful!'); window.location.href = 'loginadmin.php';</script>";
            } else {
                echo "<script>alert('Signup failed. Username or email might already exist.');window.location.href = 'loginadmin.php'</script>";
            }
            $stmt->close();
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'login') {
        // Login 
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            echo "<script>alert('All fields are required!');</script>";
        } else {
            $sql = "SELECT * FROM myadmin WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    echo "<script>alert('Login successful!'); window.location.href = 'adminhome.php';</script>";
                } else {
                    echo "<script>alert('Invalid password.'); window.location.href = 'loginadmin.php'</script>";
                }
            } else {
                echo "<script>alert('User not found.');</script>";
            }
            $stmt->close();
        }
    }
}
?>