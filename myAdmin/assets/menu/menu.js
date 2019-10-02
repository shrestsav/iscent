/*jQuery time*/
$(document).ready(function(){
	$("#IBMS_Menu h3").click(function(){
		//slide up all the link lists
		$("#IBMS_Menu ul ul").slideUp();
		//slide down the link list below the h3 clicked - only if its closed
		if(!$(this).next().is(":visible"))
		{
			$(this).next().slideDown();
		}
	})
})