//galery
if($('.catalog-section').is('[data-fancybox]')){
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
	}	
	