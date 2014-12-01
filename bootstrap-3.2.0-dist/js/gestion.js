/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */





$(document).ready(function(){
    
  maDate = new Date();
  nJour = maDate.getDay();
  nHour = maDate.getHours();
  
if(nJour < 1 || nJour > 3){  
    
    //$(".container" ).load( "jours_off.html" ); 
    document.location.replace('jours_off.html');
} 


$('.photo').click(function(){
   
    $(this).toggleClass("changeBackground");
});

$('#valider').click(function(){
    
 //$('.changeBackground').each(function(){ $(this).show().attr("alt"); });
      var tab = new Array();
      var i=0;

      $('.changeBackground').each(function(){ tab[i]=$(this).next().html();i++; });                        
      
      var nom_absent = tab.join();
      var date;
 
			/* DATASTRING */
		    var dataString = 'nom_absent='+ nom_absent;
 
 
 
			if(nom_absent=='') {
			
                        
                        
                        $('.success').fadeOut(200).hide();
                        $('.error').fadeOut(200).show();
			
			} else {
			$.ajax({
			type: "POST",
                        url: "insert.php",
                        data: dataString,
		    	success: function(){
					$('.success').fadeIn(200).show();
		    		       $('.error').fadeOut(200).hide();
					
		   		}
			});
				}//EOC
		   return false;
			}); //EOF
 
 
 
$('#btn_up').click(function() {
    $('html,body').animate({scrollTop: 0}, 'slow');
  });

});

$('#vider').click(function(){
  
  $(".changeBackground").toggleClass("changeBackground");
  
});


  $(window).scroll(function(){
     if($(window).scrollTop()>25){
        $('.navbar').fadeOut(1500);
     }else{
        $('.navbar').fadeIn(1500);
     }
     if($(window).scrollTop()<50){
        $('#btn_up').fadeOut();
        
     }else{
        $('#btn_up').fadeIn();
     }
  });

