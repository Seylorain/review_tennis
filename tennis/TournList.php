<?php 
$sql = $db->query("SELECT * FROM tournaments ORDER BY ID DESC");
$games = $sql->fetchAll(PDO::FETCH_ASSOC);

foreach ($games as $game){
?>
	<table class = "table" style="margin-top:20px; table-layout: fixed;" >
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
				<td rowspan = "2" class = "text-center" style = "vertical-align:middle"><form style="margin:0;" action="/" method = "POST"><input type = "hidden" name = "id" value = "<?=$game['id']?>"><button type="submit" class = "btn btn-outline-primary">Подробнее <i class="fas fa-search"></i></button></form></td>
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
<?php } ?>