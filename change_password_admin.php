<?php	
	include 'header_admin.php';
	include 'db_connection.php';

	$admin_id = $_SESSION['admin_id'];
	$message = '';
	$error = '';

	function is_alphanumeric($str) {
		return preg_match("/^[A-Za-z0-9@]+$/", $str);
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$current_password = trim($_POST['current_password']);
		$new_password     = trim($_POST['new_password']);
		$confirm_password = trim($_POST['confirm_password']);

		if (strlen($current_password) < 6 || strlen($new_password) < 6 || strlen($confirm_password) < 6) {
			$error = "All passwords must be at least 6 characters long.";
		} elseif (!$current_password || !$new_password || !$confirm_password) {
			$error = "All fields are required.";
		} elseif (!is_alphanumeric($current_password) || !is_alphanumeric($new_password) || !is_alphanumeric($confirm_password)) {
			$error = "Passwords must contain only letters and numbers.";
		} elseif ($new_password !== $confirm_password) {
			$error = "New Password and Confirm Password do not match!";
		} else {
			$query = "SELECT password FROM admins WHERE admin_id = $admin_id";
			$result = $conn->query($query);
			$row = $result->fetch_assoc();

			if ($row && password_verify($current_password, $row['password'])) {
				$hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
				$update = "UPDATE admins SET password = '$hashed_new_password' WHERE admin_id = $admin_id";

				if ($conn->query($update)) {
					$message = "Password changed successfully!";
					echo "<script>alert('$message');</script>";
				} else {
					$error = "Failed to update password. Please try again.";
					echo "<script>alert('$error');</script>";
				}
			} else {
				$error = "Current password is incorrect!";
				echo "<script>alert('$error');</script>";
			}
		}
	}
?>

<html>
<head>
    <title>Change Password</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f2f2f2; padding: 30px; }
        form { max-width: 450px; margin: auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        input[type="password"], input[type="submit"] { width: 97%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc;}
        input[type="submit"] {width: 102%; background-color: #35424a; color: white; font-weight: bold; cursor: pointer;}
        input[type="submit"]:hover {background-color: #222;}
        .message1 { text-align: center; color: red; margin-top: 10px; }
		.message2 { text-align: center; color: green; margin-top: 10px; }
    </style>
    <script>
        function validateForm() {
            const currPass = document.getElementById("current_password").value.trim();
            const newPass = document.getElementById("new_password").value.trim();
            const confPass = document.getElementById("confirm_password").value.trim();
            let errors = [];

            if (currPass === "" || newPass === "" || confPass === "") {
                errors.push("All fields are required.");
            }
            if (currPass.length < 6 || newPass.length < 6 || confPass.length < 6) {
                errors.push("All passwords must be at least 6 characters long.");
            }
            const alphanumRegex = /^[A-Za-z0-9@]+$/;
            if (!alphanumRegex.test(currPass) || !alphanumRegex.test(newPass) || !alphanumRegex.test(confPass)) {
                errors.push("Passwords must contain only letters and numbers.");
            }
            if (newPass !== confPass) {
                errors.push("New Password and Confirm Password do not match!");
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

    <form method="POST" onsubmit="return validateForm()">
        <h2>Admin Change Password</h2>
        <label>Current Password:</label>
        <input type="password" id="current_password" name="current_password" required minlength="6" pattern="[A-Za-z0-9@]+" title="Only letters and numbers allowed.">

        <label>New Password:</label>
        <input type="password" id="new_password" name="new_password" required minlength="6" pattern="[A-Za-z0-9@]+" title="Only letters and numbers allowed.">

        <label>Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required minlength="6" pattern="[A-Za-z0-9@]+" title="Only letters and numbers allowed.">

        <input type="submit" value="Change Password">
    </form>

<?php include 'footer_admin.php'; ?>
</body>
</html>