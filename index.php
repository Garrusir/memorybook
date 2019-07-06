<?php
require "connect.php";
require "functions.php";


if(isset($_GET['ajax']) && $_GET['ajax'] == true ) {


	$articles_on_page = count_articles_on_admin_page($connect);



	$page = Page();
	$count_page = Count_page($articles_on_page);

	$str = "SELECT * FROM mb2 ORDER BY surname  ASC, name ASC LIMIT ".($page-1)*$articles_on_page.", ".$articles_on_page;
	$result = mysqli_query($connect,$str) or die (mysqli_error());
	$myrow = mysqli_fetch_array($result);
	?>

		<div class="content-table">
			<?php
			$n = ($page-1)*$articles_on_page+1;
			print_result($myrow, $result,$n);
			?>
		</div>
		<div class="pagination row">
			<div class="pagin-wrap"><?php 	Pagination($count_page, $page, $articles_on_page, $result, $myrow); ?></div>
		</div>

<?php

} else {
?>
<!DOCTYPE html>
<html>
<head>
	<title>Книга памяти Кузнецкого района Пензенской области</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="main.js"></script>
	<link rel="stylesheet" type="text/css" href="lib/font-awesome-4.7.0/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="lib/bootstrap-3.3.7-dist/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta charset="utf-8">


</head>
<body>
<div class="container">
<div class="header row">
 <?php require 'blocks/header.php'; ?>
</div>

<div class="slider row ">
	<div class="col-md-12">
	<img src="img/slider1.jpg" class="img-responsive">
	</div>

</div>
<div class="content row">
	<div class="col-md-3 " style="margin-bottom: 100px; ">
		<?php require 'blocks/sidebar.php';?>

	</div>
	<div class="col-md-9">
			<div class="text">
				
				<h1>Книга памяти Кузнецкого района Пензенской области</h1>
				<p>
				Вашему вниманию предлагается электронный вариант Книги Памяти Кузнецкого района Пензенской области. В ней  упомянуты 5739 фамилии  наших земляков, не вернувшихся домой после окончания  Великой Отечественной войны. 

				</p>
				<p>
				Выпуск  этой Книги стал возможным благодаря инициативе  НП «Землячество Кузнецкого района»  и неравнодушным людям. Для сбора информации и фотографий  наших бойцов и офицеров, была осуществлена поездка в Центральный Архив Министерства Обороны РФ (г.Подольск). При составлении алфавитного списка выполнен значительный объем  всевозможных сверок и уточнений. 
				</p>
			</div>
			<div class="row search-form">
				<form action="search.php" method="GET">
				<input class="col-md-9 search-field" id="name" name="words" type="text" required placeholder="Поиск">

				<input  class="button test col-md-3" type="submit" id='send' name="search" value="Найти">
			
				<label for="send"><i class="fa fa-search button " aria-hidden="true"></i></label>
				</form>
			</div>
<!-- 			<div class="fullsearch">
				<form action="search.php" method="GET">
				<input  type="text"	name="surname" placeholder="Фамилия">
				<input  type="text"	name="name" placeholder="Имя">
				<input  type="text"	name="patronymic" placeholder="Отчество">
				<input type="text" name="place" placeholder="Место рождения">
				<input type="text" name="db" placeholder="Год рождения">
				<input type="text" name="dd" placeholder="Год смерти">
				<input type="submit" name="fullsearch" value="Найти">
				</form>
			</div> -->
			<?php

	$articles_on_page = count_articles_on_admin_page($connect);



	$page = Page();
	$count_page = Count_page($articles_on_page);

	$str = "SELECT * FROM mb2 ORDER BY surname  ASC, name ASC LIMIT ".($page-1)*$articles_on_page.", ".$articles_on_page;
	$result = mysqli_query($connect,$str) or die (mysqli_error());
	$myrow = mysqli_fetch_array($result);
	?>
	<div class="ajax-content">
		<div class="content-table">
			<?php
			$n = ($page-1)*$articles_on_page+1;
			print_result($myrow, $result,$n);
			?>
		</div>
		<div class="pagination row">
			<div class="pagin-wrap"><?php 	Pagination($count_page, $page, $articles_on_page, $result, $myrow); ?></div>
		</div>
	</div>
</div>
</div>
</div>
	<?php require 'blocks/footer.php';?>

</body>
</html>
<?php
}
 ?>