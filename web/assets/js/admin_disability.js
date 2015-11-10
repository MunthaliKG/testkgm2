$(document).ready(function(){
	$('#form_disability').change(function(){
        if($(this).val() !== ""){
        	$('#findDisabilityForm').submit();
        }
	});
});