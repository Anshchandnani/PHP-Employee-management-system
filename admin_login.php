<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
     body {font-family: Arial, sans-serif;margin: 0;padding: 0;background-color: #f4f4f4;}
     header {background: #35424a;color: #ffffff;padding: 20px 0;text-align: center;}
	 nav ul {list-style: none;padding: 0;text-align: center;background-color: #35424a;margin: 0;}
	 nav ul li {display: inline-block;margin: 0 15px;}
	 nav ul li a {color: #ffffff;text-decoration: none;font-weight: bold;}
     main {padding: 20px;text-align: center;}
     input[type="text"], input[type="password"] {padding: 8px;width: 250px;}
     input[type="submit"] {padding: 8px 16px;background-color: #35424a;color: white;border: none;cursor: pointer;}
     input[type="submit"]:hover {background-color: #222;}
     footer {text-align: center;padding: 5px 0;background: #35424a;color: #ffffff;margin-top: 30px;}
    </style>
    <script>
        function validateForm() {
            let admin_id = document.getElementById("admin_id").value.trim();
            let password = document.getElementById("password").value.trim();
            let errors = [];

            // HTML5 attributes already enforce required, but additional check here.
            if (admin_id === "" || password === "") {
                errors.push("Please fill in all fields.");
            }
            // Check that admin_id is numeric and specifically 1 or 2.
            if (!/^(1|2)$/.test(admin_id)) {
                errors.push("Admin ID must be numeric and only 1 or 2 are allowed.");
            }
            // Password must be at least 6 characters.
            if (password.length < 6) {
                errors.push("Password must be at least 6 characters long.");
            }

            if (errors.length > 0) {
                alert(errors.join("\n"));
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

<header>
    <h1>Admin Login Dashboard</h1>
</header>

<nav>
    <ul>
        <li><a href="admin_login.php">Admin</a></li>
        <li><a href="login.php">Employee</a></li>
    </ul>
</nav>

<main>
    <h2>Admin Login Page</h2>
    <form action="" method="POST" onsubmit="return validateForm()">
        <label for="admin_id">Admin ID:</label><br>
        <!-- Using HTML5 validation: required and pattern restricted to 1 or 2 -->
        <input type="text" id="admin_id" name="admin_id" 
               value="<?= isset($_COOKIE['a_id']) ? $_COOKIE['a_id'] : '' ?>" 
               required pattern="^(1|2)$" title="Admin ID must be 1 or 2"><br><br>

        <label for="password">Admin Password:</label><br>
        <!-- HTML5 validation: required and minlength -->
        <input type="password" id="password" name="password" required autocomplete="off" required minlength="6"><br><br>

        <input type="checkbox" name="rememberme" id="rememberme" <?= isset($_COOKIE['a_id']) ? 'checked' : '' ?>>
        <label for="rememberme">Remember Me</label><br><br>

        <input type="submit" value="Login"><br><br>
    </form>
</main>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = trim($_POST['admin_id']);
    $password = trim($_POST['password']);
    $errors = [];

    // Server-side validation: check empty fields.
    if (empty($admin_id) || empty($password)) {
        $errors[] = "Please fill in all fields.";
    }
    // Check that admin_id is exactly 1 or 2.
    if (!preg_match("/^(1|2)$/", $admin_id)) {
        $errors[] = "Admin ID must be numeric and only 1 or 2 are allowed.";
    }
    // Check password length.
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;text-align:center;'>$error</p>";
        }
        exit;
    }

    include 'db_connection.php';

    // Escape admin_id for SQL safety.
    $admin_id = $conn->real_escape_string($admin_id);
    $sql = "SELECT password FROM admins WHERE admin_id = $admin_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_hash = $row['password'];

        if (password_verify($password, $stored_hash)) {
            session_start();
            $_SESSION['user_name'] = 'admin';
            $_SESSION['admin_id'] = $admin_id;

            if (isset($_POST["rememberme"])) {
                setcookie('a_id', $admin_id, time() + (7 * 24 * 60 * 60), "/"); // 7 days
            } else {
                setcookie('a_id', '', time() - 3600, "/");
            }

            echo "<h2 style='text-align:center;color:green;'>Welcome Admin</h2>";
            echo "<p style='text-align:center;'>Redirecting to Admin Home Page...</p>";
            header("refresh:1; url=admin_home.php");
            exit();
        } else {
            echo "<p style='color:red;text-align:center;'>Invalid Password.</p>";
            echo "<script>alert('Invalid Password');</script>";
        }
    } else {
        echo "<p style='color:red;text-align:center;'>Invalid Admin ID.</p>";
        echo "<script>alert('Invalid Admin ID');</script>";
    }

    $conn->close();
}
?>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Employee Management System</p>
</footer>

</body>
</html>