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

	if(!$('#year_set').length && !$('#year_not_necessary').length){
		//function to display modal box prompting the user for the school year
		var getYear = function(message){
			bootbox.prompt(message, function(response){
				if(response === null or response.trim() === ""){
					getYear("Please enter the school year:");
				}else{
					$.ajax({
						url: Routing.generate('set_year',{year: response})
					})
					.done(function (data) {
			            if(data.result === 'success'){
			            	bootbox.alert('The school year has been set');
			            }
			            else{
			            	alert('there was an error setting the school year. Please try again. \n if the problem persists, contact the administrator');
			            }
			        })
			        .fail(function (jqXHR, textStatus, errorThrown) {
			            if (typeof jqXHR.responseJSON !== 'undefined') {
			                if (jqXHR.responseJSON.hasOwnProperty('form')) {
			                    $('#form_body').html(jqXHR.responseJSON.form);
			                }
			 
			                $('.form_error').html(jqXHR.responseJSON.message);
			 
			            } else {
			                alert(errorThrown);
			            }
			 
			        });
				}
			});
		};
		getYear("Please enter the school year: ");
		
	}
});