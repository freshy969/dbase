//------------------------------------------------
// BACKGROUND JOB
//------------------------------------------------
$(function () {
	setInterval(function() {
		
		/*
			As explained in the Documentation -> Background Jobs section,
			here you can place function(s) to execute jobs in background.
			It's important that they stay inside this "$(function ()"
		*/


	}, 60*2*1000); // execute every 2 minutes

	console.log('bkgjobs.js IS WORKING!');
});// ./ on page load

