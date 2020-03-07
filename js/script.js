document.addEventListener('DOMContentLoaded', function(){

    $('[data-anchor=true]').click(function() {
        var elementClick = $(this).attr("href")
        var destination = ($(elementClick).offset().top - 65);
        jQuery("html:not(:animated),body:not(:animated)").animate({
            scrollTop: destination
        }, 500);
        return false;
    });


// toggle


      $(".foo-t-line").on('click',function(event){
        var panel = $(this).next(".m-toggle");
            if($(window).width() < 521) {
            if (panel.css('max-height')=='0px'){
                $(this).toggleClass('foot-t-active')
                panel.css('max-height',panel.prop('scrollHeight') + "px")
            } else {
                panel.css('max-height','0px');
                $(this).toggleClass('foot-t-active')
            } 
        }
    })




//ripple
$(document).on("mousedown", "[data-ripple]", function(e) {
    
    var $self = $(this);
    
    if($self.is(".btn-disabled")) {
      return;
    }
    if($self.closest("[data-ripple]")) {
      e.stopPropagation();
    }
    
    var initPos = $self.css("position"),
        offs = $self.offset(),
        x = e.pageX - offs.left,
        y = e.pageY - offs.top,
        dia = Math.min(this.offsetHeight, this.offsetWidth, 100), // start diameter
        $ripple = $('<div/>', {class : "ripple",appendTo : $self });
    
    if(!initPos || initPos==="static") {
      $self.css({position:"relative"});
    }
    
    $('<div/>', {
      class : "rippleWave",
      css : {
        background: $self.data("ripple"),
        width: dia,
        height: dia,
        left: x - (dia/2),
        top: y - (dia/2),
      },
      appendTo : $ripple,
      one : {
        animationend : function(){
          $ripple.remove();
        }
      }
    });
  });    
});