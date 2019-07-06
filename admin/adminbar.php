<div class="adminbar">
	<div class="welcome">
		<span> Привет, <a href="users.php"> <?php echo $_SESSION['login'] ?></a></span>
	</div>
</div>

<div class="navigation">
	
	<ul>
		<li><a href="../index.php" target="_blank">Главная</a></li>
		<li><a href="index.php"><i class="fa fa-search" aria-hidden="true"></i> Поиск</a></li>
		<li><a href="create_new.php"><i class="fa fa-plus" aria-hidden="true"></i> Добавить</a></li>
		<li><a href="upload.php"><i class="fa fa-picture-o" aria-hidden="true"></i> Галерея</a></li>
		<li><a href="options.php"><i class="fa fa-cog" aria-hidden="true"></i> Настройки</a></li>
		<li><a href="login.php?action=logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Выход</a></li>
	</ul>
</div>
