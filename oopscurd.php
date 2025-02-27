<!-- Index.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h3 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 14px;
            color: #333;
            display: block;
            margin-bottom: 8px;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            color: #333;
            box-sizing: border-box;
        }

        .form-group input[type="radio"],
        .form-group input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }

        .form-group div {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .form-group button {
            padding: 12px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .form-group button:focus {
            outline: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>User Registration</h3>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Gender</label>
                <div>
                    <input type="radio" id="male" name="gender" value="male"> Male
                    <input type="radio" id="female" name="gender" value="female"> Female
                    <input type="radio" id="other" name="gender" value="other"> Other
                </div>
            </div>
            <div class="form-group">
                <label>Hobbies</label>
                <div>
                    <input type="checkbox" id="reading" name="hobbies[]" value="Reading"> Reading
                    <input type="checkbox" id="sports" name="hobbies[]" value="Sports"> Sports
                    <input type="checkbox" id="travelling" name="hobbies[]" value="Travelling"> Travelling
                </div>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <select id="city" name="city">
                    <option value="New York">New York</option>
                    <option value="Los Angeles">Los Angeles</option>
                    <option value="Chicago">Chicago</option>
                    <option value="Houston">Houston</option>
                    <option value="Phoenix">Phoenix</option>
                </select>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>



<!-- register.php -->


<?php
class UserRegistration {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "oopscurd");

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function registerUser($firstname, $lastname, $email, $password, $gender, $hobbies, $city) {
        $hobbies_str = !empty($hobbies) ? implode(", ", $hobbies) : "";

        $query = "INSERT INTO users (firstname, lastname, email, password, gender, hobbies, city) 
                  VALUES ('$firstname', '$lastname', '$email', '$password', '$gender', '$hobbies_str', '$city')";

        if ($this->conn->query($query) === TRUE) {
            echo "<script>
            alert('Registration successful!');
            window.location.href = 'user-list.php';
          </script>";
        } else {
            echo "Error: " . $this->conn->error;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new UserRegistration();
    $user->registerUser(
        $_POST['firstname'], 
        $_POST['lastname'], 
        $_POST['email'], 
        $_POST['password'], 
        $_POST['gender'], 
        isset($_POST['hobbies']) ? $_POST['hobbies'] : [], 
        $_POST['city']
    );
}
?>



<!-- user-list.php -->


<?php
class UserDisplay {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "oopscurd");

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function fetchUsers() {
        $query = "SELECT * FROM users";
        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {
            echo "<table border='1' cellpadding='10' cellspacing='0'>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Hobbies</th>
                        <th>City</th>
                        <th>Actions</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['firstname']}</td>
                        <td>{$row['lastname']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['gender']}</td>
                        <td>{$row['hobbies']}</td>
                        <td>{$row['city']}</td>
                        <td>
                            <a href='edit.php?id={$row['id']}'>Edit</a> | 
                            <a href='delete.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                        </td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "No users found.";
        }
    }
}

$userDisplay = new UserDisplay();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-top: 20px;
        }

        a {
            color: #007BFF;
            font-size: 18px;
            text-decoration: none;
            display: inline-block;
            background-color: ghostwhite;
           
        }

        a:hover {
            text-decoration: underline;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td a {
            color: #007BFF;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div>
    <h2>User List</h2>
    <a href="index.php">Add New User</a>
    </div>
  
    <?php $userDisplay->fetchUsers(); ?>
</body>
</html>



<!-- edite.php -->

<?php
class UserEdit {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "oopscurd");

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getUser($id) {
        $query = "SELECT * FROM users WHERE id = $id";
        $result = $this->conn->query($query);
        return $result->fetch_assoc();
    }

    public function updateUser($id, $firstname, $lastname, $email, $gender, $hobbies, $city) {
        $hobbies_str = !empty($hobbies) ? implode(", ", $hobbies) : "";

        $query = "UPDATE users SET 
                    firstname = '$firstname', 
                    lastname = '$lastname', 
                    email = '$email', 
                    gender = '$gender', 
                    hobbies = '$hobbies_str', 
                    city = '$city' 
                  WHERE id = $id";

        if ($this->conn->query($query) === TRUE) {
            echo "<script>
                    alert('User updated successfully!');
                    window.location.href = 'user-list.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error: " . $this->conn->error . "');
                  </script>";
        }
    }
}

$userEdit = new UserEdit();

if (isset($_POST['update'])) {
    $userEdit->updateUser(
        $_POST['id'],
        $_POST['firstname'],
        $_POST['lastname'],
        $_POST['email'],
        $_POST['gender'],
        isset($_POST['hobbies']) ? $_POST['hobbies'] : [],
        $_POST['city']
    );
}

if (isset($_GET['id'])) {
    $user = $userEdit->getUser($_GET['id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 14px;
            color: #333;
            display: block;
            margin-bottom: 8px;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group select,
        .form-group input[type="radio"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            color: #333;
            box-sizing: border-box;
        }

        .form-group input[type="radio"] {
            width: auto;
            margin-right: 10px;
        }

        .form-group input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }

        .form-group select {
            cursor: pointer;
        }

        .form-group button {
            padding: 12px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .form-group button:focus {
            outline: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit User</h2>
    <form action="edit.php" method="POST">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="firstname" value="<?= $user['firstname'] ?>" required>
        </div>

        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="lastname" value="<?= $user['lastname'] ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= $user['email'] ?>" required>
        </div>

        <div class="form-group">
            <label>Gender</label>
            <input type="radio" name="gender" value="male" <?= $user['gender'] == 'male' ? 'checked' : '' ?>> Male
            <input type="radio" name="gender" value="female" <?= $user['gender'] == 'female' ? 'checked' : '' ?>> Female
            <input type="radio" name="gender" value="other" <?= $user['gender'] == 'other' ? 'checked' : '' ?>> Other
        </div>

        <div class="form-group">
            <label>Hobbies</label>
            <?php
            $hobbies = explode(", ", $user['hobbies']);
            ?>
            <input type="checkbox" name="hobbies[]" value="Reading" <?= in_array('Reading', $hobbies) ? 'checked' : '' ?>> Reading
            <input type="checkbox" name="hobbies[]" value="Sports" <?= in_array('Sports', $hobbies) ? 'checked' : '' ?>> Sports
            <input type="checkbox" name="hobbies[]" value="Travelling" <?= in_array('Travelling', $hobbies) ? 'checked' : '' ?>> Travelling
        </div>

        <div class="form-group">
            <label>City</label>
            <select name="city">
                <option value="New York" <?= $user['city'] == 'New York' ? 'selected' : '' ?>>New York</option>
                <option value="Los Angeles" <?= $user['city'] == 'Los Angeles' ? 'selected' : '' ?>>Los Angeles</option>
                <option value="Chicago" <?= $user['city'] == 'Chicago' ? 'selected' : '' ?>>Chicago</option>
                <option value="Houston" <?= $user['city'] == 'Houston' ? 'selected' : '' ?>>Houston</option>
                <option value="Phoenix" <?= $user['city'] == 'Phoenix' ? 'selected' : '' ?>>Phoenix</option>
            </select>
        </div>

        <button type="submit" name="update">Update</button>
    </form>
</div>

</body>
</html>



<!-- delete.php -->

<?php
class UserDelete {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "oopscurd");

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function deleteUser($id) {
        $query = "DELETE FROM users WHERE id = $id";

        if ($this->conn->query($query) === TRUE) {
            echo "<script>
                    alert('User deleted successfully!');
                    window.location.href = 'user-list.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error: " . $this->conn->error . "');
                    window.location.href = 'index.php';
                  </script>";
        }
    }
}

if (isset($_GET['id'])) {
    $userDelete = new UserDelete();
    $userDelete->deleteUser($_GET['id']);
}
?>



<!-- table and databse -->

CREATE DATABASE user_db;

USE user_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    hobbies TEXT, -- Storing multiple hobbies as a comma-separated string
    city VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);