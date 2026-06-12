<html>
<head>
    <meta charset="UTF-8">
    <title>Employee Login</title>
    <style>
		body{font-family:Arial,sans-serif;margin:0;padding:0;background-color:#f4f4f4}
		header{background:#35424a;color:#ffffff;padding:20px 0;text-align:center}
		nav ul{list-style:none;padding:0;text-align:center;background-color:#35424a;margin:0}
		nav ul li{display:inline-block;margin:0 15px}
		nav ul li a{color:#ffffff;text-decoration:none;font-weight:bold}
		main{padding:20px;text-align:center}
		input[type="text"],input[type="password"]{padding:8px;width:250px}
		input[type="submit"]{padding:8px 16px;background-color:#35424a;color:white;border:none;cursor:pointer}
		input[type="submit"]:hover{background-color:#222}
		footer{text-align:center;padding:30px 0;background:#35424a;color:#ffffff;margin-top:50px}
    </style>
    <script>
        function remember() {
            let remember = document.getElementById("rememberme");
            if (remember.checked) {
                alert("It will store only Employee Id");
            }
            return false;
        }

        function validateForm() {
            let e_id = document.getElementById("e_id").value.trim();
            let password = document.getElementById("password").value.trim();
            let errors = [];

            if (e_id === "" || password === "") {
                errors.push("Please fill in all fields.");
            }
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
    <h1>Employee Login Dashboard</h1>
</header>

<nav>
    <ul>
        <li><a href="admin_login.php">Admin</a></li>
        <li><a href="login.php">Employee</a></li>
    </ul>
</nav>

<main>
    <h2>Employee Login Page</h2>
    <form action="" method="POST" onsubmit="return validateForm()">
        <label for="e_id">Employee Id:</label><br>
        <input type="text" id="e_id" name="e_id" value="<?= isset($_COOKIE['employee_id']) ? $_COOKIE['employee_id'] : ''; ?>"><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" autocomplete="off"><br><br>

        
        <input type="checkbox" name="rememberme" id="rememberme" <?php echo isset($_COOKIE['employee_id']) ? 'checked' : ''; ?>>
        <label for="rememberme">Remember Me</label><br><br>

        <input type="submit" value="Login"><br><br>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $e_id = trim($_POST['e_id']);
        $password = trim($_POST['password']);
        $errors = [];

        // Validation checks
        if (empty($e_id) || empty($password)) {
            $errors[] = "Please fill in all fields.";
        }
        if (!preg_match("/^[0-9]{1,6}$/", $e_id)) {
            $errors[] = "Invalid Employee Id.";
        }
        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters long.";
        }

        if (!empty($errors)) {
            foreach ($errors as $err) {
                echo "<p style='color:red;text-align:center;'>$err</p>";
            }
        } else {
            include 'db_connection.php';

            $e_id = $conn->real_escape_string($e_id);
            $sql = "SELECT * FROM emp_info WHERE emp_id = '$e_id'";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $stored_hash = $row['password'];
                $status = $row['status'];

                if ($status == 0) {
                    echo "<p style='color:red;text-align:center;'>You are currently on leave and cannot log in.</p>";
                } else {
                    if (password_verify($password, $stored_hash)) {
                        session_start();
                        $_SESSION['user_name'] = 'employee';
                        $_SESSION['emp_id'] = $e_id;
                        $_SESSION['name'] = $row['name'];

                        if (isset($_POST["rememberme"])) {
                            setcookie('employee_id', $e_id, time() + (7 * 24 * 60 * 60), "/");
                        } else {
                            setcookie('employee_id', '', time() - 3600, "/");
                        }

                        echo "<h2 style='text-align:center;color:green;'>Welcome Employee</h2>";
                        echo "<p style='text-align:center;'>Redirecting to Employee Home Page...</p>";
                        header("refresh:1; url=employee_home.php");
                        exit();
                    } else {
                        echo "<p style='color:red;text-align:center;'>Invalid Password.</p>";
                    }
                }
            } else {
                echo "<p style='color:red;text-align:center;'>Invalid Employee ID.</p>";
            }

            $conn->close();
        }
    }
    ?>
</main>

<footer>
    &copy; <?php echo date("Y"); ?> Employee Management System
</footer>

</body>
</html>
