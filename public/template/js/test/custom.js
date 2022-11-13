/* JS Document */

/******************************

 [Table of Contents]

 1. Vars and Inits
 2. Set Header
 3. Init Menu
 4. InitDeptSlider
 5. Init Accordions


 ******************************/

$(document).ready(function()
{
	"use strict";

	/*

	1. Vars and Inits

	*/

	var header = $('.header');
	var menu = $('.menu');
	var menuActive = false;

	// setHeader();

	$(window).on('resize', function()
	{
		setHeader();

		setTimeout(function()
		{
			$(window).trigger('resize.px.parallax');
		}, 375);
	});

	$(document).on('scroll', function()
	{
		// setHeader();
	});

	initMenu();
	// initDeptSlider();
	// initAccordions();

	/*

	2. Set Header

	*/

	function setHeader()
	{
		if($(window).scrollTop() > 1)
		{
			header.addClass('scrolled');
		}
		else
		{
			header.removeClass('scrolled');
		}
	}

	/*

	3. Init Menu

	*/

	function initMenu()
	{
		if($('.hamburger').length && $('.menu').length)
		{
			var hamb = $('.hamburger');
			var close = $('.menu_close_container');

			hamb.on('click', function()
			{
				if(!menuActive)
				{
					openMenu();
				}
				else
				{
					closeMenu();
				}
			});

			close.on('click', function()
			{
				if(!menuActive)
				{
					openMenu();
				}
				else
				{
					closeMenu();
				}
			});


		}
	}

	function openMenu()
	{
		menu.addClass('active');
		menuActive = true;
	}

	function closeMenu()
	{
		menu.removeClass('active');
		menuActive = false;
	}

	/*

	4. Init Dept Slider

	*/

	function initDeptSlider()
	{
		if($('.dept_slider').length)
		{
			var deptSlider = $('.dept_slider');
			deptSlider.owlCarousel(
				{
					items:2,
					autoplay:true,
					loop:false,
					nav:false,
					dots:false,
					margin:30,
					smartSpeed:1200,
					responsive:
						{
							0:{items:1},
							768:{items:2},
							992:{items:1},
							1200:{items:1}
						}
				});

			if($('.dept_slider_nav').length)
			{
				var next = $('.dept_slider_nav');
				next.on('click', function()
				{
					deptSlider.trigger('next.owl.carousel');
				});
			}
		}
	}

	/*

	5. Init Accordions

	*/

	function initAccordions()
	{
		if($('.accordion').length)
		{
			var accs = $('.accordion');

			accs.each(function()
			{
				var acc = $(this);

				if(acc.hasClass('active'))
				{
					var panel = $(acc.next());
					var panelH = panel.prop('scrollHeight') + "px";

					if(panel.css('max-height') == "0px")
					{
						panel.css('max-height', panel.prop('scrollHeight') + "px");
					}
					else
					{
						panel.css('max-height', "0px");
					}
				}

				acc.on('click', function()
				{
					if(acc.hasClass('active'))
					{
						acc.removeClass('active');
						var panel = $(acc.next());
						var panelH = panel.prop('scrollHeight') + "px";

						if(panel.css('max-height') == "0px")
						{
							panel.css('max-height', panel.prop('scrollHeight') + "px");
						}
						else
						{
							panel.css('max-height', "0px");
						}
					}
					else
					{
						acc.addClass('active');
						var panel = $(acc.next());
						var panelH = panel.prop('scrollHeight') + "px";

						if(panel.css('max-height') == "0px")
						{
							panel.css('max-height', panel.prop('scrollHeight') + "px");
						}
						else
						{
							panel.css('max-height', "0px");
						}
					}
				});
			});
		}
	}

});
function replaceStr(){
	var str = $('ul.sub_menu_meta li.active').text();
	$('ul.sub_menu_meta li:nth-of-type(1) span').text(str);
}

$(document).ready(function(){
	replaceStr(); // Run once when page loaded

	$('ul.sub_menu_meta a').click(function(){
		$(this).parent('li').addClass('active').siblings('li').removeClass('active');
		$('ul.sub_menu_meta').toggleClass('open');
		replaceStr(); // Run each time user click a item
	});

	$('ul.sub_menu_meta span').click(function(){
		$('ul.sub_menu_meta').toggleClass('open');
	});
});

$(window).resize(function(){
	// Refresh current selected status of menu when window resize(RWD)
	replaceStr();

	// Reset menu open status when window resize(RWD)
	$('ul.sub_menu_meta').removeClass('open');
});
$(document).ready(function()
{
	"use strict";

	/*

    1. Vars and Inits

    */

	var header = $('.header');
	var hambActive = false;
	var menuActive = false;

	setHeader();

	$(window).on('resize', function()
	{
		setHeader();
		setTimeout(function()
		{
			$(window).trigger('resize.px.parallax');
		}, 375);
	});



	// $(document).on('scroll', function()
	// {
		// setHeader();
	// });

	// initHomeSlider();
	// initSearch();
	initMenu();
	// initIsotope();

	/*

    2. Set Header

    */

	function setHeader()
	{
		if($(window).scrollTop() > 1)
		{
			header.addClass('scrolled');
		}
		else
		{
			header.removeClass('scrolled');
		}
	}

	/*

    3. Init Home Slider

    */

	function initHomeSlider()
	{
		if($('.home_slider').length)
		{
			var homeSlider = $('.home_slider');
			homeSlider.owlCarousel(
				{
					items:1,
					autoplay:true,
					autoplayTimeout:10000,
					loop:true,
					nav:false,
					smartSpeed:1200,
					dotsSpeed:1200,
					fluidSpeed:1200
				});

			/* Custom dots events */
			if($('.home_slider_custom_dot').length)
			{
				$('.home_slider_custom_dot').on('click', function()
				{
					$('.home_slider_custom_dot').removeClass('active');
					$(this).addClass('active');
					homeSlider.trigger('to.owl.carousel', [$(this).index(), 1200]);
				});
			}

			/* Change active class for dots when slide changes by nav or touch */
			homeSlider.on('changed.owl.carousel', function(event)
			{
				$('.home_slider_custom_dot').removeClass('active');
				$('.home_slider_custom_dots li').eq(event.page.index).addClass('active');
			});

			// add animate.css class(es) to the elements to be animated
			function setAnimation ( _elem, _InOut )
			{
				// Store all animationend event name in a string.
				// cf animate.css documentation
				var animationEndEvent = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';

				_elem.each ( function ()
				{
					var $elem = $(this);
					var $animationType = 'animated ' + $elem.data( 'animation-' + _InOut );

					$elem.addClass($animationType).one(animationEndEvent, function ()
					{
						$elem.removeClass($animationType); // remove animate.css Class at the end of the animations
					});
				});
			}

			// Fired before current slide change
			homeSlider.on('change.owl.carousel', function(event)
			{
				var $currentItem = $('.home_slider_item', homeSlider).eq(event.item.index);
				var $elemsToanim = $currentItem.find("[data-animation-out]");
				setAnimation ($elemsToanim, 'out');
			});

			// Fired after current slide has been changed
			homeSlider.on('changed.owl.carousel', function(event)
			{
				var $currentItem = $('.home_slider_item', homeSlider).eq(event.item.index);
				var $elemsToanim = $currentItem.find("[data-animation-in]");
				setAnimation ($elemsToanim, 'in');
			})
		}
	}

	/*

    4. Init Search

    */

	function initSearch()
	{
		if($('.search').length && $('.search_panel').length)
		{
			var search = $('.search');
			var panel = $('.search_panel');

			search.on('click', function()
			{
				panel.toggleClass('active');
			});
		}
	}

	/*

    5. Init Menu

    */

	function initMenu()
	{
		if($('.hamburger').length)
		{

			var hamb = $('.hamburger');

			hamb.on('click', function(event)
			{
				$('.menu-overlay').css({'display':'block'});
				event.stopPropagation();

				if(!menuActive)
				{
					openMenu();

					$(document).one('click', function cls(e)
					{
						if($(e.target).hasClass('menu_mm'))
						{
							$(document).one('click', cls);

						}
						else
						{
							closeMenu();
							$('.menu-overlay').css('display','none');

						}
					});
				}
				else
				{
					$('.menu').removeClass('active');
					menuActive = false;
				}
			});

			//Handle page menu
			if($('.page_menu_item').length)
			{
				var items = $('.page_menu_item');
				items.each(function()
				{
					var item = $(this);

					item.on('click', function(evt)
					{
						if(item.hasClass('has-children'))
						{
							evt.preventDefault();
							evt.stopPropagation();
							var subItem = item.find('> ul');
							if(subItem.hasClass('active'))
							{
								subItem.toggleClass('active');
								TweenMax.to(subItem, 0.3, {height:0});
							}
							else
							{
								subItem.toggleClass('active');
								TweenMax.set(subItem, {height:"auto"});
								TweenMax.from(subItem, 0.3, {height:0});
							}
						}
						else
						{
							evt.stopPropagation();
						}
					});
				});
			}
		}
	}

	function openMenu()
	{
		var fs = $('.menu');
		fs.addClass('active');

		hambActive = true;
		menuActive = true;
	}

	function closeMenu()
	{
		var fs = $('.menu');
		fs.removeClass('active');

		hambActive = false;
		menuActive = false;
	}

	/*

    6. Init Isotope

    */

	function initIsotope()
	{
		var sortingButtons = $('.product_sorting_btn');
		var sortNums = $('.num_sorting_btn');

		if($('.product_grid').length)
		{
			var grid = $('.product_grid').isotope({
				itemSelector: '.product',
				layoutMode: 'fitRows',
				fitRows:
					{
						gutter: 30
					},
				getSortData:
					{
						price: function(itemElement)
						{
							var priceEle = $(itemElement).find('.product_price').text().replace( '$', '' );
							return parseFloat(priceEle);
						},
						name: '.product_name',
						stars: function(itemElement)
						{
							var starsEle = $(itemElement).find('.rating');
							var stars = starsEle.attr("data-rating");
							return stars;
						}
					},
				animationOptions:
					{
						duration: 750,
						easing: 'linear',
						queue: false
					}
			});
		}
	}

});
$( document ).ready( function() {
	$( '.dropdown' ).on( 'show.bs.dropdown', function() {
		$( this ).find( '.dropdown-menu' ).first().stop( true, true ).slideDown( 350 );
	} );
	$('.dropdown').on( 'hide.bs.dropdown', function(){
		$( this ).find( '.dropdown-menu' ).first().stop( true, true ).slideUp( 350 );
	} );
} );
