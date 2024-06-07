<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

include_once 'Database.php';
include_once 'User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $matrix = $_POST['matrix'];
        $name = $_POST['name'];
        $role = $_POST['role'];
        $result = $user->updateUser($matrix, $name, $role);
        if ($result === true) {
            echo "<script>alert('User updated successfully.');</script>";
        } else {
            echo $result;
        }
    } elseif (isset($_POST['delete'])) {
        $matrix = $_POST['matrix'];
        $result = $user->deleteUser($matrix);
        if ($result === true) {
            echo "<script>alert('User deleted successfully.');</script>";
        } else {
            echo $result;
        }
    }
}

$result = $user->getUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="header">
        <h2>Hello, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></h2>
        <button class="btn-logout" onclick="window.location.href='logout.php'">Logout</button>
    </div>
    <table>
        <thead>
            <tr>
                <th>Matric</th>
                <th>Name</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <form action="users.php" method="POST">
                    <td><input type="text" name="matrix" value="<?php echo htmlspecialchars($row['matrix']); ?>" readonly></td>
                    <td><input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" readonly></td>
                    <td>
                        <select name="role">
                            <option value="lecturer" <?php if ($row['role'] == 'lecturer') echo 'selected'; ?>>Lecturer</option>
                            <option value="student" <?php if ($row['role'] == 'student') echo 'selected'; ?>>Student</option>
                        </select>
                    </td>
                    <td>
                        <button type="submit" name="update" class="btn-update">Update</button>
                        <button type="submit" name="delete" class="btn-delete">Delete</button>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
