$(document).ready(function(){
	if($('#adduser_roles').val() === 'ROLE_SUPER_ADMIN'){
		$('#adduser_access_level, #adduser_access_domain, #adduser_allowed_actions').prop('disabled','disabled');
	}
	
	$('#access_district').change(function(event){
		var accessDistrict = $(this).val();
		var accessLevel = $('#adduser_access_level').val();

		if(accessDistrict !== '0' && accessLevel !== '4'){
			/*use ajax to auto-populate the access domain select list with zones or schools 
			 based on the selected district*/
			$.ajax({
				url: Routing.generate('populate_access_domain', {level: accessLevel, district: accessDistrict})
			})
			.done(function(data) {
	            $('#adduser_access_domain').html(data);
	        })
		}
		
	});

	$('#access_district_div').hide();
	
	$('#adduser_access_level').change(function(event){
		var level = $(this).val();
		if(level !== '3' && level !== '4' && level !== ''){
			$('#access_district_div').show();
		}
		else{
			$('#access_district_div').hide();
			if(level === '3'){
				var accessDistrict = $(this).val();
				/*use ajax to populate the select list with districts*/
				$.ajax({
					url: Routing.generate('populate_access_domain', {level: 3, district: 0})
				})
				.done(function(data) {
					$('#adduser_access_domain').html(data);
				})
			}
		}
	});

	$('#adduser_roles').change(function(event){
		var role = $(this).val();
		var dependentFields = $('#adduser_access_level, #adduser_access_domain, #adduser_allowed_actions');
		if(role === 'ROLE_SUPER_ADMIN'){
			$('#adduser_access_level').val('4');
			$('#adduser_access').val('4');
			$('#adduser_allowed_actions').val('2');
			$('#access_district_div').hide();
			dependentFields.prop('disabled','disabled');
		}
		else{
			dependentFields.val('');
			dependentFields.prop('disabled','');
		}
	});
});