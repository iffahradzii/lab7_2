<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createUser($matrix, $name, $password, $role) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (matrix, name, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssss", $matrix, $name, $password, $role);
            $result = $stmt->execute();
            if ($result) {
                return true;
            } else {
                return "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            return "Error: " . $this->conn->error;
        }
    }


    public function getUsers() {
        $sql = "SELECT matrix, name, role FROM users";
        $result = $this->conn->query($sql);
        return $result;
    }

    public function getUser($matrix) {
        $sql = "SELECT * FROM users WHERE matrix = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $matrix);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    public function updateUser($matrix, $name, $role) {
        $sql = "UPDATE users SET name = ?, role = ? WHERE matrix = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sss", $name, $role, $matrix);
            $result = $stmt->execute();
            if ($result) {
                return true;
            } else {
                return "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    public function deleteUser($matrix) {
        $sql = "DELETE FROM users WHERE matrix = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $matrix);
            $result = $stmt->execute();
            if ($result) {
                return true;
            } else {
                return "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    public function authenticateUser($matrix, $password) {
        $sql = "SELECT * FROM users WHERE matrix = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $matrix);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    return $user;
                }
            }
            $stmt->close();
        }
        return false;
    }
}
?>
