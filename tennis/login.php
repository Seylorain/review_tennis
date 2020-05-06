<?php
/********************************************************
CWE  				98: Improper Control of Filename for Include/Require Statement in PHP Program ('PHP Remote File Inclusion')

Описание: 			Ошибка связана с прямым использованием функций: 
				include_once('db.php')
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

if ($_SERVER['REQUEST_METHOD'] != "POST"){
	 header("HTTP/1.0 405 Method Not Allowed");
	 exit();
 }

 if ($user)
	 exit (json_encode(array('success' => false, "msg" => "Так ты же уже залогинился.")));
 

 $l = $_POST['l'];
 $p = $_POST['p'];


/*******************************************************************

CWE                 307: Improper Restriction of Excessive Authentication Attempts          
Описание         	Нет ограничения на количество попыток ввода пароля. Злоумышленник может перебрать пароли и логины.
Решение проблемы:	Ограничить количество попыток:
					if($coutn>5)
						exit (json_encode(array('success' => false, "msg" => "Количество неудачных попыток аутентификации превышено")));

*/
 if (!preg_match('/^[a-zA-Z0-9|_]+$/',$l))

	exit (json_encode(array('success' => false, "msg" => "Логин содержит недопустимые символы. Разрешённые символы: a-z, A-Z, _")));
 if (strlen($login) > 20)
	exit (json_encode(array('success' => false, "msg" => "Длина логина не может превышать 20 символов.")));

/*******************************************************************
CWE                 	89: Improper Neutralization of Special Elements used in an SQL Command ('SQL Injection')

Описание		Присутствует возможность внедрения SQL-инъекции при обращении к login.
			Можно применить следующую SQL - инъекцию:
			user' OR 1=1 -- и тогда любой логин будет верен.

Решение проблемы 	Нельзя брать данные напрямую в запросе, необходима из проверка на валидность. Можно воспользоваться функцией
			mysql_real_escape_string(). 
			Данная функция требует установить соединение с БД, перед использованием.

			$db->query("SELECT name FROM users WHERE user='%s', 
					mysql_real_escape_string($l)
				);

Источник: 			https://www.php.net/mysql_real_escape_string	 	    
*/

 $sql = $db->query("SELECT regdate FROM users WHERE login = '$l'");
 $time = false;
 if ($sql->rowCount() != 0){
	$row = $sql->fetch();
	$time = $row['regdate'];
 }

 if (!$time)
	exit (json_encode(array('success' => false, "msg" => "Такой пары логин / пароль не существует.")));
 $s1 = 'pq9qhxNEXPYnLyF9QQVc';
 $s2 = 'wfAexZgJNQh04fq6mJd8';

 $pass = hash('sha256', $s1.$p.$time.$s2);
/*******************************************************************
CWE                 	89: Improper Neutralization of Special Elements used in an SQL Command ('SQL Injection')

Описание		Присутствует возможность внедрения SQL-инъекции при обращении к login.
			Можно применить следующую SQL - инъекцию:
			user' OR 1=1 -- и тогда любой логин будет верен, а пароль будет отброшен во время аутентификации.

Решение проблемы 	Нельзя брать данные напрямую в запросе, необходима из проверка на валидность. Можно воспользоваться  функцией
			mysql_real_escape_string(). 
			Данная функция требует установить соединение с БД, перед использованием.

			$db->query("SELECT name FROM users WHERE user='%s' AND password='%s', 
					mysql_real_escape_string($l),
            				mysql_real_escape_string($pass)
					);

Источник: 			https://www.php.net/mysql_real_escape_string
*/

 $sql = $db->query("SELECT name FROM users WHERE login = '$l' AND password = '$pass'");
 
 if ($sql->rowCount() != 0){
	$db->exec("UPDATE users SET hash = ".$db->quote($_COOKIE['PHPSESSID'])." WHERE login = '$l'");
	exit (json_encode(array('success' => true)));
 }
	exit (json_encode(array('success' => false, "msg" => "Такой пары логин / пароль не существует.")));
?>
