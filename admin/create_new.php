<?php
require "../connect.php";
require "../functions.php";
$title = 'Новая запись';
require "header.php";
 ?>
<body>
<?php require 'adminbar.php'; ?>
<div class="content">

<h1>Новая запись</h1>
<?php

$errors = array();

if ( isset($_POST['create_new'])) {
	$surname = clean_data($_POST['surname']);
	$name = clean_data($_POST['name']);
	$patronymic = clean_data($_POST['patronymic']);
	$place = clean_data($_POST['place']);
	$db = clean_data($_POST['db']);
	$dd = clean_data($_POST['dd']);
	
	$str = "INSERT INTO mb2(surname,name,patronymic,place,db,dd) VALUES ('$surname','$name', '$patronymic', '$place', '$db', '$dd') ";
	$result = mysqli_query($connect, $str) or die (mysqli_error('Ошибка записи: '));
	if($result) echo "<h1>Добавлено!</h1>";
}

?>
<div class="create_new">
<form action="create_new.php" method="POST">
	<label> Фамилия <input type="text" name="surname"></label>
	<label> Имя <input type="text" name="name"></label>
	<label> Отчество <input type="text" name="patronymic"></label>
	<label> Место <input type="text" name="place"></label>
	<label> Дата рождения <input type="text" name="db"></label>
	<label> Дата смерти<input type="text" name="dd"></label>
	<input type="submit" name="create_new" value="Сохранить">
</form>
</div>
</div>
</body>
</html>