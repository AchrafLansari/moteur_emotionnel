$(document).ready(function() {
   
    $("#search").css("display","none");
    $("#icon-search").one("click", function(){
        
        $(".lien-search").attr("onclick","document.searchForm.submit();");
        if($("#search").css("display")=="none"){

       $("#search").animate({
        width:"35%" ,
        left:"+=80",
        display:"block",   
        opacity:"toggle"
}, 1500 )
      
        } 
        return false;
});

});