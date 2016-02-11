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
    
    $('.exit_reason').change(function(event){
    	var count = $(this).data('reasoncount');
    	var target = $('[data-othercount='+count+']');
        if($(this).val() === "other"){
            target.prop('readonly','');
        }else{
            target.prop('readonly','readonly');
        }
    });
    $('#customReport_standard').change(function(event){
		if($(this).val() === "Std 1 TO..."){
			$('#customReport_standard').prop('readonly','');
		}else{
			$('#customReport_standard').prop('readonly','readonly');
		}
	});
        
        //listen for change in the teacher type field and hide some field (for snt or regular teacher)
        $('#teacher_teacher_type_0').change(function(event){//SNT option selected

		if($(this).val() == "snt"){
                    //alert('SNT selected');
                    $('#teacher_cpd_training').prop('disabled','disabled');
                    $('#teacher_snt_type').prop('disabled','');
                    $('#teacher_speciality').prop('disabled','');                                       
                    $('#teacher_year_started').prop('disabled','');
                    $('#teacher_qualification').prop('disabled','');
                    $('#teacher_speciality').prop('disabled','');
                   
		}
	});
         $('#teacher_teacher_type_1').change(function(event){ //regular option selected

		if($(this).val() == "regular"){
                    $('#teacher_cpd_training').prop('disabled','');
                    $('#teacher_snt_type').prop('disabled','disabled');
                    $('#teacher_no_of_visits').prop('disabled','disabled');
                    $('#teacher_year_started').prop('disabled','disabled');
                    $('#teacher_qualification').prop('disabled','disabled');
                    $('#teacher_speciality').prop('disabled','disabled');
		}
	});
        $('#teacher_snt_type').change(function(event){ //check snt type

		if($(this).val() == "Itinerant"){                    
                    $('#teacher_no_of_visits').prop('disabled','');                                            
		}else {                    
                    $('#teacher_no_of_visits').prop('disabled','disabled'); 
                }
	});
        
	//listen on resource form field to hide either available field or the quantity fields
	$('#resourceRoom_available').change(function(event){ //check snt type
        var targets =
            $('#resourceRoom_quantity_required, #resourceRoom_quantity_available, #resourceRoom_quantity_in_use');
		if($(this).val().trim() !== ''){
			targets.prop('disabled','disabled');
		}
        else{
            targets.prop('disabled','');
        }
	});
	$('#resourceRoom_quantity_available, #resourceRoom_quantity_in_use, #resourceRoom_quantity_required')
			.change(function(event){ //check snt type
                if($(this).val().trim() !== ''){
                    $('#resourceRoom_available').prop('disabled','disabled');
				}
                else if($('#resourceRoom_quantity_available').val().trim() === ''
                && $('#resourceRoom_quantity_in_use').val().trim() === ''
                && $('#resourceRoom_quantity_required').val().trim() === ''){
                    $('#resourceRoom_available').prop('disabled','');
                }
	});
        
    // disabled some fields depending on prefilled data values of resources
    if(($('#resourceRoom_quantity_required').val() !== '') || ($('#resourceRoom_quantity_available').val() !== '') || ($('#resourceRoom_quantity_in_use').val() !== '')){
            $('#resourceRoom_available').prop('disabled','disabled');
	}
    if($('#resourceRoom_available').val() !== ''){
	        $('#resourceRoom_quantity_required').prop('disabled','disabled');
            $('#resourceRoom_quantity_available').prop('disabled','disabled');
            $('#resourceRoom_quantity_in_use').prop('disabled','disabled');
	}
        
//code that hides/shows "other means" field depending on the value of the "means of travelling to school" field
	if($('#learner_personal_means_to_school').val() !== 'other'){
		$('#other_means').hide();
	}
	$('#learner_personal_means_to_school').change(function(event){
		var selected = $(this).val();
        if(selected === 'other'){
        	$('#other_means').show();
        }else{
        	$('#other_means').hide();
        }
	});
//code that hides/shows "other non-relative" field depending on the value of the "relationship" field
	if($('#learner_personal_guardian_relationship').val() !== 'other non-relative'){
		$('#non_relative').hide();
	}
	$('#learner_personal_guardian_relationship').change(function(event){
		var selected = $(this).val();
		if(selected === 'other non-relative'){
			$('#non_relative').show();
		}else{
			$('#non_relative').hide();
		}
	})

	$('[autofocus]:enabled:not([readonly]):first').focus();

//submit learner exit forms through ajax
	$('#exit_form_container').on('submit', '.learner_exit_form', function (e) {
        e.preventDefault();

        var form = $(this);

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize()
        })
        .done(function (data) {
        	form.parent('tr').html( data.htmlresponse );
        	if(data.error !== ''){
        		$('.error-box').append('<div class="alert alert-danger alert-dismissible pull-left" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+data.error+'</div><hr>');
        	}        	
            if (typeof data.message !== 'undefined') {
                alert(data.message);
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
    });
    $('body').on('click', '#save-all', function (e) {
    	e.preventDefault();
    	$('.learner_exit_form').each(function(index){
    		$(this).submit();
    	});
 
    });

});