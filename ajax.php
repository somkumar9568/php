<?php
$servername ="localhost";
$username="root";
$password ="";
$dbname ="registerform";


$conn = new mysqli ($servername,$username,$password,$dbname);

if($conn->connect_error){
    die("connection is failded :". $conn->connect_error); 
}else{
    // echo "connection is  succesffully";
}

?>

<!--1 registrepage.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        .error {
            color: red;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <form id="myform">
        <h1>Register Page</h1>

        <label for="fname">First Name</label>
        <input type="text" name="fname" id="fname" placeholder="Enter your first name" value="">
        <div class="error" id="fnameErr"></div>

        <label for="lname">Last Name</label>
        <input type="text" name="lname" id="lname" placeholder="Enter your last name" value="">
        <div class="error" id="lnameErr"></div>

        <label for="date">Date of Birth</label>
        <input type="date" name="date" id="date">
        <div class="error" id="dateErr"></div>

        <label for="address">Address</label>
        <input type="text" name="address" id="address" placeholder="Enter your address">
        <div class="error" id="addressErr"></div>

        <label for="phonenumber">Phone Number</label>
        <input type="text" name="phonenumber" id="phonenumber" placeholder="Enter your phone number">
        <div class="error" id="phonenumberErr"></div>

        <label for="email">Email</label>
        <input type="text" name="email" id="email" placeholder="Enter your email">
        <div class="error" id="emailErr"></div>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter your password">
        <div class="error" id="passwordErr"></div>

        <button type="submit">Register</button>
    </form>

    <script>
    $(document).ready(function () {

        function validateFirstName() {
            let fname = $("#fname").val();
            const regex = /^[a-zA-Z]+$/;
            if (fname === "") {
                $("#fnameErr").text("First Name is required.");
                return false;
            } else if (!regex.test(fname)) {
                $("#fnameErr").text("First Name should contain only letters.");
                return false;
            } else {
                $("#fnameErr").text("");
                return true;
            }
        }

        function validateLastName() {
            let lname = $("#lname").val();
            const regex = /^[a-zA-Z]+$/;
            if (lname === "") {
                $("#lnameErr").text("Last Name is required.");
                return false;
            } else if (!regex.test(lname)) {
                $("#lnameErr").text("Last Name should contain only letters.");
                return false;
            } else {
                $("#lnameErr").text("");
                return true;
            }
        }

        function validateDateOfBirth() {
            let dob = $("#date").val();
            if (dob === "") {
                $("#dateErr").text("Date of Birth is required.");
                return false;
            } else {
                let birthDate = new Date(dob);
                let age = new Date().getFullYear() - birthDate.getFullYear();
                if (age < 18) {
                    $("#dateErr").text("You must be at least 18 years old.");
                    return false;
                } else {
                    $("#dateErr").text("");
                    return true;
                }
            }
        }

        function validatePhoneNumber() {
            let phone = $("#phonenumber").val();
            const regex = /^[0-9]{10}$/;
            if (phone === "") {
                $("#phonenumberErr").text("Phone number is required.");
                return false;
            } else if (!regex.test(phone)) {
                $("#phonenumberErr").text("Phone number must be 10 digits.");
                return false;
            } else {
                $("#phonenumberErr").text("");
                return true;
            }
        }

        function validateEmail() {
            let email = $("#email").val();
            const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            if (email === "") {
                $("#emailErr").text("Email is required.");
                return false;
            } else if (!regex.test(email)) {
                $("#emailErr").text("Enter a valid email.");
                return false;
            } else {
                $("#emailErr").text("");
                return true;
            }
        }

        function validatePassword() {
            let password = $("#password").val();
            const regex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
            if (password === "") {
                $("#passwordErr").text("Password is required.");
                return false;
            } else if (!regex.test(password)) {
                $("#passwordErr").text("Password must be at least 8 characters, and contain both letters and numbers.");
                return false;
            } else {
                $("#passwordErr").text("");
                return true;
            }
        }

        function validateAddress() {
            let address = $("#address").val();
            if (address === "") {
                $("#addressErr").text("Address is required.");
                return false;
            } else {
                $("#addressErr").text("");
                return true;
            }
        }

        $("#fname").on("input", validateFirstName);
        $("#lname").on("input", validateLastName);
        $("#date").on("input", validateDateOfBirth);
        $("#phonenumber").on("input", validatePhoneNumber);
        $("#email").on("input", validateEmail);
        $("#password").on("input", validatePassword);
        $("#address").on("input", validateAddress);

        $("#myform").on("submit", function (e) {
            e.preventDefault();

            if (validateFirstName() && validateLastName() && validateDateOfBirth() &&
                validatePhoneNumber() && validateEmail() && validatePassword() && validateAddress()) {

                $.ajax({
                    url: "insert.php",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.trim() === "duplicate") {  
                            alert("This entry already exists.");
                            $("#myform input").prop("disabled", false);  
                        } else if (response.trim() === "Registration successful!") {
                            alert("Registration Successful!");
                            $("#myform")[0].reset(); 
                            $(".error-message").text("");  
                        } else {
                            alert("Failed to insert data. Please try again.");
                        }
                    },
                    error: function () {
                        alert("Error submitting form");
                    }
                });
            } else {
                alert("Please fix the errors in the form before submitting.");
            }
        });


    });
    </script>
</body>
</html>


<!--2 insert.php -->


<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $address = $_POST['address'];
    $phonenumber = $_POST['phonenumber'];
    $date = $_POST['date'];

    $check_email_query = "SELECT * FROM user WHERE email = '$email'";
    $result_email = $conn->query($check_email_query);

    if ($result_email->num_rows > 0) {
        echo "duplicate";
        exit();
    }

    $query = "INSERT INTO user (fname, lname, email, password, address, phonenumber, date) 
              VALUES ('$fname', '$lname', '$email', '$password', '$address', '$phonenumber', '$date')";

    if ($conn->query($query)) {
        echo "Registration successful!";
    } else {
        echo "Failed to insert data.";
    }

    $conn->close();
}
?>



<!--3 loging.php -->

<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password']; 

    $query = "SELECT * FROM user WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if ($password == $user['password']) { 
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $user['id']; 
            header("Location: welcome.php");
            exit;
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        .error {
            color: red;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <form id="loginForm" method="POST">
        <h1>Login</h1>

        <label for="email">Email</label>
        <input type="text" name="email" id="email" placeholder="Enter your email"><br>
        <div class="error" id="emailErr"></div>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter your password"><br>
        <div class="error" id="passwordErr"></div>

        <button type="submit">Login</button>
    </form>

    <script>
    $(document).ready(function () {

        function validateEmail() {
            let email = $("#email").val();
            const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            if (email === "") {
                $("#emailErr").text("Email is required.");
                return false;
            } else if (!regex.test(email)) {
                $("#emailErr").text("Please enter a valid email address.");
                return false;
            } else {
                $("#emailErr").text("");
                return true;
            }
        }

        function validatePassword() {
            let password = $("#password").val();
            if (password === "") {
                $("#passwordErr").text("Password is required.");
                return false;
            } else if (password.length < 6) {
                $("#passwordErr").text("Password must be at least 6 characters.");
                return false;
            } else {
                $("#passwordErr").text(""); 
                return true;
            }
        }

        $("#email").on("input", validateEmail);
        $("#password").on("input", validatePassword);

        $("#loginForm").on("submit", function (e) {
            e.preventDefault(); 
            if (validateEmail() && validatePassword()) {
                this.submit();
            } else {
                alert("Please fix the errors before submitting.");
            }
        });

    });
    </script>
    
</body>
</html>


<!--4  welcome.php -->

<?php
session_start();
include 'config.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM user WHERE id = $id";
    if ($conn->query($deleteQuery)) {
        echo "<script>alert('User deleted successfully!');
         window.location='welcome.php';</script>";
    } else {
        echo "<script>alert('Error deleting user!');</script>";
    }
}

$query = "SELECT * FROM user";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this user?")) {
                window.location = "welcome.php?delete=" + id;
            }
        }
    </script>
</head>
<body>

<h2>Welcome, <?php echo $_SESSION['email']; ?></h2>
<a href="logout.php">Logout</a>

<h3>All Users</h3>
<table border="1">
    <tr>
        <th>ID</th>
        <th>fName</th>
        <th>lName</th>
        <th>Phone Number</th>
        <th>Email</th>
        <th>Password</th>
        <th>Address</th>
        <th>Date</th>
        <th>Update</th>
        <th>Delete</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['fname']; ?></td>
        <td><?php echo $row['lname']; ?></td>
        <td><?php echo $row['phonenumber']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['password']; ?></td>
        <td><?php echo $row['address']; ?></td>
        <td><?php echo $row['date']; ?></td>
        <td>
            <a href="update.php?id=<?php echo $row['id']; ?>">Edit</a>
        </td>
        <td>
            <button onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</button>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>


<!--5 logout.php -->

<?php
session_start();
session_destroy();
header("Location: loging.php");
exit;
?>



?>