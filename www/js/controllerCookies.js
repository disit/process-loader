

$(document).ready(function(){    

		 //funzione per settare cookie su form per mantenere selezioni checkate
  $("input.check_user").each(function() {
		  var mycookie = $.cookie($(this).attr('id'));
		  if (mycookie && mycookie === "true") {
		  $(this).prop('checked', mycookie);
			}
			});
		$("input.check_user").change(function() {
			$.cookie($(this).attr("id"), $(this).prop('checked'), {
			path: '/',
			expires: 365
	 });
 });
 
 
 

		 //funzione per settare cookie su form per mantenere selezioni checkate
  $("input.check_cat").each(function() {
		  var mycookie = $.cookie($(this).attr('id'));
		  if (mycookie && mycookie === "true") {
		  $(this).prop('checked', mycookie);
			}
			});
		$("input.check_cat").change(function() {
			$.cookie($(this).attr("id"), $(this).prop('checked'), {
			path: '/',
			expires: 365
	 });
 });
  

		 //funzione per settare cookie su form per mantenere selezioni checkate
  $("input.check_format").each(function() {
		  var mycookie = $.cookie($(this).attr('id'));
		  if (mycookie && mycookie === "true") {
		  $(this).prop('checked', mycookie);
			}
			});
		$("input.check_format").change(function() {
			$.cookie($(this).attr("id"), $(this).prop('checked'), {
			path: '/',
			expires: 365
	 });
 }); 

		 //funzione per settare cookie su form per mantenere selezioni checkate
  $("input.check_ri").each(function() {
		  var mycookie = $.cookie($(this).attr('id'));
		  if (mycookie && mycookie === "true") {
		  $(this).prop('checked', mycookie);
			}
			});
		$("input.check_ri").change(function() {
			$.cookie($(this).attr("id"), $(this).prop('checked'), {
			path: '/',
			expires: 365
	 });
 }); 

		 //funzione per settare cookie su form per mantenere selezioni checkate
  $("input.check_lic").each(function() {
		  var mycookie = $.cookie($(this).attr('id'));
		  if (mycookie && mycookie === "true") {
		  $(this).prop('checked', mycookie);
			}
			});
		$("input.check_lic").change(function() {
			$.cookie($(this).attr("id"), $(this).prop('checked'), {
			path: '/',
			expires: 365
	 });
 }); 

		 //funzione per settare cookie su form per mantenere selezioni checkate
  $("input.check_rt").each(function() {
		  var mycookie = $.cookie($(this).attr('id'));
		  if (mycookie && mycookie === "true") {
		  $(this).prop('checked', mycookie);
			}
			});
		$("input.check_rt").change(function() {
			$.cookie($(this).attr("id"), $(this).prop('checked'), {
			path: '/',
			expires: 365
	 });
 });
 
 		 //funzione per settare cookie su form per mantenere selezioni checkate
  $("input.check_per").each(function() {
		  var mycookie = $.cookie($(this).attr('id'));
		  if (mycookie && mycookie === "true") {
		  $(this).prop('checked', mycookie);
			}
			});
		$("input.check_per").change(function() {
			$.cookie($(this).attr("id"), $(this).prop('checked'), {
			path: '/',
			expires: 365
	 });
 });
 
 }); 