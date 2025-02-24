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

        $sql_update = "UPDATE users SET name='$name', email='$email', dob='$dob', gender='$gender', hobbies='$hobbies', city='$city', state='$state', country='$country', about='$about' WHERE id=$id";
        
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

<form method="POST" action="">
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

    <button type="submit">Update</button>
</form>
