var initial_values = {};
var hault_form = false;
var errors = [];

$(document).ready(function() {
	
	$("form").each(function() {
		initialize_form($(this));
	})
	
	$("form").submit(function() {	
		errors = [];
		$("#feedback").remove();
		if (!check_for_errors($(this))) {
			var html = "<p id=\"feedback\" style=\"color: #cc0000;\">You must enter all required fields.</p>";
			if ($("#feedback").html()) {
				$("#feedback").html(html);
			} else {
				$(this).prepend(html);
			}
			//console.log(errors);
			$.each(errors, function(i, error) {
				var error_details = $('<div class="error">');
				error_details.html(error);
				$("#feedback").append(error_details);
			}); 
			return false;
		} 
		
		if (typeof window.on_form_success == 'function') 
			on_form_success();
		
		rel = $(this).attr("rel");
		
		if ($(this).hasClass("ajax")) {
			$.post($(this).attr("action"), $(this).serializeArray(), function(html) {
				id = "#"+rel;
				$(id).html(html)
			});
			return false;
		}
	});
	
	
	
});

function initialize_form(obj) {
	if (!obj.attr("id")) {
		obj.attr("id", "form");
	}

	form_name = obj.attr("id");
	id = "#"+form_name;

	$.each(obj.serializeArray(), function(i, field) {
		var element_id = field.name.replace("[", "_").replace("]", "");
		var element_key = form_name+"_"+element_id;
		
		initial_values[element_key] = field.value;
		//console.log(element_id, element_key, i, field);
		$("#"+element_id).focus(function() {
			if ($(this).val()==initial_values[element_key]) $(this).val("");
		}).blur(function() {
			if ($(this).val()=="") $(this).val(initial_values[element_key]);
		});//.val(initial_values[element_key]);
		
	});
}

function check_for_errors(obj) {
	var values = {};
	hault_form = false;

	if (!obj.attr("id")) {
		obj.attr("id", "form");
	}

	id = "#"+obj.attr("id");

	$.each(obj.serializeArray(), function(i, field) {
		values[field.name] = field.value;
	});
	
	$.each(values, function(name, value) {
		element_id = "#"+name.replace("[", "_").replace("]", "");
		element_name = name.replace("[", "_").replace("]", "");
		
		//console.log(id+" "+element_id);
		
		var required_function = $(id+" "+element_id).data("required");
		if (required_function) {
			required_result = mainfunc(required_function, value)
			//console.log(required_function, value, required_result);	
		}
		
		if ($(id+" "+element_id).parent().hasClass("required")) { 
			//console.log(value, initial_values[element_name]);
			if (value=="" || value==null || (required_function && !required_result)) { //|| value==initial_values[element_name]
				$(id+" "+element_id).addClass("error");
				hault_form = true;
			} else {
				$(id+" "+element_id).removeClass("error");
			}
			
		}
	});

	if (hault_form) {
		return false;
	}
	//console.log("SUCCESS");
	return true;
}



$('form.standard input').keypress(function(e){
    if (e.which == 13 ) return false;
    if (e.which == 13 ) e.preventDefault();
});


function ValidPostalCode(input) {
	var regex = /(^\d{5}(-\d{4})?$)|(^[ABCEGHJKLMNPRSTVXYabceghjklmnpstvxy]{1}\d{1}[A-Za-z]{1} ?\d{1}[A-Za-z]{1}\d{1})$/;
	if (!regex.test(input)) {
		errors.push("Please enter a valid Zip/Postal Code.");
		return false;
	}
	return true;
	
}

function ValidEmail(email) {
	var regex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i;
	if (!regex.test(email)) {
		errors.push("Please enter a valid email address.");
		return false;
	}
	return true;
}

function ValidPhone(input) {
	var regex = /^([0-9+\s]{0,4})?[0-9]{3}(.{1}|-{1}|\s{1})?[0-9]{3}(.{1}|-{1}|\s{1})?[0-9]{4}$/;
	//var regex = /^(\+\d)*\s*(\(\d{3}\)\s*)*\d{3}(-{0,1}|\s{0,1})\d{2}(-{0,1}|\s{0,1})\d{2}$/;
	
	if (input.length==0) return true;
	
	if (!regex.test(input) || input.length > 15) {	
		errors.push("Please enter a valid phone number.");
		return false;
	}
	return true
}

jQuery.fn.ValidEmail = function() {
	return this.each(function() {
		$(this).bind("change", function() {
			if (!ValidEmail($(this).val())) {
				$(this).addClass("error");
				return false;
			}
			return true;
		});
	});
};


function mainfunc (func){
    return this[func].apply(this, Array.prototype.slice.call(arguments, 1));
}

$.fn.ForcePhone = function() {
	//+11 123 123 1234\
	return this.each(function() {
	
		$(this).keydown(function(e) {
			var value = $(this).val();
			var key = e.charCode || e.keyCode || 0;
			//alert(key);
			if (value.length<18) {
				  return (
		                key == 8 ||
		                key == 46 ||
		                key == 32 ||
		                key == 187 ||
		                key == 189 ||
		                key == 190 ||
		                (key >= 37 && key <= 40) ||
		                (key >= 49 && key <= 56) ||
		                (key >= 96 && key <= 105)
		            );
			} else {
				return (key == 8 || (key >= 37 && key <= 40));
			}
			//alert(value.length);
			return false;
		});
	});
};

/*
$("INPUT[name=phone]").keyup(function() {
	value = $(this).val().replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2-$3");
	$(this).val(value);
});


jQuery.fn.ForceNumericOnly =
	function() {
	    return this.each(function() {
	        $(this).keydown(function(e) {
	        	
	            var key = e.charCode || e.keyCode || 0;
	            //console.log(key);
	            // allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
	            return (
	                key == 8 || 
	                //key == 9 ||
	                key == 46 ||
	                //key == 32 ||
	                //key == 189 ||
	                //key == 190 ||
	                //(key >= 37 && key <= 40) ||
	                (key >= 48 && key <= 57) ||
	                (key >= 96 && key <= 105)
	            );
	        });
	    });
	};
*/
	
$(document).ready(function() {
	$("li.phone input").ForcePhone();
});

