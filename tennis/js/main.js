"use strict";

function setCookie(key, value) {
	var exp = new Date();
	exp.setTime(exp.getTime() + (365 * 24 * 60 * 60 * 1000));
	document.cookie = key + "=" + value + "; expires=" + exp.toUTCString();
}

function getCookie(key) {
	var patt = new RegExp(key + "=([^;]*)");
	var matches = patt.exec(document.cookie);
	if (matches) {
		return matches[1];
	}
	return "";
}

$(document).ready(function () {
	$("#regBtn").on('click', function () {
		var n = $('#register_name').val(),
		l = $('#register_login').val(),
		p = $('#register_password').val(),
		p2 = $('#register_password2').val();
		if (p !== p2){
			toastr['error']('Введённые пароли должны совпадать');
			return;
		}
		$.ajax({
			type: "POST",
			url: "register.php",
			data: {
				n: n,
				l: l,
				p: p,
				p2: p2,
			},
			success: function (data) {
				data = JSON.parse(data);
				if (data.success) {
					toastr["success"](data.msg);
					setTimeout(function(){window.location.reload();},1000);
				} else {
					toastr["error"](data.msg);
				}
			},
			error: function (err) {
				toastr["error"]("AJAX error: " + err.responseText);
			}
		});
	});
	
	$('#logBtn').on('click', function(){
		var l = $('#login_username').val(),
			p = $('#login_password').val();
		$.ajax({
			type: "POST",
			url: "login.php",
			data: {
				l: l,
				p: p
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
	
	$('#exBtn').on('click', function(){
		location.href = '/exit.php';
	});
});

toastr.options = {
	"closeButton": false,
	"debug": false,
	"newestOnTop": false,
	"progressBar": true,
	"positionClass": "toast-bottom-left",
	"preventDuplicates": true,
	"onclick": null,
	"showDuration": "300",
	"hideDuration": "1000",
	"timeOut": "7000",
	"extendedTimeOut": "1000",
	"showEasing": "swing",
	"hideEasing": "linear",
	"showMethod": "fadeIn",
	"hideMethod": "fadeOut"
}
