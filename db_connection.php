<?php
$conn = new mysqli("localhost", "root", "", "employee");
if ($conn->connect_error) {
	die("<p class='error'>Connection Failed: " . $conn->connect_error . "</p>");
}
?>