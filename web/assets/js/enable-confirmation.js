$(document).ready(function(){
	$('[data-toggle=confirmation]').confirmation({
		title: function(){ return $(this).data("prompt") },
		popout: true,
		singleton: true
	});
});