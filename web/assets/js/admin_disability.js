$(document).ready(function(){
	$('#form_disability').change(function(){
        if($(this).val() !== ""){
        	$('#findDisabilityForm').submit();
        }
	});

	$('#add-level-btn').click(function(e){
		e.preventDefault();

		bootbox.prompt("Please enter the level name", function(response){
			response = response.trim();
			if(response.trim() !== '' && response !== null && response !== undefined ){
				$.ajax({
							url: Routing.generate('add_level',{level: response})
						})
						.done(function (data) {
							if(data.result === 'success'){
								$('#disability_levels').append('<option value="'+data.idlevel+'">'+data.levelName+'</option>');
							}
							else{
								alert('there was an error adding the level. Please try again. \n if the problem persists, contact the administrator');
							}
						})
			}
		});
	});
});