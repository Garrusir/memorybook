<?php


require "../connect.php";
require "../functions.php";
session_start();
if(!is_login()) {
		header("Location: login.php");
 }
$title = "Редактировать";

if (isset($_GET['n'])) {
	$n = (int)clean_data($_GET['n']);
	if( $n != 0) {
		$str = "SELECT * FROM mb2 WHERE id = '$n'";
		$result = mysqli_query($connect, $str) or die(mysqli_error("Ошибка выборки из базы данных: "));
		$myrow = mysqli_fetch_array($result);
	} else header("Location: index.php");
} else 	if(isset($_POST['id']) && $_POST['action'] == 'edit' ) {
			$surname = clean_data($_POST['surname']);
			$name = clean_data($_POST['name']);
			$patronymic = clean_data($_POST['patronymic']);
			$place = clean_data($_POST['place']);
			$db = clean_data($_POST['db']);
			$dd = clean_data($_POST['dd']);
			$id = clean_data($_POST['id']);
		
			$str = "UPDATE mb2 SET 	surname = '$surname', 
									name = '$name', 
									patronymic = '$patronymic',  
									place = '$place', 
									db = '$db',
									dd = '$dd'  WHERE id = '$id'";
			echo $str;
			$result = mysqli_query($connect,$str) or die(mysqli_error("Ошибка обновления")); 
			if ($result) {
				$is_saved = true;
			}
			$str = "SELECT * FROM mb2 WHERE id = '$id'";
			$result = mysqli_query($connect, $str) or die(mysqli_error("Ошибка выборки из базы данных: "));
			$myrow = mysqli_fetch_array($result);
} 
 if(isset($n) && $_GET['action'] == 'delete') {
			$del = delete_article($n);
			if ($del) {
				header("Location: index.php");
			} else $errors[] = "Ошибка удаления";
		};

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title ?></title>
	<link rel="stylesheet" type="text/css" href="admin-style.css">
	<link rel="stylesheet" type="text/css" href="../lib/font-awesome-4.7.0/css/font-awesome.css">
	<meta charset="utf-8">
</head>
<body>

<?php

 require 'adminbar.php' ?>
<div class="content">
<?php 
if (isset($is_saved) && $isset == true) {
				print_info("Сохранено");
			}
?>
<div class="create_new">

	<form action="edit.php" method="POST">
						<input type="hidden" name="id" value="<?php echo $myrow['id'] ?>" >
		<label> Фамилия <input type="text" name="surname"	value="<?php echo $myrow['surname'] ?>"></label>
		<label> Имя <input type="text" name="name"	value="<?php echo $myrow['name'] ?>"></label>
		<label> Отчество <input type="text" name="patronymic"	value="<?php echo $myrow['patronymic'] ?>"></label>
		<label> Место <input type="text" name="place"	value="<?php echo $myrow['place'] ?>"></label>
		<label> Дата рождения <input type="text" name="db"	value="<?php echo $myrow['db'] ?>"></label>
		<label> Дата смерти<input type="text" name="dd"	value="<?php echo $myrow['dd'] ?>"></label>
		<input type="hidden" name="action" value="edit">
		<input type="submit" name="create_new" value="Сохранить">
	</form>
</div>
</div>
</body>
</html>