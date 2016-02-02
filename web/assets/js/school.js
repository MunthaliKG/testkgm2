$(document).ready(function(){

	// listen for change of value in the district select list
	$('#schoolfinder_district').change(function(event){

		var district = $(this).val();
		if(district !== ""){
			//use ajax to auto-populate the school select list based on the selected district
			$.ajax({
				url: Routing.generate('school_ajax',{id: district})
			})
			.done(function (data) {
	            $('#schoolfinder_school').html(data);
	        })
		}
	})
        
        // listen for change of value in the district select list in district page
	$('#lwdfinder_district').change(function(event){

		var district = $(this).val();
		if(district !== ""){
			//use ajax to auto-populate the school select list based on the selected district
			$.ajax({
				url: Routing.generate('school_ajax',{id: district})
			})
			.done(function (data) {
	            $('#lwdfinder_school').html(data);
	        })
		}
	})
        //---------------
        // listen for change of value in the district select list in district page
	$('#lwdfinder_school').change(function(event){

		var school = $(this).val();
		if(school !== ""){
			//use ajax to auto-populate the learner select list based on the selected school
			$.ajax({
				url: Routing.generate('learner_ajax',{id: school})
			})
			.done(function (data) {
	            $('#lwdfinder_learner').html(data);
	        })
		}
	})
        //submit the form if a learner is selected
	$('#lwdfinder_learner').change(function(event){                
		if($(this).val() !== "0"){
			$('#findLearnerForm').submit();
		}
	});
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
        $('#form_material').change(function(event){
		if($(this).val() !== ""){
			$('#findMaterialForm').submit();
		}
	});
        $('#form_need').change(function(event){
		if($(this).val() !== ""){
			$('#findResourceForm').submit();
		}
	});
        // listen for change of value in the district select list
	$('#zonefinder_district').change(function(event){

		var district = $(this).val();
		//use ajax to auto-populate the zone select list based on the selected district
		$.ajax({
			url: Routing.generate('zone_ajax',{id: district})
		})
		.done(function (data) {
            $('#zonefinder_zone').html(data);
        })
	})

	//submit the form if a zone is selected
	$('#zonefinder_zone').change(function(event){

		if($(this).val() !== "0"){
			$('#findByName').submit();
		}
	});
        
        //submit the form if a district is selected
	$('#districtfinder_district').change(function(event){

		if($(this).val() !== "0"){
			$('#findByName').submit();
		}
	});

    if($('.datepicker').length){
		$('.datepicker').datepicker();
    }
    
    $('#learner_exit_reason').change(function(event){
        if($(this).val() === "other"){
            $('#learner_exit_other_reason').prop('readonly','');
        }else{
            $('#learner_exit_other_reason').prop('readonly','readonly');
        }
    });
    $('#customReport_standard').change(function(event){
		if($(this).val() === "Std 1 TO..."){
			$('#customReport_standard').prop('readonly','');
		}else{
			$('#customReport_standard').prop('readonly','readonly');
		}
	});
        
        //listen for change in the teacher type field
        $('#teacher_teacher_type_0').change(function(event){//SNT option selected

		if($(this).val() == "snt"){
                    //alert('SNT selected');
                    $('#teacher_snt_type').prop('disabled','');
                    //$('#teacher_no_of_visits').prop('disabled','');
                    $('#teacher_speciality').prop('disabled','');
                    //$('#teacher_cpd_training').prop('disabled','disabled');
                     document.getElementById('teacher_snt_type').style.display = 'block';
                     //document.getElementById('teacher_no_of_visits').style.display = 'block';
                     document.getElementById('teacher_speciality').style.display = 'block';
                     document.getElementById('teacher_cpd_training').style.display = 'none';
                   
		}
	});
         $('#teacher_teacher_type_1').change(function(event){ //regular option selected

		if($(this).val() == "regular"){
                    $('#teacher_cpd_training').prop('disabled','');
                    //$('#teacher_snt_type').prop('disabled','disabled');
                    //$('#teacher_no_of_visits').prop('disabled','disabled');
                    document.getElementById('teacher_snt_type').style.display = 'none';
                    document.getElementById('teacher_no_of_visits').style.display = 'none';
                    document.getElementById('teacher_speciality').style.display = 'none';
                    document.getElementById('teacher_cpd_training').style.display = 'block';
		}
	});
        $('#teacher_snt_type').change(function(event){ //check snt type

		if($(this).val() == "Itinerant"){                    
                    $('#teacher_no_of_visits').prop('disabled','');                    
                    document.getElementById('teacher_no_of_visits').style.display = 'block';                    
		}else {
                    document.getElementById('teacher_no_of_visits').style.display = 'none';
                }
	});

	$('[autofocus]:enabled:not([readonly]):first').focus();
});