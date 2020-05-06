<div class="col-12 no-gutters" style="display:flex; border-bottom:1px solid red">
	<div class="col-<?php echo $user['rank'] == 100 ? 3 : 9 ?>">
	<a href = "/"><img src = "logo.png" style = "height:100%"></a>
	</div>
	<?php if ($user['rank'] == 100){?>
	<div class = "col-6">
		<ul class = "nav navbar" style = "height: 100%">
			<li data-toggle="modal" data-target="#tickets"> Тикеты (Не реализовано :)</li>
			<li data-toggle="modal" data-target="#ctourn"> Создать турнир</li>
			<li data-toggle="modal" data-target="#Mda"> Я НЕ ПРИДУМАЛ</li>
		</ul>
	</div>
	<?php }?>
	<div class="col-3">
	<?php
	if (!$user){
	?>
		<button class = "login btn btn-outline-success" style= "margin-top:10px; margin-bottom:46px;" data-toggle = "modal" data-target="#login">Логин <i class="fal fa-sign-in-alt"></i></button>
	<?php
	}else{
	?>
		<b><?=$user['name']?></b> (<?=$user['login']?>) <img class = "rounded" src = "https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/92/928735b49617f5ba2063c2b1cf1ffac3262fc22a.jpg"><br>
		<span>Баланс: </span><?=$user['balance']?><br>
		<span>Рейтинг: </span><?=$user['rating']?> <button class = "btn btn-outline-danger" style="margin-left:20px; margin-bottom: 5px;" id = "exBtn">Выйти <i class="far fa-sign-out-alt"></i></button>
	<?php
	}
	?>
	</div>
	</div>