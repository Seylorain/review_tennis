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

if ($user['rank'] != 100){
	header("HTTP/1.0 404 Not Found");
	include('404.html');
	exit();
}
if ($_SERVER['REQUEST_METHOD'] != "POST"){
	 header("HTTP/1.0 405 Method Not Allowed");
	 exit();
 }



$task = $_POST['task'];

switch($task){	
	case 'ct':
/********************************************************
CWE    		242:Use of Inherently Dangerous Function

Описание:	Уязвимость существует из-за ошибки в функции "html_entity_decode()". 
		Удаленный пользователь может получить доступ к определенным участкам памяти на системе с помощью сценария, вызывающего функцию "html_entity_decode()". 
		Удачная эксплуатация уязвимости возможна, если злоумышленник может контролировать входные данные и получить результат выполнения функции.
			
Решение проблемы: использование новой версии PHP от 5.1.3-RC1
*/ 
			$name = html_entity_decode($_POST['a']);
			$plamount = $_POST['b'];
			$price = $_POST['c'];
			$pool = $_POST['d'];
			$rtmin = $_POST['e'];
			$rtmax = $_POST['f'];
/********************************************************
CWE    		242:Use of Inherently Dangerous Function

Описание:	Уязвимость существует из-за ошибки в функции "html_entity_decode()". 
		Удаленный пользователь может получить доступ к определенным участкам памяти на системе с помощью сценария, вызывающего функцию "html_entity_decode()". 
		Удачная эксплуатация уязвимости возможна, если злоумышленник может контролировать входные данные и получить результат выполнения функции.
			
Решение проблемы: использование новой версии PHP от 5.1.3-RC1
*/ 			
			if (!preg_match('/^[a-zA-Zа-яА-Я0-9\sёЁ]+$/u',$name))
				exit(json_encode(array("success"=>false, 'msg'=> 'Это же абсурд! Немедленно убери это название!')));
			if (!(($plamount & ($plamount - 1)) == 0) || ($plamount > 16))
				exit(json_encode(array("success"=>false, 'msg'=> 'Число участников неверное.')));
			if ($price < 1)
				exit(json_encode(array("success"=>false, 'msg'=> 'Играем за бесплатно теперь?')));
			if ($pool > 1000)
				exit(json_encode(array("success"=>false, 'msg'=> 'Не жирно ли?')));
			if ($rtmin > $rtmax)
				exit(json_encode(array("success"=>false, 'msg'=> 'Дядя Петя, ты дурак?')));
			$time = time();
			$db->exec("INSERT INTO tournaments (name,player_amount,payment,pool,rtMin,rtMax,created) VALUES('$name','$plamount','$price','$pool','$rtmin','$rtmax','$time')");
			exit(json_encode(array("success"=>true)));
		break;
	
	
	default:
		exit(json_encode(array("success"=>false, "msg"=> "Почти попал.")));
		break;
}

exit(json_encode(array("success"=>false, "msg"=> "Это сообщение никто никогда не увидит.")));
?>
