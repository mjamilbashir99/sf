$(function () {

	$('#header #nav li:last').addClass('nobg');
	$('.block_head ul').each(function() { $('li:first', this).addClass('nobg'); });
	//$('.block form input[type=file]').addClass('file');

	
	
	$('.block table tr th.header').css('cursor', 'pointer');

	// Messages
	$('.block .message').hide().append('<span class="close" title="Dismiss"></span>').fadeIn('slow');
	$('.block .message .close').hover(
		function() { $(this).addClass('hover'); },
		function() { $(this).removeClass('hover'); }
	);
		
	$('.block .message .close').click(function() {
		$(this).parent().fadeOut('slow', function() { $(this).remove(); });
	});
	
	// Tabs
	$(".tab_content").hide();
	$("ul.tabs li:first-child").addClass("active").show();
	$(".block").find(".tab_content:first").show();

	$("ul.tabs li").click(function() {

		$(this).parent().find('li').removeClass("active");
		$(this).addClass("active");
		$(this).parents('.block').find(".tab_content").hide();

		var activeTab = $(this).find("a").attr("href");
		$(activeTab).show();
		return false;
	});
	
	// Sidebar Tabs
	$(".sidebar_content").hide();
	
	if(window.location.hash && window.location.hash.match('sb')) {
	
		$("ul.sidemenu li a[href="+window.location.hash+"]").parent().addClass("active").show();
		$(".block .sidebar_content#"+window.location.hash).show();
	} else {
	
		$("ul.sidemenu li:first-child").addClass("active").show();
		$(".block .sidebar_content:first").show();
	}

	$("ul.sidemenu li").click(function() {
	
		var activeTab = $(this).find("a").attr("href");
		window.location.hash = activeTab;
	
		$(this).parent().find('li').removeClass("active");
		$(this).addClass("active");
		$(this).parents('.block').find(".sidebar_content").hide();			
		$(activeTab).show();
		return false;
	});	
	
	// Block search
	$('.block .block_head form .text').bind('click', function() { $(this).attr('value', ''); });
	
	// Date picker
	$('input.date_picker').date_input();

	// Navigation dropdown fix for IE6
	if(jQuery.browser.version.substr(0,1) < 7) {
		$('#header #nav li').hover(
			function() { $(this).addClass('iehover'); },
			function() { $(this).removeClass('iehover'); }
		);
	}
	
	
		
});