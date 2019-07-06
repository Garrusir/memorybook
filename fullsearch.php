<?php
require "connect.php";
require "functions.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Поиск</title>
	<link rel="stylesheet" type="text/css" href="lib/bootstrap-3.3.7-dist/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta charset="utf-8">
</head>
<body>
<div class="container">
<div class="header row">
	<?php require 'blocks/header.php';?>
</div>

<div class="slider row ">
	<div class="col-md-12">
	<img src="img/slider1.jpg">
	</div>
</div>
<div class="content row">
	<div class="col-md-3 " style='margin-bottom: 100px;'>
		<?php require 'blocks/sidebar.php';?>
	</div>
	<div class="col-md-9">
			<div class="text">
				<h1>Расширенный поиск</h1>
				
			</div>
<!-- 			<div class="row search-from">
				<form action="search.php" method="GET">
				<input class="col-md-9 search-field" id="name" name="words" type="text"><input  class="search-button col-md-3" type="submit" name="search" value="Найти">
				</form>
			</div> -->
			<div class="fullsearch row" style="margin-bottom: 100px; ">
				<form action="search.php" method="GET">
				<div class="col-md-12" > <input type="text" name="surname" 		placeholder="Фамилия"		> </div>
				<div class="col-md-12" > <input type="text" name="name" 			placeholder="Имя"			> </div>
				<div class="col-md-12" > <input type="text" name="patronymic" 	placeholder="Отчество"		> </div>
				<div class="col-md-12" > <input type="text" name="place" 		placeholder="Место рождения"> </div>
				<div class="col-md-12" > <input type="number" name="db" 			placeholder="Год рождения"	> </div>
				<div class="col-md-12" > <input type="number" name="dd" 			placeholder="Год смерти"	> </div>
				<div class="col-md-12" > <input type="submit" name="fullsearch" value="Найти" class="button">	</div>
				</form>
			</div> 

</div>
</div>
</div>
	<?php require 'blocks/footer.php';?>
</body>
</html>