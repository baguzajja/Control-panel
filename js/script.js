var notif_id;
var count_help = 0, count_kritik = 0, count_laporan = 0;
//var last_notif = 0;
function playSound( url ){   
  // document.getElementById("body").innerHTML="<embed src='"+url+"' hidden=true autostart=true loop=false>";
   $('#chatAudio')[0].play();
}
function showNofif(text, client, url){
	playSound("../misc/alert.mp3");
	clearTimeout(notif_id);
	$('.notif-wrapper').remove();
	$("body").append('<div class="notif-wrapper"><a href="'+url+'" class="notif"><i class="fa fa-warning"></i><span class="text">'+text+'</span><span class="client">'+client+'</span></a></div>');
	$('.notif-wrapper').fadeIn();
	notif_id = setTimeout(function(){ 
		$(".notif-wrapper").fadeOut(function(){
			$(".notif-wrapper").remove();
		})

	}, 10000);
}
function addMessage(type, msg, time, msgID){
	$(".message-wrapper > ul ").append('<li id="msg-'+msgID+'" class="new"><div class="message '+type+'"><p>'+msg+'</p><span class="date">'+time+'</span></div><br class="clear"></li>')
	$(".content-help .help-messages .message-wrapper").animate({ scrollTop: $('.content-help .help-messages .message-wrapper')[0].scrollHeight}, 1000);
	$(".message-wrapper > ul .new").fadeIn(700);
}
function response(group, msg){
	var msgID = Date.now();
	addMessage('reply', msg, 'Sending', msgID);
	$.ajax({
		url: "system.php?act=response_admin",
		method: "POST",
		data: {"deviceId": client_id, "toId": grup, "msg": msg},
		success: function(response){
			$("#msg-"+msgID).find('.date').html(response);
		}
	})
}
function updateCounter(counter){
	if(counter.help != count_help){
		count_help = counter.help;
		if(count_help == 0) $("#navbar li a[href='index.php'] .badge").remove()
		if($("#navbar li a[href='index.php'] .badge").length == 0 && count_help != 0){
			$("#navbar li a[href='index.php']").append('<span class="badge danger">'+count_help+'</span>');
		} else {
			$("#navbar li a[href='index.php'] .badge").html(count_help);
		}
	}
	if(counter.kritiksaran != count_kritik){
		count_kritik = counter.kritiksaran;
		if(count_kritik == 0) $("#navbar li a[href='kritiksaran.php'] .badge").remove()
		if($("#navbar li a[href='kritiksaran.php'] .badge").length == 0  && count_kritik != 0){
			$("#navbar li a[href='kritiksaran.php']").append('<span class="badge">'+count_kritik+'</span>');
		} else {
			$("#navbar li a[href='kritiksaran.php'] .badge").html(count_kritik);
		}
	}
	if(counter.laporan != count_laporan){
		count_laporan = counter.laporan;
		if(count_laporan == 0) $("#navbar li a[href='laporan.php'] .badge").remove()
		if($("#navbar li a[href='laporan.php'] .badge").length == 0  && count_laporan != 0){
			$("#navbar li a[href='laporan.php']").append('<span class="badge">'+count_laporan+'</span>');
		} else {
			$("#navbar li a[href='laporan.php'] .badge").html(count_laporan);
		}
	}

}
$(document).ready(function(){
	$(".content-help .help-messages .message-wrapper").animate({ scrollTop: $('.content-help .help-messages .message-wrapper')[0].scrollHeight}, 1000);
	$("#response").focus();
	$('<audio id="chatAudio"><source src="misc/alert.mp3" type="audio/mpeg"></audio>').appendTo('body');
	var wHeight = $(window).height(),
		wWidth = $(window).width(),
		replyWrapperHeight = $(".content-help .help-messages .reply-wrapper").outerHeight();
	$(".content-help .help-messages").height( wHeight );
	$(".content-help .help-messages .message-wrapper").height( wHeight - 50 - replyWrapperHeight - 15 - 15);


	$(".view-image-larger").click(function(){
		var wHeight = $(window).height(),
		wWidth = $(window).width(),
		replyWrapperHeight = $(".content-help .help-messages .reply-wrapper").outerHeight();
		var src = $(this).find('img').attr('src');
		$("body").append('<div class="overlay"></div><div class="image-preview"><a href="#"><i class="fa fa-close"></i></a><img src="'+src+'" alt=""></div>');
		$('.image-preview').css({
			left: (wWidth / 2) - ($('.image-preview').outerWidth()/2),
			top: (wHeight / 2) - ($('.image-preview').outerHeight()/2)
		})
		$('.overlay, .image-preview').fadeIn(100);
		$(".image-preview a, .overlay").click(function(){
			$('.overlay, .image-preview').fadeOut(100, function(){
				$('.overlay, .image-preview').remove();
			});
			return false;
		})
		return false;
	})

	$(".help-client").click(function(){
		//showNofif('Lorem ipsum dolor sit amet consectetur adpiscing elit', 'Arya Sena', '')
	})

	$("#btn-help-reply").click(function(){
		$("#response").focus();
		var msg = $("#response").val();
		if(msg == '') return false;
		response(grup, msg);
		$("#response").val('');
		return false;
	})
	setInterval(function(){
		$.ajax({
			url: "system.php?act=listen",
			method: "POST",
			data: {"id": client_id, "grup": grup, "last_timestamp": last_timestamp},
			// dataType: json,
			success: function(result){
				result = JSON.parse(result);
				if(result.length <= 0) return;
				updateCounter(result.counter);
				if(result.data.length <= 0) return;
				result = result.data;
				$.each(result, function(index, value){
					var type = '';
					if(value.type == 0) type = 'reply';
					addMessage(type, value.message, value.time);
					last_timestamp = value.date;
				})
			}
		})
	}, 1000);
	setInterval(function(){
		$.ajax({
			url: "system.php?act=check",
			method: "POST",
			data: {"last_notif": last_notif},
			success: function(result){
				var url = "index";
				console.log(result);
				result = JSON.parse(result);
				if(result.length <= 0) return;
				if(result.grup == "1") url = "kritiksaran"; else 
				if(result.grup == "2") url = "laporan"; else 
				if(!$('#room-0'+result.client_id).hasClass('new')){
					$('#room-0'+result.client_id).addClass('new').find('a').append('<span class="alert-btn">new</span>')
				}
				showNofif(result.message, result.nama, url + ".php?id="+result.client_id);
				last_notif = result.date;
			}
		})
	}, 1000);
})