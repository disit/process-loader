$('.check_user').change(function()
 {
	checklist=document.getElementsByClassName("check_user");
	count=0;
	for(j=0;j<checklist.length;j++){
		if(checklist[j].checked==false){
			count=count+1;
		}
	}
  				
	if(count==checklist.length){
		$.ajax({
			type:'POST',
			url:'./sessions/session_user.php',	
			complete: function(event){
			event.preventDefault();
			document.facet_form.submit();					
			}	
		}); 
	}
    $('#submit_form').click();
});


$('.check_cat').change(function()
 {
	checklist=document.getElementsByClassName("check_cat");
	count=0;
	for(j=0;j<checklist.length;j++){
		if(checklist[j].checked==false){
			count=count+1;
		}
	}
  				
	if(count==checklist.length){
		$.ajax({
			type:'POST',
			url:'./sessions/session_category.php',	
			complete: function(event){
			event.preventDefault();
			document.facet_form.submit();					
			}	
		}); 
	}
    $('#submit_form').click();
});
 
 
 
 $('.check_format').change(function()
 {
	checklist=document.getElementsByClassName("check_format");
	count=0;
	for(j=0;j<checklist.length;j++){
		if(checklist[j].checked==false){
			count=count+1;
		}
	}
  				
	if(count==checklist.length){
		$.ajax({
			type:'POST',
			url:'./sessions/session_format.php',	
			complete: function(event){
			event.preventDefault();
			document.facet_form.submit();					
			}	
		}); 
	}
    $('#submit_form').click();
});


$('.check_ri').change(function()
 {
	checklist=document.getElementsByClassName("check_ri");
	count=0;
	for(j=0;j<checklist.length;j++){
		if(checklist[j].checked==false){
			count=count+1;
		}
	}
  				
	if(count==checklist.length){
		$.ajax({
			type:'POST',
			url:'./sessions/session_ri.php',	
			complete: function(event){
			event.preventDefault();
			document.facet_form.submit();					
			}	
		}); 
	}
    $('#submit_form').click();
});
 
 
 $('.check_lic').change(function()
 {
	checklist=document.getElementsByClassName("check_lic");
	count=0;
	for(j=0;j<checklist.length;j++){
		if(checklist[j].checked==false){
			count=count+1;
		}
	}
  				
	if(count==checklist.length){
		$.ajax({
			type:'POST',
			url:'./sessions/session_lic.php',	
			complete: function(event){
			event.preventDefault();
			document.facet_form.submit();					
			}	
		}); 
	}
    $('#submit_form').click();
});


$('.check_rt').change(function()
 {
	checklist=document.getElementsByClassName("check_rt");
	count=0;
	for(j=0;j<checklist.length;j++){
		if(checklist[j].checked==false){
			count=count+1;
		}
	}
  				
	if(count==checklist.length){
		$.ajax({
			type:'POST',
			url:'./sessions/session_rt.php',	
			complete: function(event){
			event.preventDefault();
			document.facet_form.submit();					
			}	
		}); 
	}
    $('#submit_form').click();
});


$('.check_per').change(function()
 {
	checklist=document.getElementsByClassName("check_per");
	count=0;
	for(j=0;j<checklist.length;j++){
		if(checklist[j].checked==false){
			count=count+1;
		}
	}
  				
	if(count==checklist.length){
		$.ajax({
			type:'POST',
			url:'./sessions/session_periodic.php',	
			complete: function(event){
			event.preventDefault();
			document.facet_form.submit();					
			}	
		}); 
	}
    $('#submit_form').click();
});