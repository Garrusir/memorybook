<?php
session_start();
if(!is_login()) {
		header("Location: login.php");
 }

?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title ?></title>
	<link rel="stylesheet" type="text/css" href="admin-style.css">
	<link rel="stylesheet" type="text/css" href="../lib/font-awesome-4.7.0/css/font-awesome.css">
	<meta charset="utf-8">
</head>