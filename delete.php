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
