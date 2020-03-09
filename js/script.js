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
							panel.attr('style','');
                $(this).toggleClass('foot-t-active')
            } 
        }
    })

		$(".links-nav-parent").on('click',function(event){
			var panel = $(this).children(".links-nav-child");
					if($(window).width() < 963) {
					if (panel.css('max-height')=='0px'){
							$(this).toggleClass('links-nav-parent-active')
							panel.css('max-height',panel.prop('scrollHeight') + "px")
					} else {
							panel.attr('style','');
							$(this).toggleClass('links-nav-parent-active')
					} 
			}
	})


//menu-script	
$('.m-nav-icon').on('click',function(event){
	$('.header-overley').addClass('show-overlay');
	$('body').addClass('stop-scroll');
	$('.m-nav-hiiden').addClass('m-nav-hiiden-active')
})
$('.close-menu-icon, .header-overley').on('click', function(){
	$('.header-overley').removeClass('show-overlay');
	$('body').removeClass('stop-scroll');
	$('.m-nav-hiiden').removeClass('m-nav-hiiden-active')
})

//get scroll-width
function getScrollBarWidth () {
	var $outer = $('<div>').css({visibility: 'hidden', width: 100, overflow: 'scroll'}).appendTo('body'),
			widthWithScroll = $('<div>').css({width: '100%'}).appendTo($outer).outerWidth();
	$outer.remove();
	return 100 - widthWithScroll;
};


//galery	
$("[data-fancybox]").fancybox({
    buttons: [
        "close"
    ],
    transitionEffect: "slide",
    loop: true,
    lang: "ru",
    clickOutside: "close",
    mobile : {
        arrows: false,
        gutter: 0,
},

i18n: {
ru: {
  CLOSE: "Закрыть",
  NEXT: "Следующее",
  PREV: "Предыдущее",
  ERROR: "Не удалось загрузить.<br/> Пожалуйста попробуйте позднее.",
    }},
    
btnTpl: {
        arrowLeft:
            '<button data-fancybox-prev class="fancybox-button fancybox-button--arrow_left" title="{{PREV}}">' +
            '<div><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7 12"><path d="M1.88562 5.80474L6.74755 0.942809L5.80474 0L0 5.80474L5.80474 11.6095L6.74755 10.6667L1.88562 5.80474Z"/></svg></div>' +
            "</button>",

        arrowRight:
            '<button data-fancybox-next class="fancybox-button fancybox-button--arrow_right" title="{{NEXT}}">' +
            '<div><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7 12"><path d="M4.86193 5.80474L0 0.942809L0.942809 0L6.74755 5.80474L0.942809 11.6095L0 10.6667L4.86193 5.80474Z"/></svg></div>' +
            "</button>",
},
beforeShow: function() {
    $('.site-header').css('padding-right',getScrollBarWidth ()+'px') 
    },
    afterClose: function() {
    $('.site-header').css('padding-right',0+'px') 
    },
});




if($(window).width() > 992) {
$(window).scroll(function(){
	if($(this).scrollTop()>400){
			$('.site-header').addClass('fixed-header');
			$('body').addClass('nav-fixed');
	}
	if ($(this).scrollTop()<350 && $('.site-header').hasClass('fixed-header')){
		$('.site-header').addClass('header-animated');
}
if ($(this).scrollTop()>350 && $('.site-header').hasClass('header-animated')){
	$('.site-header').removeClass('header-animated');
}
if ($(this).scrollTop() < 200){
			$('.site-header').removeClass('fixed-header');
			$('.site-header').removeClass('header-animated');
			$('body').removeClass('nav-fixed');
	}
});
}

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