<?php
/********************************************************
CWE  				98: Improper Control of Filename for Include/Require Statement in PHP Program ('PHP Remote File Inclusion')

Описание: 			Ошибка связана с прямым использованием функций: 
				include_once('db.php'), include_once('TopMenu.php'), include_once('modals.php'), include('Tournament.php'), include('TournList.php')
				Можно сформировать hhtp запрос таким образом, что удастся внедрить php модуль с чужого хостинга.

Решение проблемы: 		1)Нумеровать модули следующем образом: “module1.php”, ”module2.php” …”module<n>.php”
				Преобразовать $module в числовой формат (settype($module,”integer”)) 
				2) Использовать конструкцию switch-case.
					switch ($case) // $case - имя переменной передаваемой в параметре к скрипту
					{
						case news:
						include("news.php");
						break;

						case articles:
						include("guestbook.php");
						break;
						... // и т.д.
						default:
						include("index.php"); // если в переменной $case не будет передано значение, которое учтено выше, то открывается главная страница
						break;
					}	
					
Источник: 			http://www.realcoding.net/articles/php-include-uyazvimost-ot-teorii-k-praktike.html
*/
 include_once('db.php');
 session_start();
 session_destroy();
?>
<html>
	<head>
		<title>Крутой заголовок</title>
		<script src="/js/jQuery.js"></script>
		<script src="/js/jquery.dataTables.min.js"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/expanding.js"></script>
		<script src="/js/dataTables.bootstrap.js"></script>
		<script src="/js/brackets.min.js"></script>
		<script src="/js/all.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
		<script src="/js/main.js?v=<?=time()?>"></script>
		<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="/css/all.min.css">
		<link rel="stylesheet" type="text/css" href="/css/main.css?v=<?=time()?>">
		<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" rel="stylesheet">
		<meta charset="utf-8">
	</head>
	<body>
		<?php
			include_once('TopMenu.php');
		?>
		<div class = "col-12">
			<?php include_once('modals.php'); ?>
			<div class = "container text-center" id = "main">	
				<?php 
					if (isset($_POST['id'])) include('Tournament.php'); else include('TournList.php'); 
				?>
			</div>
		</div>
	</body>
</html>
