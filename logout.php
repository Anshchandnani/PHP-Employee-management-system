<?php
	session_start();
	session_destroy();
	header('refresh: 2; url=login.php');
	echo 'You will be redirected to Login page in 2 seconds';
	exit();
?>
