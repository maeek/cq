$(document).ready(function() {
	$(".hide-menu").click(function() {
		if(parseInt($(this).attr("data-opacity")) != 0){
			$(this).attr("data-opacity", 0);
			$(".menu").slideUp();
			$(this).html('<i class="fa fa-angle-down"></i>');
		} else {
			$(this).attr("data-opacity", 1);
			$(".menu").slideDown();
			$(this).html('<i class="fa fa-angle-up"></i>');

		}
	});
	$(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('#totop').fadeIn("fast");
		} else {
			$('#totop').fadeOut("fast");
		}
	});
	$(document).on("click","#totop",function() {
		$("html, body").animate({scrollTop: 0},"slow");
	});
});