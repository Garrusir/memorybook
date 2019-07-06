<?php
require "connect.php";
require "functions.php";
$result = mysqli_query($connect, "SELECT email FROM settings");
$myrow = mysqli_fetch_array($result);
?>

<?php 
$errors = Array();
$send = false;
if (isset($_POST['send'])) {
	$email = $_POST['email'];
	$to  = $myrow['email'];

	
	if (check_email($email) == false) {
		$errors[] = "Не верный email адрес";}
	$subject = "Обратная связь"; 
	
	$message = '<p>От: '.clean_data($_POST['name']).'</p><p>'.strip_tags($_POST['text'])."</p><p>Email: ".$email."</p>";
	
	$headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
	$headers .= "From: $email \r\n"; 
	$headers .= "Reply-To: reply-to@example.com\r\n"; 
	if (empty($errors)) {
		$send = mail($to, $subject, $message, $headers); 
	}
	


}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Обратная связь</title>
	<link rel="stylesheet" type="text/css" href="lib/bootstrap-3.3.7-dist/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta charset="utf-8">
</head>
<body>
<div class="container">
<div class="header row">
	 <?php require 'blocks/header.php'; ?>
</div>


<div class="content row">
	<div class="col-md-3 ">
		<?php require 'blocks/sidebar.php';?>
	</div>
	<div class="col-md-9">
		<div class="row" style="margin-bottom: 100px;">
			<div >
				<?php
				if ($send ){
					print_info("Сообщение отправлено!");
				} else if(isset($_POST['send'])) $errors[] = "Сообщение не отправлено";
				if (!empty($errors)) {
		print_error($errors);
	}

				 ?>
			</div>
			<div class="text col-md-12">

				<h1>Обратная связь</h1>
				<p>
				НП «Землячество Кузнецкого района» проведена большая работа по сверке печатных и электронных баз данных, воспоминаний очевидцев. Однако, остались вопросы. Возможно, кто-то остался не учтенным или, в силу технических ошибок военных писарей, изменил  звучание фамилии и (или) имени, отчества. Не у всех известны года и место  рождения.
				</p>
				<p>
О замеченной недостоверной информации просим нам сообщить. 
				</p>
				<p>
Спасибо за помощь!
				</p>
			
			</div>

				<div class="feedback-form col-md-10 col-md-offset-1 ">
					<div class="row">
					<form action="feedback.php" method="POST">
						<label class="col-md-12"><span>Ваше  имя:</span><input type="text" name="name"></label>
						<label class="col-md-12"><span>E-mail для ответа:</span><input type="email" name="email" required></label>
						<label class="col-md-12"><span>Ваше сообщение:</span><textarea  name="text" rows="20" required></textarea></label>
						<input class="col-md-12 button" type="submit" name="send" >
					</form>
					</div>
				</div>
		</div>
			
	</div>
</div>
</div>
	<?php require 'blocks/footer.php';?>
</body>
</html>