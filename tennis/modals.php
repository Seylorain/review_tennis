<!--

<div class = "modal fade" id = "">
	<div class = "modal-dialog">
		<div class = "modal-content">
			<div class = "modal-header">
				<h4 class="modal-title"><b></b></h4>
			</div>
			<div class = "modal-body">
				<div class="form-group">
					
				</div>
			</div>
			<div class="modal-footer">
				
			</div>
		</div>
	</div>
</div>


!-->





<div class = "modal fade" id = "login">
	<div class = "modal-dialog">
		<div class = "modal-content">
			<div class = "modal-header">
				<h4 class="modal-title"><b>Вход</b></h4>
			</div>
			<div class = "modal-body">
				<div class="form-group">
					<label>Логин</label>
					<input type="text" class="form-control" id="login_username" value="">
					<label>Пароль</label>
					<input type="password" class="form-control" id="login_password" value="">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-success" style = "width:100%" id = "logBtn">Логин</button>
				<button type="button" class="btn btn-outline-primary" style = "width:100%" data-dismiss="modal" data-toggle = "modal" data-target = "#register">Регистрация</button>
			</div>
		</div>
	</div>
</div>

<div class = "modal fade" id = "register">
	<div class = "modal-dialog">
		<div class = "modal-content">
			<div class = "modal-header">
				<h4 class="modal-title"><b>Регистрация</b></h4>
			</div>
			<div class = "modal-body">
				<div class="form-group">
					<label>Никнейм (Отображаемое имя)</label>
					<input type="text" class="form-control" id="register_name" value="">
					<label>Логин</label>
					<input type="text" class="form-control" id="register_login" value="">
					<label>Пароль</label>
					<input type="password" class="form-control" id="register_password" value="">
					<label>Повторите пароль</label>
					<input type="password" class="form-control" id="register_password2" value="">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-primary" style = "width:100%" id = "regBtn">Зарегистрироваться</button>
			</div>
		</div>
	</div>
</div>




<?php if ($user['rank'] == 100){ ?>

<div class = "modal fade" id = "ctourn">
	<div class = "modal-dialog">
		<div class = "modal-content">
			<div class = "modal-header">
				<h4 class="modal-title"><b>Создать турнир</b></h4>
			</div>
			<div class = "modal-body">
				<div class="form-group">
					<label>Название турнира</label>
					<input type="text" class="form-control" id="TName" value="">
					<label>Кол-во участников</label>
					<select class = "form-control" id = "pAM">
					<?php for ($i=1; $i< 5;$i++)
						echo "<option>".pow(2,$i)."</option>"
					?>
					</select>
					<label>Стоимость входа</label>
					<input class = "form-control" type="number" min="1" max="100" step="1"/ id = "TPrice">
					<label>Призовой пул</label>
					<input class = "form-control" type="number" min="100" max="1000" step="1"/ id = "TPool">
					<label>Ограничение по рейтингу</label>
					<div style="display:flex; align-items:center;"><input class = "form-control" type="number" min="0" step="1"/ id = "rtMin"> <span>-</span> <input class = "form-control" type="number" min="1" step="1"/ id = "rtMax"></div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-primary" style = "width:100%" id = "CreateTournament">Создать</button>
			</div>
		</div>
	</div>
</div>


<script>
$(document).ready(function () {
	$('#CreateTournament').on('click',function(){
		var a = $('#TName').val(),
			b = $('#pAM').val(),
			c = $('#TPrice').val(),
			d = $('#TPool').val(),
			e = $('#rtMin').val(),
			f = $('#rtMax').val();
		$.ajax({
			type: "POST",
			url: "admin.php",
			data: {
				a: a,
				b: b,
				c: c,
				d: d,
				e: e,
				f: f,
				task: 'ct'
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
	});
});
</script>

<?}?>