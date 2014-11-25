

$(document).ready(function() {
	
	$("h1.logo").click(function() {
		document.location = '/';	
	});

	$('a[rel="_blank"]').each(function(){
		$(this).attr('target', '_blank');
	});

});