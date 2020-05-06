<?php
/********************************************************
CWE  				98: Improper Control of Filename for Include/Require Statement in PHP Program ('PHP Remote File Inclusion')

Описание: 			Ошибка связана с прямым использованием функции include_once('db.php'). 
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

if ($user){
	$s = $user['login'];
	$db->exec("UPDATE users SET hash = '' WHERE login = '$s'");
}
setcookie('PHPSESSID', '', time()-1);

header('Location: /');
exit();
?>
