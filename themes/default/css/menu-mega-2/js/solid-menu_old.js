$(document).ready(function() {

	var bootMenu = {

		init: function() {

			this.cacheDom();
			this.bindEvents();
			this.loadCarousel();
			this.loadNewsTicker();
			this.loadSprites();
			this.calculateDistance();
			this.setResizeOptions();


		},
		cacheDom: function() {

			this.$document = $(document);
			this.$window = $(window);
			this.$html = $('html');
			this.$body = $('body');
			this.$html_body = $('html, body');
			this.$owl_carousel = this.$body.find('.owl-carousel');
			this.$news_ticker = this.$body.find('#js-news');
			this.$solid_menus = $('.solid-menus');
			this.$navbar = this.$body.find('.navbar');
			this.$tab_nav = this.$body.find('.tab-nav');
			this.$tab_nav_links = this.$body.find('.tab-nav > li > a');
			this.$navbar_nav = this.$body.find('.navbar-nav');
			this.$navbar_header = this.$body.find('.navbar-header');
			this.$dropdown = this.$body.find('.dropdown');
			this.$dropdown_custom = this.$body.find('.dropdown-custom');
			this.$dropdown_autoheight = this.$body.find('.dropdown-autoheight');
			this.$dropdown_menu = this.$body.find('.dropdown-menu');
			this.$top_search = this.$body.find('#top-search');
			this.$search_close = this.$body.find('.x-search-close');
			this.$sprite_list = this.$body.find('.s-list-sprite > li > a');
			this.$tile_container = this.$body.find('.tile-container');
			this.$back_2_top = this.$body.find('#back-2-top');
			this.$top_social_links = this.$body.find('#top-social a');
			this.$mini_menu = this.$body.find('.mini-menu');
			this.$mini_nav = this.$body.find('.mini-nav');
			this.$rocket_idle = this.$body.find('#rocket-idle');
			this.$rocket_blast = this.$body.find('#rocket-blast');
			this.$a_w_title = this.$body.find('a[data-title]');

		},
		bindEvents: function() {

			this.$body.on('click mouseenter', '.tab-nav > li > a', this.setActiveTab);
			this.$body.on('click mouseenter mouseleave', '.dropdown', this.showDropDown);
			this.$body.on('click mouseenter', '.dropdown.dropdown-convey-width', this.conveyWidth);
			this.$body.on('click mouseenter', '.dropdown.dropdown-convey-height', this.conveyHeight);
			this.$document.on('click', '.navbar .dropdown-menu', this.stopPropagation);
			this.$body.on('click', '.navbar-click .dropdown-right, .navbar-click .dropdown-left', this.setActiveMenu);
			this.$body.on('click', '.x-search', this.activateTopSearch);
			this.$body.on('click mouseenter', '.dropdown-autoheight', this.calculateHeight);
			this.$body.on('click', '.sub-marker', this.setMenuActive);
			this.$window.on('resize', this.setResizeOptions.bind(this));
			this.$window.on('scroll', this.scrollSettings.bind(this));
			this.$body.on('click', '#back-2-top', this.backToTop);
			this.$document.on('click', 'html', this.clickOutsideNav);
			this.$body.on('mouseenter mouseleave', '#top-social a', this.topSocial);
			this.$body.on('mouseenter mouseleave click', '.mini-nav', this.showDropDownMini);
			this.$body.on('mouseleave','.mini-nav', this.removeBlockSettings);
			this.$body.on('click', '.prev-default', function(event) {event.preventDefault();})
			//this.$body.on('touchstart', '.dropdown', function(){});

		},
		loadCarousel: function() {

			if (this.$owl_carousel.length > 0) {

				this.$owl_carousel.each(function() {

					var _this = $(this);

					var single_item = Boolean(_this.attr('data-single-item'));
	   		 		var items_no = _this.attr('data-items') ? Number(_this.attr('data-items')) : 4;
	   		 		var navigation_value = _this.attr('data-navigation') ? Boolean(_this.attr('data-navigation')) : false;
	    			var autoplay_interval = _this.attr('data-autoplay') ? Number(_this.attr('data-autoplay')) : 3000;
	    			var stop_on_hover = _this.attr('data-stop-on-hover') ? Boolean(_this.attr('data-stop-on-hover')) : false;
	    			var pagination_value = _this.attr('data-pagination') ? Boolean(_this.attr('data-pagination')) : true;
	    			var transition_value = _this.attr('data-transition') ? String(_this.attr('data-transition')) : false;

	    			var items_lg = _this.attr('data-items-lg') ? Number(_this.attr('data-items-lg')) : 4;
	    			var items_md = _this.attr('data-items-md') ? Number(_this.attr('data-items-md')) : 4;
	    			var items_sm = _this.attr('data-items-sm') ? Number(_this.attr('data-items-sm')) : 2;
	    			var items_xs = _this.attr('data-items-xs') ? Number(_this.attr('data-items-xs')) : 1;

	    			if (single_item == true) {

	    				_this.owlCarousel({

	    					lazyLoad: true,
	    					single_item: true,
	    					pagination: false,
	    					autoPlay: autoplay_interval,
	    					navigation: navigation_value,
	    					navigationText: ["<i class='icon-angle-left'></i>","<i class='icon-angle-right'></i>"],
	    					transitionStyle: transition_value

	    				});

	    			} else {

		    			_this.owlCarousel({ 

		    				lazyLoad: true,
		    				items: items_no,
		    				navigation: navigation_value,
		    				navigationText: ["prev","next"],
		        			pagination: pagination_value,
		        			autoPlay: autoplay_interval,
		        			stopOnHover: stop_on_hover,
		        			itemsCustom: [[0, items_xs], [400, items_sm], [700, items_md], [1000, items_lg], [1200, items_lg], [1600, items_lg]],
		   					transitionStyle: transition_value
		   					
		    			});
		    		}

				});

			}

		},
		loadNewsTicker: function() {

			if (this.$news_ticker.length > 0) {

				this.$news_ticker.ticker({

					htmlFeed: true,
					controls: false,
					titleText: '',
					speed: 0.18,
					displayType: 'reveal', 	// Animation type - current options are 'reveal' or 'fade'
					direction: 'ltr',       // Ticker direction - current options are 'ltr' or 'rtl'
					pauseOnItems: 5000,    	// The pause on a news item before being replaced
					fadeInSpeed: 600,      	// Speed of fade in animation
					fadeOutSpeed: 600      	// Speed of fade out animation

				});

			}

		},
		loadSprites: function() {

			if (this.$sprite_list.length > 0) {

				this.$sprite_list.each(function() {

					var _this = $(this);
					var x_pos = _this.attr('data-pos-x');
					var y_pos = _this.attr('data-pos-y');

					_this.css('background-position', x_pos + 'px' + ' ' + y_pos + 'px');

				});

			}

		},
		stopPropagation: function(event) {

			event.stopPropagation();

		},
		setActiveTab: function(event) {

			var _this = $(this);

			var tab_value = _this.attr('data-tabs');
			var parent = _this.parent();
			var siblings = parent.siblings();

			var tab_container = _this.parents('.tab-nav').siblings('.tab-container')
			var target_tab_content = tab_container.children('div#' + tab_value);

			if (event.type == 'mouseenter' && _this.parents('.tab-nav').hasClass('tab-nav-hover')) {

				parent.addClass('ui-tabs-active');
				siblings.removeClass('ui-tabs-active');
				target_tab_content.addClass('l-block').siblings().removeClass('l-block');

			} else if (event.type == 'click') {

				parent.addClass('ui-tabs-active');
				siblings.removeClass('ui-tabs-active');
				target_tab_content.addClass('l-block').siblings().removeClass('l-block');

			}

			//event.preventDefault();

		},
		showDropDown: function(event) {

			var _this = $(this);

			/* for main navbar */

			if (event.type == 'mouseenter' && bootMenu.$navbar.hasClass('navbar-hover')) {

				bootMenu.setDropAnimation(_this);

			} else if (event.type == 'click' && bootMenu.$navbar.hasClass('navbar-click')) {

				bootMenu.setDropAnimation(_this);

			} else if (event.type == 'mouseleave' && bootMenu.$navbar.hasClass('navbar-hover')) {

				bootMenu.$dropdown.removeClass('open');
				bootMenu.$mini_nav.find('.dropdown-menu').removeClass('l-block');

			}

			
		},
		showDropDownMini: function(event) {

			var _this = $(this);

			/* for mini nav */

			if (_this.hasClass('navbar-hover') || _this.hasClass('navbar-click')) {

				if (event.type == 'mouseenter' && bootMenu.$mini_nav.hasClass('navbar-hover')) {

					_this.find('.dropdown-menu').addClass('l-block');
					bootMenu.setDropAnimation(_this);

				} else if (event.type == 'click' && bootMenu.$mini_nav.hasClass('navbar-click')) {

					_this.find('.dropdown-menu').addClass('l-block');
					bootMenu.setDropAnimation(_this);
					_this.parent().siblings().find('.dropdown-menu').removeClass('l-block');

				} else if (event.type == "mouseleave") {

					bootMenu.removeBlockSettings();

				}

			} else {

				_this.find('.dropdown-menu').addClass('l-block');
				bootMenu.setDropAnimation(_this);
				_this.parent().siblings().find('.dropdown-menu').removeClass('l-block');

			}

		},
		setActiveMenu: function(event) {

			var _this = $(this);

			if (_this.hasClass('dropdown-parent')) {

				_this.find('.dropdown-right .dropdown-menu, .dropdown-left .dropdown-menu').removeClass('l-block');
				_this.siblings('.dropdown-parent').find('.dropdown-menu').removeClass('l-block');
				_this.children('.dropdown-menu').toggleClass('l-block');


			} else {

				_this.children('.dropdown-menu').toggleClass('l-block');

			}

			bootMenu.showDropDown(_this);
			event.stopPropagation();

		},
		activateTopSearch: function(event) {

			var _this = $(this);

			if (_this.hasClass('x-search-trigger')) {

				bootMenu.$navbar_header.hide();
				bootMenu.$navbar_nav.hide();
				bootMenu.$top_search.fadeIn(500).find('input').focus();

			} else {

				bootMenu.$top_search.hide()
				bootMenu.$navbar_header.fadeIn(1000);
				bootMenu.$navbar_nav.fadeIn(1100);

			}

			_this.fadeOut().siblings().show();
			
			event.preventDefault();

		},
		calculateHeight: function(event) {

			var _this = $(this);
			var col_autoheight = _this.find('.col-autoheight');
			var dropdown_menu = _this.find('.dropdown-menu');
			var adjustment = dropdown_menu.attr('data-adjust') ? dropdown_menu.attr('data-adjust') : Number(0);
			var computed_h = dropdown_menu.outerHeight();

			if (window.innerWidth > 767) {

				dropdown_menu.css('height', computed_h);
				col_autoheight.css('height', computed_h - adjustment);

			} else {

				dropdown_menu.css('height', 'auto !important');
				col_autoheight.css('height','auto !important');

			}

			
		},
		conveyWidth: function(event) {

			var _this = $(this);
			var dropdown_menu = _this.find('.dropdown-menu');
			var dropdown_menu_c = $('.dropdown.dropdown-convey-width').find('.dropdown-menu');
			var computed_w = _this.children('.dropdown-menu').outerWidth();

			if (window.innerWidth > 767) {

				dropdown_menu.css('width',computed_w);

			} else {

				dropdown_menu_c.css('width','auto');
			}


		},
		conveyHeight: function(event) {

			var _this = $(this);
			var dropdown_custom = _this.children('.dropdown-menu');
			var computed_h = _this.parent('.dropdown-menu').outerHeight();

			if (window.innerWidth > 767) {

				dropdown_custom.css('height',computed_h);

			} else {

				dropdown_custom.css('height','100%');
			}

		},
		calculateDistance: function(event) {

			this.$dropdown_custom.each(function() {

				var _this = $(this);
				var dropdown_menu = _this.children('.dropdown-menu');
				var offset_value_y = Number(dropdown_menu.attr('data-offset-y'));

				if (window.innerWidth > 767) {

					dropdown_menu.css('top', offset_value_y);
				
				} else {

					dropdown_menu.css('top', 'auto');

				}

			});


		},
		setResizeOptions: function() {

			if (window.innerWidth <= 767) {

				this.removeBlockSettings();
				this.conveyWidth();
				this.$navbar.removeClass('navbar-hover').addClass('navbar-click');
				this.$tab_nav.removeClass('tab-nav-hover');
				this.$tab_nav_links.removeClass('prev-default');

			} else {

				this.navOptions();
				this.tabOptions();
				this.calculateDistance();
				this.$tab_nav_links.addClass('prev-default');
				
			}

			/* activating Title when Main Nav goes to Icon only */

			if (window.innerWidth <= 991 && window.innerWidth > 767) {

				this.activateTitle();

			} else {

				this.$a_w_title.removeAttr('title');

			}

		},
		scrollSettings: function() {

			var scroll_distance = this.$back_2_top.attr('data-scroll-distance');

			if (this.$window.scrollTop() > scroll_distance) {

				this.$back_2_top.fadeIn();
				
			} else {

				this.$back_2_top.fadeOut();
				this.$rocket_idle.show();
				this.$rocket_blast.hide(); /* rocket blast icon */

			}


		},
		backToTop: function(event) {

			var _this = $(this);
			var easing_value = _this.data('easing') ? _this.data('easing') : 'swing';

			bootMenu.$html_body.animate({scrollTop:0}, 1250, easing_value);

			_this.children('#rocket-blast').show().addClass('animated shake');
			_this.children('#rocket-idle').hide();

		},
		navOptions: function() {

			if (this.$navbar.hasClass('navbar-click-strict')) {

				this.$navbar.addClass('navbar-click').removeClass('navbar-hover');		

			} else {

				this.$navbar.removeClass('navbar-click').addClass('navbar-hover');
			}

		},
		tabOptions: function() {

			if (this.$tab_nav.hasClass('tab-nav-click-strict')) {

				this.$tab_nav.removeClass('tab-nav-hover');		

			} else {

				this.$tab_nav.addClass('tab-nav-hover');
			}

		},
		clickOutsideNav: function(event) {

			if ($(event.target).closest('#nav-section').length == 0 ) {


				bootMenu.removeBlockSettings();

			}

		},
		removeBlockSettings: function() {

			bootMenu.$dropdown_menu.removeClass('l-block');

		},
		setMenuActive: function(event) {

			event.preventDefault();

		},
		setDropAnimation: function(_this) {

			var animation_value = _this.attr('data-animation') ? _this.attr('data-animation') : 'fadeIn'; //if data-animation is missing, default is fadeIn
			var dropdown_menu = _this.find('.dropdown-menu').first();
		
			dropdown_menu.removeClass('animated').addClass('animated ' + animation_value);
				
		},
		topSocial: function(event) {

			var _this = $(this);
			var hover_width = _this.data('hover-width');
			var hover_bg = _this.data('hover-bg');

			if (event.type == 'mouseenter') {

				_this.css({'width': hover_width,'background-color': hover_bg});

			} else if (event.type == 'mouseleave') {

				bootMenu.$top_social_links.css({'width': '40','background-color': 'transparent'});

			}

		},
		activateTitle: function() {

			if (this.$a_w_title.length > 0) {

				this.$a_w_title.each(function() {

					var _this = $(this);
					var title_value = _this.data('title')

					_this.attr('title', title_value);


				});

			}


		}

	}

	bootMenu.init();

	/* settings - you can safely remove this in production */

	$('.color-box').on('click', 'li', function() {

		var _this = $(this);
		var navbar = $('.navbar');
		var mini_menu = $('.mini-menu');
		var theme_value = _this.attr('data-theme');
		var theme_value_s = _this.attr('data-theme-s'); 

		navbar.removeClass('navbar-default-light navbar-default-dark navbar-default-blue').addClass(theme_value);
		mini_menu.removeClass('mini-menu-light mini-menu-dark mini-menu-blue').addClass(theme_value_s);

	});

	$('#position-settings').on('click', 'button', function() {

		var _this = $(this);
		var pos_value = _this.attr('data-pos');
		var wrapper = $('#wrapper');
		var outer_container = $('#solidMenus');
		var row_carousel = $('#row-carousel');
		var navbar = $('.navbar');
		var navbar_inner = $('#navbar-inner-container');
		var main_carousel = $('#main-carousel');
		var page_contents = $('#page-contents');

		outer_container.removeClass('container-fluid container');
		navbar_inner.removeClass('container-fluid container');
		navbar.removeClass('navbar-static-top navbar-fixed-top');
		page_contents.removeClass('container-fluid container');

		switch (pos_value) {

			case 'default': 

				wrapper.css({'padding-top': '20px','padding-bottom': '20px'});
				outer_container.addClass('container-fluid').removeAttr('style');
				navbar_inner.addClass('container-fluid');
				row_carousel.removeAttr('style');
				page_contents.addClass('container-fluid');

			break;

			case 'default-short':

				wrapper.css({'padding-top': '20px','padding-bottom': '20px'});
				outer_container.addClass('container').removeAttr('style');
				navbar_inner.addClass('container-fluid');
				row_carousel.removeAttr('style');
				page_contents.addClass('container');

			break;

			case 'static-top':

				wrapper.css('padding-top','0');
				outer_container.addClass('container-fluid').css({'padding-left': 0, 'padding-right': 0});
				navbar_inner.addClass('container-fluid');
				navbar.addClass('navbar-static-top');
				row_carousel.css({'padding': '20px 15px','margin-top': '0'});
				page_contents.addClass('container-fluid');

			break;

			case 'static-top-short':

				wrapper.css('padding-top','0');
				outer_container.addClass('container-fluid').css({'padding-left': 0, 'padding-right': 0});
				navbar_inner.addClass('container');
				navbar.addClass('navbar-static-top');
				row_carousel.css({'padding': '20px 15px','margin-top': '0'});
				page_contents.addClass('container-fluid');

			break;

			case 'fixed-top':

				wrapper.css('padding-top','0');
				outer_container.addClass('container-fluid').css({'padding-left': 0, 'padding-right': 0});
				navbar_inner.addClass('container-fluid');
				navbar.addClass('navbar-fixed-top');
				row_carousel.css({'padding': '15px','margin-top': '55px'});
				page_contents.addClass('container-fluid');

			break;

			case 'fixed-top-short':

				wrapper.css('padding-top','0');
				outer_container.addClass('container-fluid').css({'padding-left': 0, 'padding-right': 0});
				navbar_inner.addClass('container');
				navbar.addClass('navbar-fixed-top');
				row_carousel.css({'padding': '15px','margin-top': '55px'});
				page_contents.addClass('container-fluid');

			break;

		}

	});

});