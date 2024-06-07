<?php
include_once 'Database.php';
include_once 'User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $result = $user->createUser($matric, $name, $password, $role);
    if ($result === true) {
        echo "<script>
                alert('New record created successfully');
                window.location.href = 'login.html';
              </script>";
    } else {
        echo $result;
    }
}
?>
