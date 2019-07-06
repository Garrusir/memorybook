<?php
require "../connect.php";
$title = "Настройки";
require "../functions.php";
require "header.php";
?>
<?php

if (isset($_GET['save'])) {

	$count_art_on_page = $_GET['count_art_on_page'];
	$count_art_on_admin_page = $_GET['count_art_on_admin_page'];
	$email = $_GET['email'];

	if ((int)$count_art_on_page < 1 ) {
		$count_art_on_page = 1;
	}
	if ((int)$count_art_on_admin_page < 1) {
		$count_art_on_admin_page = 1;
	}
	$result = mysqli_query($connect, "UPDATE settings SET art_on_page='$count_art_on_page', art_on_admin_page = '$count_art_on_admin_page', email ='$email' ") or die ('Ошибка MySQL : ');
	$is_saved = true;

}
$result = mysqli_query($connect , "SELECT * FROM settings");
$myrow = mysqli_fetch_array($result);

?>
<body>
<?php require 'adminbar.php'; ?>
	<div class="content">
	<div class="message">
		<?php
			if (isset($is_saved)) {
				echo "Сохранено!";
			}
		?>
	</div>

	<div>
		<h3>Общие настройки</h3>
		<form action="options.php">
		<table>
		<tr> 
		<td><label for="count_art_on_page">Отображать на странице поиска не более </label></td> <td><input type="number" name="count_art_on_page" id="count_art_on_page" value="<?php echo $myrow['art_on_page'] ?>"> записей </td>
		</tr>
		<tr> 
		<td><label for="count_art_on_admin_page">Отображать на админ-панели не более </label></td> <td><input type="number" name="count_art_on_admin_page" id="count_art_on_admin_page" value="<?php echo $myrow['art_on_admin_page'] ?>"> записей </td>
		</tr>
		<tr> 
		<td><label for="count_art_on_admin_page">Почта для обратной связи </label></td> <td><input type="email" name="email" id="email" value="<?php echo $myrow['email'] ?>"> записей </td>
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