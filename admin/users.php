<?php 
require "../connect.php";
require "../functions.php";
$title = "Смена пароля";
require "header.php";
$str = "SELECT * FROM users WHERE id = '".$_SESSION['userid']."' ";
$result = mysqli_query($connect, $str);
$myrow = mysqli_fetch_array($result);
$errors = array();
if (isset($_POST['save'])) {

	$password = clean_data($_POST['password']);
	$new_password = clean_data($_POST['new_password']);
	$repeat_password = clean_data($_POST['repeat_password']);

	if ($new_password != $repeat_password) {
		$errors[] = "Пароли должны совпадать";
	}

	if ($password != $myrow['password']) {
		$errors[] = "Неверный пароль";
	}

	if (empty($errors)) {
		$new_password = md5($new_password);
		$result = mysqli_query($connect, "UPDATE users SET password = '$new_password' WHERE id LIKE '1'");
		if ($result) $info = "Сохранено"; else $errors[] = "Ошибка записи MySQL";
	} 
}




?>

<body>
<?php require 'adminbar.php' ?>
<div class="content">
	

<?php 
	if(!empty($errors)){ print_error($errors); }
		elseif(!empty($info)) { print_info($info);}
	?>

<div class="user-options">
	<form action="users.php" method="POST">
		<table>
			<tr> 
				<td><label for="login">Логин </label></td>
				<td><input type="text" name="login" id="login" value="<?php echo $myrow['login'] ?> " disabled="disabled"> <span >Логин не может быть изменен</span> </td>				
			</tr>
			<tr> 
				<td><label for="password">Старый пароль </label></td>
				<td><input type="password" name="password" id="password"  > </td>				
			</tr>
			<tr> 
				<td><label for="password">Новый пароль </label></td>
				<td><input type="password" name="new_password" id="password"  > </td>				
			</tr>
			<tr> 
				<td><label for="password">Повторите пароль </label></td>
				<td><input type="password" name="repeat_password" id="password"  > </td>				
			</tr>
			<tr> 
				<td><input type="submit" name="save" value="Сохранить"></td>			
			</tr>
		</table>
	</form>
</div>
</div>
</body>
</html>