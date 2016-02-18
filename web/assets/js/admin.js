var userRole;
var userActions;
var userDomain;
var userLevel;

$(document).ready(function(){
	$('#access_district_div').hide();
	userRole = $('#adduser_roles').val();
	userActions = $('#adduser_allowed_actions').val();
	userDomain = $('#adduser_access_domain').val();
	userLevel = $('#adduser_access_level').val();

 	toggleUserRole($('#adduser_roles'), true);
	toggleAccessLevel($('#adduser_access_level'), true);
	
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
		$('#adduser_access_domain').prop('disabled','');
		
	});
	
	$('#adduser_access_level').change(function(event){
		toggleAccessLevel($(this));
	});

	$('#adduser_roles').change(function(event){
		toggleUserRole($(this));
	});
});
function toggleUserRole(object, pageLoad){
	pageLoad = (pageLoad === undefined)? false: pageLoad;
	var role = object.val();
	var dependentFields = $('#adduser_access_level, #adduser_access_domain');
	if(role === 'ROLE_SUPER_ADMIN' || role === 'ROLE_ADMIN'){
		$('#adduser_allowed_actions').val('2');
		$('#adduser_allowed_actions').prop('disabled','disabled');

		if(role === 'ROLE_SUPER_ADMIN'){
			$('#adduser_access_level').val('4');
			$('#adduser_access_domain').val('');
			$('#access_district_div').hide();
			dependentFields.prop('disabled','disabled');
		}else{
			if(pageLoad !== true) {//if this function has not been called while the page is loading
                $('#adduser_access_level').val('');
            }
			else {
                $('#adduser_access_level').val(userLevel);
            }
			dependentFields.prop('disabled','');
		}
	}
	else{
		if(pageLoad !== true){
			dependentFields.val('');
			$('#adduser_allowed_actions').val('');
			$('#adduser_allowed_actions').prop('disabled','');
			dependentFields.prop('disabled','');
		}else{
            $('#adduser_access_level').val(userLevel);
        }
	}
}
function toggleAccessLevel(object, pageLoad){
	pageLoad = (pageLoad === undefined)? false: pageLoad;
	var level = object.val();
	if(level !== '3' && level !== '4' && level !== ''){
		$('#access_district_div').show();
		$('#access_district').val('');
		$('#adduser_access_domain').prop('disabled','disabled');
	}
	else{
		$('#access_district_div').hide();
		if(level === '3'){
			/*use ajax to populate the select list with districts*/
			$.ajax({
				url: Routing.generate('populate_access_domain', {level: 3, district: 0})
			})
			.done(function(data) {
				$('#adduser_access_domain').html(data);
				if(pageLoad === true){
					$('#adduser_access_domain').val(userDomain);
				}
			})
		}
		if(level === '4'){
			$('#adduser_access_domain').prop('disabled','disabled');
		}else{
			$('#adduser_access_domain').prop('disabled','');
		}
	}
}