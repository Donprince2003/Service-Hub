<?php
session_start();
include("conn.php");
include("index.php");

if (isset($_POST['submit'])) {
  $username = mysqli_real_escape_string($conn, $_POST['name']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  $sql = "SELECT * FROM user_tab WHERE user_id = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = mysqli_fetch_assoc($result);
    $user_password = $row['user_password'];
    if ($password == $user_password) {
      $role = $row['user_role'];
      $_SESSION['user_role'] = $role;
      $_SESSION['user_id'] = $username;

      echo "<script>alert('login');</script>";
     

      header("Location: home.php");
    } else {
      echo "<script>alert('wrong password!');</script>";
    }
  } else {
    echo "<script>alert('User ID does not exist!');</script>";
  }
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Login Page</title>
    <script>
        function validateEmail() {
            var email = document.forms["loginForm"]["name"].value;
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!re.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <div class="logincontainer">
        <h1>Login</h1>
        <div class="loginbox">
            <form name="loginForm" method="POST" onsubmit="return validateEmail()">
                <input type="text" placeholder="Email" name="name" required>
                <input type="password" placeholder="Password" name="password" required>
        </div>
        <input type="submit" name="submit" value="Login">
        </form>
        <div>
            <p>If you have not Registered <a href="reg.php">click here.</a><br>
            If you have forgot your password <a href="s-check.php">click here.</a></p>.
        </div>
    </div>
</body>

</html>
