<?php
/********************************************************

CWE             	242: Use of Inherently Dangerous Function

Описание:		Уязвимость позволяет удаленному злоумышленнику обойти ограничения безопасности и выполнить произвольный код на целевой системе.
			Возникает из-за ошибки в проверке входных данных в функции ini_set()", вызываемых из ".htaccess" файла. 
			
Решение проблемы: 	Использование новой версии PHP от 5.2.4

Источник:		https://www.securitylab.ru/vulnerability/302085.php

*/
ini_set('display_errors','Off');
try { 
/********************************************************

CWE                 	256: Plaintext Storage of a Password

Описание           	Пароль и логин записан в незашифрованном, открытом виде

Решение проблемы    	1) Сохранить пароль и логин в файл, а затем заблокировать доступ к файлу через .htaccess 
					(так же можно назначить пароль к файлу с помощью .htpasswd)
					<files mypasswdfile>
						order allow,deny
						deny from all
					</files>
					
Источник: https://clck.ru/NJYSS

*/
	$db = new PDO('mysql:host=localhost;dbname=tennis', 'root', 'Здесьмойкрутойпароль', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); 
} 

catch (PDOException $e) { 
/********************************************************

CWE              		210: Information Exposure Through Self-generated Error Message

Описание			Если будет введен неправильный пароль, он выведется на экран и злоумышленник получит доступ к БД

Решение проблемы        	Не использовать пароль и логин в открытом виде, в коде.

Источник: https://www.php.net/manual/ru/language.exceptions.php      
*/
	exit(json_encode(array( 'success' => false, 'error'=>$e->getMessage()))); 
}
if (isset($_COOKIE['PHPSESSID'])){
	$sql = $db->query("SELECT * FROM `users` WHERE `hash` = " . $db->quote($_COOKIE['PHPSESSID']));
	if ($sql->rowCount() != 0) {
		$row = $sql->fetch();		
		if($_COOKIE['PHPSESSID'] == $row['hash']) $user = $row;
	}
}
?>
