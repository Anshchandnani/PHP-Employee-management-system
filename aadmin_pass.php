<?php
	$plain_password = "admin@456";
	$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
	echo $hashed_password;
?>
