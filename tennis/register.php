<?php
/********************************************************
CWE  				98: Improper Control of Filename for Include/Require Statement in PHP Program ('PHP Remote File Inclusion')

Описание: 			Ошибка связана с прямым использованием функции include_once('db.php'). 
					Можно сформировать hhtp запрос таким образом, что удастся внедрить php модуль с чужого хостинга.

Решение проблемы: 	1)	Нумеровать модули следующем образом: “module1.php”, ”module2.php” …”module<n>.php”
						Преобразовать $module в числовой формат (settype($module,”integer”)) 
					2) 	Использовать конструкцию switch-case.
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
	 exit (json_encode(array('success' => false, "msg" => "Так ты же уже зарегистрирован.")));
 $name = $_POST['n'];
 $login = $_POST['l'];
 $pass = $_POST['p'];
 $pass2 = $_POST['p2'];
 session_start();
 $hash = $db->quote($_COOKIE['PHPSESSID']);
 session_destroy();
 
 if (!preg_match('/^[a-zA-Z0-9|_]+$/',$name))
	exit (json_encode(array('success' => false, "msg" => "Никнейм содержит недопустимые символы. Разрешённые символы: a-z, A-Z, _")));
 if (strlen($name) > 40)
	exit (json_encode(array('success' => false, "msg" => "Длина никнейма не может превышать 40 символов."))); 
 if (!preg_match('/^[a-zA-Z0-9|_]+$/',$login))

	exit (json_encode(array('success' => false, "msg" => "Логин содержит недопустимые символы. Разрешённые символы: a-z, A-Z, _")));
 if (strlen($login) > 20)
	exit (json_encode(array('success' => false, "msg" => "Длина логина не может превышать 20 символов.")));
 
 if (strlen($pass) < 8)
	exit (json_encode(array('success' => false, "msg" => "Пароль должен быть длиной минимум 8 символов.")));
 if ($pass != $pass2)
	exit (json_encode(array('success' => false, "msg" => "Пароли не совпадают, ну я же говорил."))); 

 $sql = $db->query('SELECT `name` FROM `users` WHERE `login` = '.$db->quote($login));
 if ($sql->rowCount() != 0)
	exit (json_encode(array('success' => false, "msg" => "Пользователь с данным логином уже существует.")));

 $time = time();
 $s1 = 'pq9qhxNEXPYnLyF9QQVc';
 $s2 = 'wfAexZgJNQh04fq6mJd8';
 $pass = hash('sha256', $s1.$pass.$time.$s2);
 $db->exec("INSERT INTO users (login,password,name,regdate,hash) VALUES ('$login', '$pass', '$name', '$time', $hash)");
 exit (json_encode(array('success' => true, "msg" => "Регистрация прошла успешно, сейчас страница перезагрузится")));
?>