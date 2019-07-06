<?php
require "../connect.php";
require "../functions.php";
$title = "Галерея";
require "header.php";
?>
<?php
	$errors = array();
		$upload_dir = $_SERVER['DOCUMENT_ROOT']."/mb/uploads/";
	$thumbs_upload_dir = $_SERVER['DOCUMENT_ROOT']."/mb/uploads/thumbs/";
	// $upload_dir = $_SERVER['DOCUMENT_ROOT']."/uploads/";
	// $thumbs_upload_dir = $_SERVER['DOCUMENT_ROOT']."/uploads/thumbs/";

 if (isset($_GET['action']) && $_GET['action'] == 'delete') {
 	delete_image($_GET['id'],$upload_dir,$thumbs_upload_dir );
 }

if (isset($_GET['action']) && $_GET['action'] == 'save') {
	$id_img = $_GET['id_img'];
 	$n_name = $_GET['name'];
 	$n_alt = $_GET['alt'];
 	$id_alb = $_GET['id_alb'];
 	$result = mysqli_query($connect, "UPDATE images SET name='$n_name', alt = '$n_alt', id_alb = '$id_alb' WHERE id_img LIKE $id_img") or die ('Ошибка MySQL : ');
 	$is_saved = true;

 }

 if (isset($_GET['action']) && $_GET['action'] == 'edit') {
 	if (isset($_GET['id'])) {
 							
 							$id_img = clean_data($_GET['id']);
 							
							$is_edit = true;
							
						} 
 }


if (isset($_FILES['img']) && isset($_POST['upload'])) {
	$size = 8;
	$extensions = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif');

	
	//check_image($_FILES['img'], $size,$extensions);
	// if (!empty($errors)) {
	// 	print_error();
	// }
	

	$img_orig_name = $_FILES['img']['name'];
	$thumb_orig_name = "thumb_".$_FILES['img']['name'];

	$img_name = change_img_name($upload_dir,$img_orig_name);

	$thumb_name = change_img_name($thumbs_upload_dir, $thumb_orig_name);

	

	resize($_FILES['img'],$thumb_name, $thumbs_upload_dir);
	
	

 	 // echo "<br> _FILES['img'] ===== ".var_dump($_FILES['img']);
 	// echo "<br> img_name = ". ($img_name)."<br>";
 	// echo "<br> thumb_name = ".($thumb_name);
 	// echo "<br> upload_dir = ".($upload_dir);
 	// echo "<br> thumbs_upload_dir = ".($thumbs_upload_dir);

	upload_image($_FILES['img'],$img_name,$thumb_name,$upload_dir);



	
		


 


	if(!empty($errors)) print_error($errors) ;



}


?>

<!-- <!DOCTYPE html>
<html>
<head>
	<title>Галерея</title>
	<link rel="stylesheet" type="text/css" href="admin-style.css">
	<link rel="stylesheet" type="text/css" href="../font-awesome-4.7.0/css/font-awesome.css">
	<meta charset="utf-8">
	<script>
		window.onload = function(){
		console.log('test');
		click = document.getElementById('test');
		click.onclick = function() {
			divbg = document.createElement('div');
			divbg.className = "bg";
			divbg.onclick = function() {
				document.body.removeChild(divbg);
			};
			document.body.appendChild(divbg);
		}
	}
	</script>
	<style>
		.bg {
			position: fixed;
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;
			background-color: #000;
			opacity: .7;
			min-height: 360px;
			z-index: 159900
		}
	</style>
</head> -->
<body>
  <?php require 'adminbar.php'; ?>  
	<div class="content">
	<div class="message">
		<?php
			if (isset($is_saved)) {
				print_info("Сохранено");
			 }
		?>
	</div>

	<div class="admin-gallery">
		<h2>Галерея изображений</h2>
		<p>Загрузить изображение:</p>
		<form action="upload.php" method="post" enctype="multipart/form-data">
		<input type="file" name="img">
		<input type="submit" name="upload" value="Загрузить">
		</form>
		<?php	


if (isset($is_edit)) {

	$result = mysqli_query($connect, "SELECT * FROM images WHERE id_img LIKE '$id_img'");
	
	if(mysqli_num_rows($result)>0) {
		$myrow = mysqli_fetch_array($result);
		$img_path = $_SERVER["HTTP_HOST"]."/uploads/".$myrow['path']; 
		echo "
								<div class='post-img'>
									<h3>Изменить картинку</h3>
									<form action='upload.php' method='GET'>
									<input type='hidden' name='action' value='save'>
									<input type='hidden' name='id_img' value='".$myrow['id_img']."'>
									<input type='text' name='name' value='".$myrow['name']."'>
									<div>
									Ссылка на файл: <a href='/uploads/".$myrow['path']."' target='_blank'>$img_path</a>
									</div>
									<div class='full-img'>
										<img src='../uploads/".$myrow['path']."'>
									</div>
									<div>
										<label> Атрибут alt
											<input type='text' name='alt' value='".$myrow['alt']."'>
										</label>
									</div>
									<div>
										<label> Альбом
											<select  name='id_alb'>
												<option value='1' "; if($myrow['id_alb'] == 1) echo " selected";
												echo ">Памятники</option>
												<option value='2' "; if($myrow['id_alb'] == 2) echo " selected" ;
												echo ">Ветераны</option>
											</select>
										</label>
									</div>
										<input type='submit' value='Сохранить' >
									</form>

								</div>
							";
	} else {
		echo "<div>
		<p>Пусто</p>
		</div>";
	}
}
?>

<div class="lib-img">
<h3>Библиотека изображений</h3>
<?php


						$result = mysqli_query($connect, "SELECT * FROM images"); 
						if(mysqli_num_rows($result)>0) {
							$myrow = mysqli_fetch_array($result);
							do {
								echo "
								<div class='img-row'>
									
									<div class='img-wrap' id='test'>
										<img src='../uploads/thumbs/".$myrow['thumb_path']."' >
									</div>
									<div class='img-data'>
										<div class='img-name'><span>".$myrow['name']."</span></div>
										<div class='img-edit'><span><a  href='?action=edit&id=".$myrow['id_img']."'>Изменить</a> </span></div>
										<div class='img-del'><span><a href='?action=delete&id=".$myrow['id_img']."'>Удалить</a> </span></div>
									</div>
								</div>
								"; 
							}
							while ($myrow = mysqli_fetch_array($result));
						} else {
							echo "<div class='no-media' >
							<p>Изображения отсутствуют</p>
							</div>";
						}
						?>
</div>
	</div>
	</div>
</body>
</html>