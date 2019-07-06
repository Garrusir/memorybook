<?php
require '../connect.php';
require '../functions.php';

session_start();
$errors = array();
if(isset($_POST['action']) && $_POST['action']=='Войти') {
	$login = clean_data($_POST['login']);
	$password = md5(clean_data($_POST['password']));
	if (!$login) $errors[] = 'Вы не ввели имя пользователя';
	if (!$password) $errors[] = 'Вы не ввели пароль';

	if(empty($errors)) {
		$str = "SELECT * FROM users WHERE login = '$login' AND password = '$password'";
		$result = mysqli_query($connect, $str) ;
	
		if (mysqli_num_rows($result) > 0 ) {
	
			$myrow = mysqli_fetch_array($result);
			
	
			$_SESSION['userid'] = $myrow['id'];
			$_SESSION['login'] = $myrow['login'];
			$_SESSION['password'] = $myrow['password'];
			header("Location: index.php");
		} else $errors[] = "Неверная комбинация логин-пароль" ;
	}

}

if (isset($_GET['action']) && $_GET['action']=='logout' && is_login()) {
	   	 session_unset();
    	 session_destroy();
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="admin-style.css">
	<meta charset="utf-8">
</head>
<body class="login-page">

<div class="login">
	<?php if(!empty($errors)) {
	// echo '<div class="login-form login-errors"> <span class="error-title">Ошибка: </span> ';
		print_error($errors); 
	// echo '</div>';
	}
	?>
	<div class="login-form">
		<h1>Авторизация</h1>
		<form action="login.php" method="POST">
			<input type="text" name="login" placeholder="Имя пользователя" value="<?php if(isset($login)) echo $login; if(isset($_SESSION['login'])) echo $_SESSION['login'] ?>" id="login-field">
			<input type="password" name="password" placeholder="Пароль" id="password-field">
			<input type="submit" name="action" value="Войти" >
		</form>
	</div>
</div>
<div class="back-link">
	<a href="../index.php">&#8592 Назад к сайту</a>
</div>
</body>
</html>