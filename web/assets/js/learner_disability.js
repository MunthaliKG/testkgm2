$(document).ready(function(){
	// listen for change of value in the disability select list
	$('#addDisabilityForm').hide();
	$('.disability-select').change(function(event){

		var disability = $(this).val();
		var caller = $(this);
		//use ajax to auto-populate the level select list based on the selected disability
		if(disability !== ""){
			$.ajax({
				url: Routing.generate('populate_levels',{disabilityId: disability})
			})
			.done(function (data) {
	            $('.level-select').html(data);
	        })
		}
		
	})

	$('#showAddDisability').click(function(e){
		$('#addDisabilityForm').show();
		$('#showAddDisability').hide();
	});

});