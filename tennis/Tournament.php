<?php


if (!is_numeric($_POST['id'])){
	header('Location: /');
	exit();
}
$id = $_POST['id'];
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
$sql = $db->query("SELECT * FROM tournaments WHERE id = '$id'");
$game = array();
if ($sql->rowCount() != 0){
	$game = $sql->fetch();
}else{
	echo "<h2>КАК ВООБЩЕ МОЖНО БЫЛО ВЫБРАТЬ ТАКОЙ ID ?????</h2>";
	echo "<a href='/' class = 'btn btn-danger'>Назад</a>";
	exit();
}
$joined = false;
if (!is_null($game['players'])){
$players = explode(',', $game['players']);
$list = $game['players'];
$sql = $db->query("SELECT id, name FROM users WHERE id IN ($list)");
$idlist = $sql->fetchAll(PDO::FETCH_ASSOC);
if (in_array($user['id'],$players))
	$joined = true;
}
?>




<div class = "col-12" style = "border-bottom: 1px solid red;">
	<table class = "table" style="margin-top:20px; table-layout: fixed;">
		<thead class = "text-center">
			<tr><th colspan = "8"><?=$game['name']?></th></tr>
		</thead>
		<tbody>
			<tr>
				<td>ID</td>
				<td>Кол-во участников</td>
				<td>Стоимость</td>
				<td>Призовой фонд</td>
				<td>Рейтинг</td>
				<td>Создано</td>
				<td>Сыграно</td>
				<td rowspan = "2" class = "text-center" style = "vertical-align:middle"><button class = "btn btn-outline-<?php echo $joined==true ? 'danger' : 'success'?>" onclick = "Join(<?=$game['id']?>);" <?php echo $joined==true ? 'disabled' : ''?>><?php echo $joined ? 'Вы уже присоединились' : 'Присоединиться'?> <i class="fal fa-racquet"></i></button></td>
			</tr>
			<tr>
				<td><?=$game['id']?></td>
				<td><?php
					echo ((!empty($game['players'])) ? count(explode(',',$game['players'])) : '0').'/'.$game['player_amount'];			
				?></td>
				<td><?=$game['payment']?></td>
				<td><?=$game['pool']?></td>
				<td><?=$game['rtMin'].' - '.$game['rtMax']?></td>
				<td><?=strftime('%T %d/%m/%y',$game['created'])?></td>
				<td><? echo $game['played'] ? strftime('%T %d/%m/%y',$game['played']) : 'Нет'?></td>
			</tr>
		</tbody>
	</table>
</div>

<div class = "col-12 brackets">
</div>



<script>

function getName(a,b){	
var name = '';
	a.forEach(function(itm){
		if (itm['id'] == b)
			name = itm['name'];
	});
	return name;
}

var rounds = [],
	players = <?=$game['player_amount']?>,
	playerlist = '<?=$game['bracket']?>'.split(','),
	slist = <?php echo json_encode($idlist); ?>;
var uniqueplayers = [...new Set(playerlist)];
var cntr = 0;



if (playerlist !== undefined && playerlist.length > players){
while (players != 1){
	var oof = [];
	for (var i = 0; i < players; i+=2){
		oof.push({player1 : {name: getName(slist,playerlist[cntr]), ID: uniqueplayers.indexOf(playerlist[cntr++])}, player2 : {name: getName(slist,playerlist[cntr]), ID: uniqueplayers.indexOf(playerlist[cntr++])}});
	}
	rounds.push(oof);
	players = Math.floor(players/2);
	console.log(cntr);
}
rounds.push([{player1 : {name: getName(slist,playerlist[cntr]), ID: uniqueplayers.indexOf(playerlist[cntr])}}]);

var titles;
switch(players){
	case 16:
		titles = ['1/8 Финала', '1/4 Финала', 'Полуфинал', 'Финал', 'Чемпион'];
		break;
	case 8:
		titles = ['1/4 Финала', 'Полуфинал', 'Финал', 'Чемпион'];
		break;
	case 4:
		titles = ['Полуфинал', 'Финал', 'Чемпион'];
		break;
	case 2:
		titles = ['Финал', 'Чемпион'];
		break;
}
$(".brackets").brackets({
            titles: titles,
            rounds: rounds,
            color_title: 'black',
            border_color: '#46CFB0',
            color_player: 'black',
            bg_player: 'white',
            color_player_hover: 'white',
            bg_player_hover: '#E95546',
            border_radius_player: '5px',
            border_radius_lines: '5px',
});
}else{
	$('.brackets').html('Турнир ещё не проведён, сетка недоступна.');
}

function Join(id){
	$.ajax({
			type: "POST",
			url: "TournJoin.php",
			data: {
				id: id
			},
			success: function (data) {
				data = JSON.parse(data);
				if (data.success) {
					window.location.reload();
				} else {
					toastr["error"](data.msg);
				}
			},
			error: function (err) {
				toastr["error"]("AJAX error: " + err.responseText);
			}
		});
}

</script>
