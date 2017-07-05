"use strict";
$(document).ready(function() {



//Show spinning animation on login
$('#Login-form').submit(function(){
	$("#spin").show();
});

//--------------------------------------------
// fade out alert messages
window.setTimeout(function() {
	$(".alert").fadeTo(1000, 0).slideUp(1000, function(){
		$(this).remove(); 
	});
}, 10000);

//--------------------------------------------

// remind password toggle link-button
$('#remind-password-link').on('click', function(){

	if($('#password-reminder').css('display')=='none'){
		$('#password-reminder').show();
	}else{
		$('#password-reminder').hide();		
	}
});
$('#sendReminder').click(function(){
	document.getElementById("password").required = false;
});

//--------------------------------------------
//END LOGIN FORM PAGE


//
//BEGIN MAIN PAGE (loggedInPage.php)
//

var city= "Uppsala";

//select dropdown menu item in main page
$('#mainDropdown .dropdown-item').click(function(){
	$('#m-selected').text($(this).text());
	city = $(this).text();
});

//select dropdown menu item in modal
$('#modalDropdown a').click(function(){
	$('#selected').text($(this).text());

	//asign value to another field for easy access, (may be unnecessary)
	city= $(this).text();
	document.getElementById('modal-city').value = city;
});

//--------------------------------------------

// < MAIN PAGE ADDRESS FIELD > detect text input in and use AJAX to correspinding fields from database
$("#address").on("input propertychange", function(e) {

	$(this).css({'color':'#31304E', 'backgroundColor':'#DCE2E9'});
	var address= $("#address").val();

	$.ajax({ 
		url: "instantQuery.php",
		method: "POST",
		data: {'address': address, 'city': city},

		success:function(data){
			$("#keyNumber").html(data.split("||")[0]);
			$("#comments").html(data.split("||")[1]);
		}
	})
});



// < MAIN PAGE - MODAL >

//--------------------------------------------

//modal tabs adjustment
$('#add-remove-button').click(function(){
	$("#entries-tab").click(); 
});

//--------------------------------------------

// <MODAL QUERY - USER > detect email field and get user data from database
$("#modalEmail").on('input propertychange', function() {
	
	var email= $("#modalEmail").val();

	$.ajax({ 
		url: "query.php",
		method: "POST",
		data: {'email': email},

		success:function(data){
			$("#name").html(data.split("||")[0]);
			$("#level").html(data.split("||")[1]);
			$("#mobile").html(data.split("||")[2]);
		}
	})
});	

// < MODAL QUERY - ADDRESS > detect address field and get address data
$("#modalAddress").on('input propertychange', function() {
	
	var modalAddress= $("#modalAddress").val();

	//getting key and comments data from database after search
	$.ajax({ 
		url: "query.php",
		method: "POST",
		data: {'address': modalAddress, 'city': city},

		success:function(data){
			$("#modalKey").html(data.split("||")[0]);
			$("#modalTextarea").html(data.split("||")[1]);
		}
	})
});

//--------------------------------------------


// < REQUIRED FIELDS PER BUTTON>

$('#addUser').click(function(){

		//users
		document.getElementById("level").required = false;
		$('usersForm').submit();
	});
/*
$('#add-entry1').click(function(){

		//users
		document.getElementById("level").required = false;
		//entries

		$('usersForm').submit();
	});

	*/

	$('#deleteEntry').click(function(){

		//entries
		document.getElementById("modalKey").required = false;

		return confirm('this cannot be undone,\n are you sure?')
		$('entryForm').submit();
	});


	$('#deleteUser').click(function(){

		//users
		document.getElementById("level").required = false;

		return confirm('this cannot be undone,\n are you sure?')
		$('userForm').submit();
	});


	$('#updateUser').click(function(){

		return confirm('this cannot be undone,\n are you sure?')
		$('form').submit();
	});

// VALIDATIONS FOR MOBILE AND KEY FIELDS 
$('#mobile').bind('input propertychange', function () {
		$(this).val($(this).val().replace(/[^0-9()+-]/g, ''));
	});

// VALIDATIONS FOR LIMIT FIELDS 

		$('#level').bind('input propertychange', function () {
		$(this).val($(this).val().replace(/[^1-2]/g, ''));
	});

	$('#modalKey').bind('input propertychange', function () {
		$(this).val($(this).val().replace(/[^0-9a-zA-Z-/]/g, ''));
	});


});