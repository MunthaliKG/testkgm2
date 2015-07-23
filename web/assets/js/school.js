$(document).ready(function(){

	// listen for change of value in the district select list
	$('#schoolfinder_district').change(function(event){

		var district = $(this).val();
		//use ajax to auto-populate the school select list based on the selected district
		$.ajax({
			url: Routing.generate('school_ajax',{id: district})
		})
		.done(function (data) {
            $('#schoolfinder_school').html(data);
        })
	})

	//submit the form if a school is selected
	$('#schoolfinder_school').change(function(event){

		if($(this).val() !== "0"){
			$('#findByName').submit();
		}
	});

	$('#form_learner').change(function(event){
		if($(this).val() !== ""){
			$('#learnerPersonalForm').submit();
		}
	});

	$('#form_teacher').change(function(event){
		if($(this).val() !== ""){
			$('#findTeacherForm').submit();
		}
	});

	$('.datepicker').datepicker();

});