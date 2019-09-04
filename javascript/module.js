require(['jquery'], function($) 
{
	$(document).ready(function()
	{
		// Variables to store values for each element
		var ccodefield = document.getElementsByName("coursecode")[0];
		var cnamefield = document.getElementsByName("coursename")[0];
		var cfacultyfield = document.getElementsByName("coursefaculty")[0];
		var cschoolfield = document.getElementsByName("courseschool")[0]; 
		var cnumshells = document.getElementsByName("courseshellnumber")[0];

		//If Course Code is changed, change values of all other elements to match course code
		$("select[name='coursecode']").change(function()
		{
			cnamefield.value = ccodefield.value;
			cfacultyfield.value = ccodefield.value;
			cschoolfield.value = ccodefield.value;
		});

		//If Course Name is changed, change values of all other elements to match course name
		$("select[name='coursename']").change(function()
		{
			ccodefield.value = cnamefield.value;
			cfacultyfield.value = cnamefield.value;
			cschoolfield.value = cnamefield.value;
		});
	});
});