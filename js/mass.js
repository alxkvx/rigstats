$(document).ready(function() {
	
	$("#defsubmit").click(function(){
		//console.log("clicked add");
		$('input[type=checkbox]').each(function () {
			if ($(this).is(':checked')) {
				var ip = $(this).val(),
				aid = $(this).attr('aid'),
				pool0 = $("#defpool1").val(),
				pool1 = $("#defpool2").val(),
				pool2 = $("#defpool3").val(),
				user0 = $("#defusr1").val(),
				user1 = $("#defusr2").val(),
				user2 = $("#defusr3").val(),
				temp  = $("#deftemp").val(),
				sensor= $("#defsensor").val(),
				fans = $("#deffanck").val(),
				worker = user+'.'+aid;
				url = 'poolsave.php?ip='+ip+'&type=s9&pool0='+encodeURIComponent(pool0)+'&user0='+user0+'.'+aid+'&pool1='+encodeURIComponent(pool1)+'&user1='+user1+'.'+aid+'&pool2='+encodeURIComponent(pool2)+'&user2='+user2+'.'+aid+'&temp='+temp+'&sensor='+sensor+'&fanck='+fans+'&loadfreq=true';
				$.get(url, function(data){
					$('#pools').append(data);
				});
			}
		});
	});
	$("#all").click(function(){
		$('input:checkbox').not(this).prop('checked', this.checked);
	});
	$("#add").click(function(){
		//console.log("clicked add");
		$('input[type=checkbox]').each(function () {
			if ($(this).is(':checked')) {
				var ip = $(this).val(),
				aid = $(this).attr('aid'),
				pool = $("#url").val(),
				user = $("#user").val(),
				worker = user+'.'+aid;
				url = 'command.php?ip='+ip+'&command=addpool&url='+encodeURIComponent(pool)+'&user='+worker;

				$.get(url, function(data){
					$('#pools').append(data);
				});
			}
		});
	});
	$("#addnswt").click(function(){
		$('input[type=checkbox]').each(function () {
			if ($(this).is(':checked')) {
				var ip = $(this).val(),
				aid = $(this).attr('aid'),
				pool = $("#urlsw").val(),
				user = $("#usersw").val(),
				worker = user+'.'+aid;
				url = 'command.php?ip='+ip+'&command=addnswtchpool&url='+encodeURIComponent(pool)+'&user='+worker;

				$.get(url, function(data){
					$('#pools').append(data);
				});
			}
		});
	});
	$("#switch").click(function(){
		var pool_id = $('#poolid').val();
		$('input[type=checkbox]').each(function () {
			if ($(this).is(':checked')) {
				var ip = $(this).val(),
				url = 'command.php?ip='+ip+'&command=switchpool&opt='+pool_id;
				$.get(url, function(data){
					$('#pools').append(data);
				});
			}
		});
	});
	$("#restart").click(function(){
		$('input[type=checkbox]').each(function () {
			if ($(this).is(':checked')) {
				var ip = $(this).val(),
				url = 'command.php?ip='+ip+'&command=restart';
				$.get(url, function(data){
					$('#pools').append(data);
				});
			}
		});
	});
});
