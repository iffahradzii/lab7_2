<?php
include_once 'Database.php';
include_once 'User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    $authenticatedUser = $user->authenticateUser($matric, $password);
    if ($authenticatedUser) {
        session_start();
        $_SESSION['user'] = $authenticatedUser;
        header("Location: users.php");
    } else {
        echo "Authentication failed. <a href='login.html'>Try again</a>";
    }
}
?>
