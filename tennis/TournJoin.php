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
if (!$user)
	exit(json_encode(array('success' => false, 'msg' => 'Сессия истекла, перезайди на сайт.')));
$id = (int) $_POST['id'];
$uid = $user['id'];

if (!is_numeric($id))
	exit(json_encode(array('success' => false, 'msg' => 'Опять что-то плохое пытаешься сделать?')));
/*******************************************************************
CWE                 	89: Improper Neutralization of Special Elements used in an SQL Command ('SQL Injection')

Описание		Присутствует возможность внедрения SQL-инъекции при обращении к id. 
			Можно подобрать id другого пользователя внедрением команды:
			1' OR 1=1 -- в таком случе получим доступ к уже существующему id.

Решение проблемы 	Нельзя брать данные напрямую в запросе, необходима из проверка на валидность. 
			1)Можно воспользоваться  функцией 
			mysql_real_escape_string(). 
			Данная функция требует установить соединение с БД, перед использованием.

			$db->query("SELECT * FROM tournaments WHERE id='%i', 
						mysql_real_escape_string($id),
					);
			2)Можно задать переменной $id заведомо только числовое значение: $id = (int)$_POST['id'];

Источник: 		https://www.php.net/mysql_real_escape_string
*/
$sql = $db->query("SELECT player_amount, players, payment, rtMin, rtMax, pool FROM tournaments WHERE id = '$id'");
if ($sql->rowCount() == 0)
	exit(json_encode(array('success' => false, 'msg' => 'Игры с таким ID не существует, ага.')));
$game = $sql->fetch(PDO::FETCH_ASSOC);
$payment = $game['payment'];
$pool = $game['pool'];
if (count($game['players']) != 0)$players = explode(',', $game['players']);

if ($game['player_amount'] <= count($players))
	exit(json_encode(array('success' => false, 'msg' => 'Данная игра уже всё, прошла. Обнови страницу')));
if (in_array($user['id'], $players))
	exit(json_encode(array('success' => false, 'msg' => 'Ты уже присоединился к данной игре.')));
if ($user['balance'] < $game['payment'])
	exit(json_encode(array('success' => false, 'msg' => 'У тебя недостаточно баланса для вступления в игру.')));
if (($user['rating'] < $game['rtMin']) || ($user['rating'] > $game['rtMax']))
	exit(json_encode(array('success' => false, 'msg' => 'Твой рейтинг не подходит для вступления в данную игру.')));
/*******************************************************************
CWE                 	89: Improper Neutralization of Special Elements used in an SQL Command ('SQL Injection')

Описание		Присутствует возможность внедрения SQL-инъекции при обновлении информации о пользователе. 
			Можно присвоить результат 
			1' OR 1=1 -- в таком случе получим доступ к уже существующему id.

Решение проблемы 	Нельзя брать данные напрямую в запросе, необходима из проверка на валидность. 
			1)Можно воспользоваться  функцией 
			mysql_real_escape_string(). 
			Данная функция требует установить соединение с БД, перед использованием.

			$db->query("SELECT * FROM tournaments WHERE id='%i', 
					mysql_real_escape_string($id),
					);
			2)Можно задать переменной $id заведомо только числовое значение: $id = (int)$_POST['id'];

Источник: 		https://www.php.net/mysql_real_escape_string
*/

$db->exec("UPDATE users SET balance = balance - $payment WHERE id = '$uid'");
if ($players == NULL)
	$pp = $uid;
else{
	$players[] = $uid;
	$pp = implode(',',$players);
}
$db->exec("UPDATE tournaments SET players = '$pp' WHERE id = '$id'");

if ($game['player_amount'] <= count($players)){
	$bracket = $players;
	$winners = $players;
	while (count($winners) != 1){
		$ww = array();
		for ($i = 0; $i < count($winners); $i+=2){
			$p1 = $winners[$i];
			$p2 = $winners[$i+1];
			$winner = rand(0,1) == 0 ? $p1 : $p2;
			$bracket[] = $winner;
			$ww[] = $winner;
		}
		$winners = $ww;
	}
	$time = time();
	$br = implode(',',$bracket);
	$db->exec("UPDATE tournaments SET played = '$time', bracket = '$br' WHERE id = '$id'");
	$db->exec("UPDATE users SET balance = balance + $pool, rating = rating + 25 WHERE id = '$winner'");
	$key = array_search($winner, $players);
		if ($key !== false) {
			unset($players[$key]);
		}
	$ff = implode(',',$players);
	$db->exec("UPDATE users SET rating = rating - 25 WHERE id IN ($ff)");
}


exit(json_encode(array('success' => true)));
?>
