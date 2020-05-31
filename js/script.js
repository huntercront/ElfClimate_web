document.addEventListener('DOMContentLoaded', function(){

    $('body').on('click','[data-anchor=true]', function() {
        var elementClick = $(this).attr("href")
        var destination = ($(elementClick).offset().top - 74);
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

//tabs
$(".tab-t").on("click",function(e){
	$('.tab-t').removeClass('tab-t-active')
	$(this).addClass('tab-t-active');

	var activeWidth = $(this).innerWidth();
	var itemPos = $(this).position();
	$(".tab-t-selector").css({
		"left":itemPos.left + "px", 
		"width": activeWidth + "px"
	});

	$('.offer').removeClass('active-tab');
	$('[data-tab='+$(this).attr('data-select-tab')+']').addClass('active-tab');
	
})

if($('div').is(".tab-t-selector")){
$(".tab-t-selector").css({
	"left":$('.tab-t-active').position().left + "px", 
	"width": $('.tab-t-active').innerWidth() + "px"
});
}

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


//modal
function hideModal(){
	$('[data-modal]').removeClass('visible-modal');
	$('.modal-overley').removeClass('modal-overley-show');
	setTimeout(function(){
		$('body').removeClass('stop-scroll');
		$('body').css('padding-right',0+'px');
		$('.site-header').css('padding-right',0+'px')
	}, 300);
}
$(".close-modal, .modal-overley").on("click",function(e){
	hideModal()
})

$(document).keydown(function(eventObject){
	if (eventObject.which == 27)
	hideModal()
});

$('[data-modal-open]').on("click",function(e){
	event.preventDefault()

	$('[data-modal='+ $(this).attr('data-modal-open') +']').addClass('visible-modal')
	if($(this).attr('data-modal-open')==1){
	$('[data-modal=1] .modal-header-t').text($(this).prev('.cb-c-text').find('.bloc-t').text());
	$('[data-modal=1] .modal-hero-descr').text($(this).prev('.cb-c-text').find('.descr').text());
	$('[data-modal=1] .modal-hero-img').attr('src',$(this).next('.cb-c-img').attr('src'));
}
if($(this).attr('data-modal-open')==3){
	$('[data-modal=3] .centured-m-t').text($(this).parentsUntil('.hf-t').find('h1').text());
	$('[data-modal=3] .centured-m-d').text($(this).parentsUntil('.hf-t').find('.hero-content p:first-child').text());
	$('[data-modal=3] .main-request-img').attr('src',$('.hf-img img').attr('src'));
}
	$('.modal-overley').addClass('modal-overley-show');
	$('body').addClass('stop-scroll');
	$('body').css('padding-right',getScrollBarWidth ()+'px');
	$('.site-header').css('padding-right',getScrollBarWidth ()+'px');
})

// centured-modal-close
$('.centured-modal').on("click",function(e){
	hideModal()
}).children()
.click(function(e){ 
		e.stopPropagation();
})


//post-nav
if($('div').is('.post-nav-container')){

	if($(window).width() > 768){
$(".sp-text-col h2,.sp-text-col h3,.sp-text-col h4").each(function(i) {
    var current = $(this);
    current.attr("id", "title" + i);
    $(".sp-links .post-nav-container").append("<a calass='post-nav-link' id='link" + i + "' href='#title" + i + "' title='" + $(this).text() + "' data-anchor='" + true + "'>" + current.html() + "</a>");
});

//post-nav-active
$(window).scroll(function(){
var $sections = $('.sp-text-col h2,.sp-text-col h3,.sp-text-col h4');
$sections.each(function(i,el){

 var top  = $(el).offset().top;
 console.log($(window).scrollTop())
 var id = $(el).attr('id');
if( (top - $(window).scrollTop()) > 0 && (top - $(window).scrollTop()) < 120){
		 $('.post-nav-link-active').removeClass('post-nav-link-active');
$('a[href="#'+id+'"]').addClass('post-nav-link-active');
$(".post-nav-selector").css({
	"top":$('a[href="#'+id+'"]').position().top + "px", 
	"height": $('a[href="#'+id+'"]').innerHeight() + "px"
});

 }

})
});
}else{
	$(".sp-text-col h2,.sp-text-col h3,.sp-text-col h4").each(function(i) {
    var current = $(this);
    current.attr("id", "title" + i);
    $(".sp-mobile-nav .post-nav-container").append("<a calass='post-nav-link' id='link" + i + "' href='#title" + i + "' title='" + $(this).text() + "' data-anchor='" + true + "'>" + current.html() + "</a>");
});
}
}



//like-button
$('.sp-like-button').on("click",function(e){
$(this).addClass('sp-like-button-liked');
$('.sp-l-text').text(Number.parseInt($('.sp-l-text').text())+1);
})






//quiz
var curentStage=1;
$('.cond-quiz-counter-curent').text(curentStage);
$('.cond-quiz-footer-next').prop('disabled',true)

$('.count-line-counter').css('width',(100 / $('.cond-quiz-body').length) + '%')
$('.c-check input').on("change",function(e){
if($('.c-check input:checked').length > 0){
	$('.cond-quiz-footer-next').prop('disabled',false)
}
else{
	$('.cond-quiz-footer-next').prop('disabled',true)
}
	})

	$('.c-q-c-c-item').on("click",function(e){
		$(this).toggleClass('c-q-c-c-item-selected')
		if($('.c-q-c-c-item-selected').length > 0){
			$('.cond-quiz-footer-next').prop('disabled',false)
		}
		else{
			$('.cond-quiz-footer-next').prop('disabled',true)
		}
})



	$('.cond-quiz-footer-next').on("click",function(e){
if(curentStage==1){
		$('[data-stage='+curentStage+'] .c-check input:checked').each(function(i) {
			var values = $('[data-field-id=field--1]').val();
			values += $(this).parent('.c-check').text();
			$('[data-field-id=field--1]').val(values.replace(/\s/g, '')+',');
		});
	}

	if(curentStage==2){
$('.c-q-c-c-item-selected').each(function(i) {
	var values = $('[data-field-id=field--2]').val();
	values += $(this).find('.c-q-c-c-item-descr').text();
	$('[data-field-id=field--2]').val(values.replace(/\s/g, '')+',');
});
}

if(curentStage==3){
	$('[data-stage='+curentStage+'] .c-check input:checked').each(function(i) {
		var values = $('[data-field-id=field--3]').val();
		values += $(this).parent('.c-check').text();
		$('[data-field-id=field--3]').val(values.replace(/\s/g, '')+',');
	});
}

$('[data-stage='+curentStage+']').removeClass('active-stage')
$('[data-stage='+curentStage+']').addClass('disable-stage')
$('[data-stage='+curentStage+']').fadeOut(1);

curentStage = curentStage + 1;
$('[data-stage='+curentStage+']').fadeIn();
$('[data-stage='+curentStage+']').removeClass('disable-stagea')
$('[data-stage='+curentStage+']').addClass('ctive-stage')
$('.cond-quiz-counter-curent').text(curentStage);
$('.cond-quiz-footer-next').prop('disabled',true)
$('.count-line-counter').css('width',(100 / $('.cond-quiz-body').length)*curentStage + '%')
if(curentStage==4){
	$(this).parent('.cond-quiz-footer').fadeOut(1);
}

	})



// read-more

$(".prod-descr-all").each(function(){
	var review_full = $(this).html();
	var review = review_full;
	if( review.length > 120)
	{
			review = review.substring(0, 120);
			$(this).html( review + '...' + '<div class="prod-read-more s-c">Полное описание<div class="c-c"><img src="../img/icons/chevron-right.svg"></div></div>' );
	}
	$(this).append('<div class="full_text" style="display: none;">' + review_full + '</div>');
});
$(".prod-read-more").click(function(){
	$(this).parent().html( $(this).parent().find(".full_text").html() );
});




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