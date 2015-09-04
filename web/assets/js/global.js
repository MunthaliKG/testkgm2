$(document).ready(function(e){
	/*scroll to any alerts on the page*/
	var position = $('.alert').position();
	if(position != undefined ){
		$(window).scrollTop(position.top);
	}
	$('#disabilityFormsCarousel').carousel({
		interval: false,
		wrap: false
	});
});