<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$nameErr = $emailErr = $passwordErr = $dobErr = $genderErr = $profile_imageErr = $hobbiesErr = $cityErr = $stateErr = $aboutErr = $countryErr = "";
$name = $email = $password = $dob = $gender = $city = $state = $country = $about = $profile_image = "";
$hobbies = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $nameErr = "Only letters and spaces allowed";
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
    
        if (strlen($password) < 6 || !preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_]).{6,}$/", $password)) {
            $passwordErr = "Password must be at least 6 characters and include uppercase, lowercase, number, and special character.";
        }
    }
    

    if (empty($_POST["dob"])) {
        $dobErr = "Date of Birth is required";
    } else {
        $dob = test_input($_POST["dob"]);
    
        $birthDate = new DateTime($dob);
        $currentDate = new DateTime();
        $age = $currentDate->diff($birthDate)->y;
    
        if ($age < 18) {
            $dobErr = "You must be at least 18 years old.";
        }
    }



    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }



    if (empty($_POST["hobbies"])) {
        $hobbiesErr = "At least one hobby is required";
    } else {
        $hobbies = $_POST["hobbies"];
    }



    if (empty($_POST["city"])) {
        $cityErr = "City is required";
    } else {
        $city = $_POST["city"];
    }

    if (empty($_POST["state"])) {
        $stateErr = "State is required";
    } else {
        $state = $_POST["state"];
    }

    if (empty($_POST["about"])) {
        $aboutErr = "About you is required";
    } else {
        $about = test_input($_POST["about"]);
    
        if (strlen($about) > 100) {
            $aboutErr = "About you must be less than or equal to 100 characters";
        }
    }

    if (empty($_POST["country"])) {
        $countryErr = "Country is required";
    } else {
        $country = $_POST["country"];
    }

    if ($_FILES["profile_image"]["size"] > 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $profile_imageErr = "Only JPG, JPEG, and PNG files are allowed.";
        } else {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $profile_image = $target_file;
            } else {
                $profile_imageErr = "Sorry, there was an error uploading your file.";
            }
        }
    }

    if (empty($nameErr) && empty($emailErr) && empty($passwordErr) && empty($dobErr) && empty($genderErr) && empty($profile_imageErr) && empty($hobbiesErr) && empty($cityErr) && empty($stateErr) && empty($aboutErr) && empty($countryErr)) {
        $hobbiesStr = implode(", ", $hobbies); 

        $sql = "INSERT INTO users (name, email, password, dob, gender, hobbies, city, state, country, profile_image, about)
                VALUES ('$name', '$email', '$password', '$dob', '$gender', '$hobbiesStr', '$city', '$state', '$country', '$profile_image', '$about')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
            alert('New record created successfully')
            window.location.href = 'index.php';
        </script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

$conn->close();

// Sanitize input data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form with Validation</title>
</head>
<body>

    <h2>Registration Form</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        
        <div>
            <label>Name</label>
            <input type="text" name="name" value="<?php echo $name; ?>">
            <span style="color:red;">* <?php echo $nameErr; ?></span>
        </div>

        <div>
            <label>Email</label>
            <input type="text" name="email" value="<?php echo $email; ?>">
            <span style="color:red;">* <?php echo $emailErr; ?></span>
        </div>

        <div>
            <label>Password</label>
            <input type="text" name="password" value="<?php echo $password; ?>">
            <span style="color:red;">* <?php echo $passwordErr; ?></span>
        </div>

        <div>
            <label>Date of Birth</label>
            <input type="date" name="dob" value="<?php echo $dob; ?>">
            <span style="color:red;">* <?php echo $dobErr; ?></span>
        </div>

        <div>
            <label>Gender</label>
            <input type="radio" name="gender" value="Male" <?php if ($gender == "Male") echo "checked"; ?>> Male
            <input type="radio" name="gender" value="Female" <?php if ($gender == "Female") echo "checked"; ?>> Female
            <span style="color:red;">* <?php echo $genderErr; ?></span>
        </div>

        <div>
            <label>Hobbies</label>
            <input type="checkbox" name="hobbies[]" value="Reading" <?php if (in_array("Reading", $hobbies)) echo "checked"; ?>> Reading
            <input type="checkbox" name="hobbies[]" value="Traveling" <?php if (in_array("Traveling", $hobbies)) echo "checked"; ?>> Traveling
            <input type="checkbox" name="hobbies[]" value="Gaming" <?php if (in_array("Gaming", $hobbies)) echo "checked"; ?>> Gaming
            <input type="checkbox" name="hobbies[]" value="Sports" <?php if (in_array("Sports", $hobbies)) echo "checked"; ?>> Sports
            <span style="color:red;">* <?php echo $hobbiesErr; ?></span>
        </div>

        <div>
            <label>City</label>
            <select name="city">
                <option value="">Select City</option>
                <option value="Mumbai" <?php if ($city == "Mumbai") echo "selected"; ?>>Mumbai</option>
                <option value="Delhi" <?php if ($city == "Delhi") echo "selected"; ?>>Delhi</option>
                <option value="Bangalore" <?php if ($city == "Bangalore") echo "selected"; ?>>Bangalore</option>
                <option value="Hyderabad" <?php if ($city == "Hyderabad") echo "selected"; ?>>Hyderabad</option>
            </select>
            <span style="color:red;">* <?php echo $cityErr; ?></span>
        </div>

        <div>
            <label>State</label>
            <select name="state">
                <option value="">Select State</option>
                <option value="Maharashtra" <?php if ($state == "Maharashtra") echo "selected"; ?>>Maharashtra</option>
                <option value="Karnataka" <?php if ($state == "Karnataka") echo "selected"; ?>>Karnataka</option>
                <option value="Telangana" <?php if ($state == "Telangana") echo "selected"; ?>>Telangana</option>
                <option value="Delhi" <?php if ($state == "Delhi") echo "selected"; ?>>Delhi</option>
            </select>
            <span style="color:red;">* <?php echo $stateErr; ?></span>
        </div>

        <div>
            <label>Country</label>
            <select name="country">
                <option value="">Select Country</option>
                <option value="India" <?php if ($country == "India") echo "selected"; ?>>India</option>
                <option value="USA" <?php if ($country == "USA") echo "selected"; ?>>USA</option>
                <option value="UK" <?php if ($country == "UK") echo "selected"; ?>>UK</option>
                <option value="Canada" <?php if ($country == "Canada") echo "selected"; ?>>Canada</option>
            </select>
            <span style="color:red;">* <?php echo $countryErr; ?></span>
        </div>

        <div>
            <label>Profile Image</label>
            <input type="file" name="profile_image" value ="<?php echo $profile_image ?>">
        </div>

        <div>
            <label>About You</label>
            <textarea name="about" rows="4"><?php echo $about; ?></textarea>
            <span style="color:red;">* <?php echo $aboutErr; ?></span>
        </div>

        <div>
            <button type="submit">Submit</button>
        </div>
    </form>

    <h2>User List</h2>
<?php
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>Date of Birth</th>
                <th>Gender</th>
                <th>Hobbies</th>
                <th>City</th>
                <th>State</th>
                <th>Country</th>
                <th>Profile Image</th>
                <th>About</th>
                <th>Actions</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['id'] . "</td>
                <td>" . $row['name'] . "</td>
                <td>" . $row['email'] . "</td>
                <td>" . $row['password'] . "</td>
                <td>" . $row['dob'] . "</td>
                <td>" . $row['gender'] . "</td>
                <td>" . $row['hobbies'] . "</td>
                <td>" . $row['city'] . "</td>
                <td>" . $row['state'] . "</td>
                <td>" . $row['country'] . "</td>
                <td><img src='" . $row['profile_image'] . "' width='50' height='50'></td>
                <td>" . $row['about'] . "</td>
                <td>
                    <a href='update.php?id=" . $row['id'] . "'>Edit</a> | 
                    <a href='delete.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                </td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "No records found.";
}
?>

</body>
</html>


<!-- delete.php -->

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $conn = new mysqli("localhost", "root", "", "registration_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete query
    $sql = "DELETE FROM users WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
            alert('Record deleted successfully')
            window.location.href = 'index.php';
        </script>";
        
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>



<!-- update.php -->

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $conn = new mysqli("localhost", "root", "", "registration_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $hobbies = implode(", ", $_POST['hobbies']);
        $city = $_POST['city'];
        $state = $_POST['state'];
        $country = $_POST['country'];
        $about = $_POST['about'];

        $profile_image = $user['profile_image']; 
        if ($_FILES['profile_image']['name']) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (in_array($imageFileType, ['jpg', 'png', 'jpeg'])) {
                if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                    $profile_image = $target_file; 
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "Only JPG, JPEG, and PNG files are allowed.";
            }
        }

        $sql_update = "UPDATE users SET name='$name', email='$email', dob='$dob', gender='$gender', hobbies='$hobbies', city='$city', state='$state', country='$country', about='$about', profile_image='$profile_image' WHERE id=$id";
        
        if ($conn->query($sql_update) === TRUE) {
            echo "<script>
            alert('Record updated successfully')
            window.location.href = 'index.php';
        </script>";
        } else {
            echo "Error: " . $sql_update . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>

<form method="POST" action="" enctype="multipart/form-data">
    <label>Name:</label>
    <input type="text" name="name" value="<?php echo $user['name']; ?>"><br>
    
    <label>Email:</label>
    <input type="email" name="email" value="<?php echo $user['email']; ?>"><br>

    <label>Date of Birth:</label>
    <input type="date" name="dob" value="<?php echo $user['dob']; ?>"><br>

    <label>Gender:</label>
    <input type="radio" name="gender" value="Male" <?php echo ($user['gender'] == 'Male') ? 'checked' : ''; ?>> Male
    <input type="radio" name="gender" value="Female" <?php echo ($user['gender'] == 'Female') ? 'checked' : ''; ?>> Female<br>

    <label>Hobbies:</label>
    <input type="checkbox" name="hobbies[]" value="Reading" <?php echo (in_array("Reading", explode(", ", $user['hobbies']))) ? 'checked' : ''; ?>> Reading
    <input type="checkbox" name="hobbies[]" value="Traveling" <?php echo (in_array("Traveling", explode(", ", $user['hobbies']))) ? 'checked' : ''; ?>> Traveling
    <input type="checkbox" name="hobbies[]" value="Gaming" <?php echo (in_array("Gaming", explode(", ", $user['hobbies']))) ? 'checked' : ''; ?>> Gaming
    <input type="checkbox" name="hobbies[]" value="Sports" <?php echo (in_array("Sports", explode(", ", $user['hobbies']))) ? 'checked' : ''; ?>> Sports<br>

    <label>City:</label>
    <select name="city">
        <option value="Mumbai" <?php echo ($user['city'] == 'Mumbai') ? 'selected' : ''; ?>>Mumbai</option>
        <option value="Delhi" <?php echo ($user['city'] == 'Delhi') ? 'selected' : ''; ?>>Delhi</option>
        <option value="Bangalore" <?php echo ($user['city'] == 'Bangalore') ? 'selected' : ''; ?>>Bangalore</option>
        <option value="Hyderabad" <?php echo ($user['city'] == 'Hyderabad') ? 'selected' : ''; ?>>Hyderabad</option>
    </select><br>

    <label>State:</label>
    <select name="state">
        <option value="Maharashtra" <?php echo ($user['state'] == 'Maharashtra') ? 'selected' : ''; ?>>Maharashtra</option>
        <option value="Karnataka" <?php echo ($user['state'] == 'Karnataka') ? 'selected' : ''; ?>>Karnataka</option>
        <option value="Telangana" <?php echo ($user['state'] == 'Telangana') ? 'selected' : ''; ?>>Telangana</option>
        <option value="Delhi" <?php echo ($user['state'] == 'Delhi') ? 'selected' : ''; ?>>Delhi</option>
    </select><br>

    <label>Country:</label>
    <select name="country">
        <option value="India" <?php echo ($user['country'] == 'India') ? 'selected' : ''; ?>>India</option>
        <option value="USA" <?php echo ($user['country'] == 'USA') ? 'selected' : ''; ?>>USA</option>
        <option value="UK" <?php echo ($user['country'] == 'UK') ? 'selected' : ''; ?>>UK</option>
        <option value="Canada" <?php echo ($user['country'] == 'Canada') ? 'selected' : ''; ?>>Canada</option>
    </select><br>

    <label>About You:</label>
    <textarea name="about"><?php echo $user['about']; ?></textarea><br>

    <label>Profile Image</label>
    <?php if ($user['profile_image']): ?>
        <div>
            <img src="<?php echo $user['profile_image']; ?>" width="100" height="100">
        </div>
    <?php endif; ?>
    <input type="file" name="profile_image"><br>

    <button type="submit">Update</button>
</form>


<!-- create table  -->

<!-- create a table  -->

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    dob DATE NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    city VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    country VARCHAR(50) NOT NULL,
    profile_image VARCHAR(255),
    about TEXT,
    hobbies VARCHAR(255)
);