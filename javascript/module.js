require(['jquery'], function($) 
{
	var ccodefield = document.getElementsByName("coursecode")[0];
	var cnamefield = document.getElementsByName("coursename")[0];
	var cfacultyfield = document.getElementsByName("coursefaculty")[0];
	var cschoolfield = document.getElementsByName("courseschool")[0];
	
	$('select').change(function() 
	{
		cnamefield.value = ccodefield.value;
		cfacultyfield.value = ccodefield.value;
		cschoolfield.value = ccodefield.value;
	}); 
});