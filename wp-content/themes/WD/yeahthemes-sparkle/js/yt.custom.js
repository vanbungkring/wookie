/*!
 * Yeahthemes
 *
 * Custom Javascript
 */
if (typeof Yeahthemes == 'undefined') {
	var Yeahthemes = {};
}

;(function($) {
	
	"use strict";
	
	if (typeof Yeahthemes == 'undefined') {
	   return;
	}
	/**
	 * Yeahthemes.RitaMagazine
	 *
	 * @since 1.0
	 */
	Yeahthemes.RitaMagazine = {
		
		std:{},
		
		/**
		 * Init Function
		 * @since 1.0
		 */
		init: function(){
			
			var self = this,
				_framework = Yeahthemes.Framework;
				
			this._vars = Yeahthemes.themeVars;

			/*Prevent fast clicking*/
			
			//console.log(Yeahthemes);

			var isScrolling = false,
				isStoppedScrolling = false,
				srollStopTimeout,
				fixMenuInterval,
				$event = Yeahthemes.Framework.helpers.isTouch() ? 'touch' : 'mouseover';
			
			/**
			 * On load
			 */
			$(window).on('load', function(){
				
				
				//console.log('Everything is fully loaded');
				/**
				 *  Re-update Equal height column
				 */

				_framework.helpers.equalHeight('.site-main .boundary-column');
				
				if( $('.yeahslider:not(.initialized)').length ){
					$('.yeahslider:not(.initialized)').each(function(index, element) {
						Yeahthemes.Framework.ux.yeahSlider( $(this) );
					});
				}
							
				/**
				 * Document ready
				 */

				if( $( '.yt-sliding-tabs-header' ).length ){
					//console.log($( '.yt-sliding-tabs-header' ).length);
					$( '.yt-sliding-tabs-header' ).each( function(){
						var $el = $(this);
					
						_framework.ui.hoverScrollHorizontalHelperRestoreCurrentItem( $el, $( '> ul', $el ) );
						if( !_framework.helpers.isTouch()){
							_framework.ui.hoverScrollHorizontal( $el, $( '> ul', $el ) );

						}
					});
				}

				if(!$( '#yt-page-loader' ).length ){
					$('<span/>', {
						'id': 'yt-main-spinner',
						'class': 'yt-preloader yt-ajax-loader'
					}).appendTo('body');
				}	
				
			}).on('scroll', function(){

				isScrolling = true;
				//console.log("isScrolling = true");

				if( $('body').hasClass( 'scroll-fix-header') && $('body').hasClass( 'desktop') ){
					if ( isScrolling ) {
						//$('.site-top-menu').outerHeight() to $('.site-header').offset().top
						if ($(window).scrollTop() >= $('.site-top-menu').outerHeight() && $(window).width() > 1024) {
					       $('.site-banner').css({
					       	'position': 'fixed',
					       	'top': $('body').hasClass('admin-bar') ? 32 : 0,
					       	'left': 0,
					       	'right': 0
					       });

					       $('.site-header').css('padding-bottom', $('.site-banner').outerHeight());
					       $('.site-header').addClass('fixed-site-banner');
					    }
					    else {
					       $('.site-banner, .site-header').removeAttr('style');
					       $('.site-header').removeClass('fixed-site-banner');
					    }

					}

				}


				clearTimeout($.data(this, 'scrollTimer'));
			    $.data(this, 'scrollTimer', setTimeout(function() {
			        // do something
			        //console.log("isScrolling = false");
			        
			        isScrolling = false;
			    }, 250));


				


			}).on('resize', function(){

				if( typeof resizeTimeOut !== 'undefined' )
					clearTimeout(resizeTimeOut);

				var resizeTimeOut = setTimeout( function(){
				    // Haven't resized in 100ms!
				    _framework.helpers.equalHeight('.site-main .boundary-column');
				    //console.log('called setTimeout')
				}, 100);
			}).on('srollStop', function(){
				
			})
			;
			/**
			 * Attach the event handler functions for theme element
			 */

			$(document)
				/*Smooth scroll for anchor*/
				.on('click', 'a[href*=#]', _framework.ui.smoothAnchor)
				/*Mobile Menu*/
				.on('click', '.site-mobile-menu-toggle', self.ux.mobileNavigation )
				/*Social Sharing*/
				.on('click', '.yt-social-sharing span:not(.show-all-social-services)', _framework.ui.socialSharing)
				.on('click', '.show-all-social-services', _framework.ui.socialSharingMore)
				.on('click', '.yt-font-size-changer span', _framework.ui.fontSizeChanger)
				/*Tabby tab*/
				.on('click', '.yt-tabby-tabs-header ul li', _framework.ui.tabbyTabs )
				/*Sliding Cat*/
				.on('click', '.yt-sliding-tabs-header-trigger', self.ui.toggleSlidingCat )
				.on('click', '.site-banner', _framework.ux.tapToTop )
				.on('click', '.yt-ajax-posts-by-cat .yt-sliding-tabs-header ul li a', self.ux.widgetAjaxPostsByCategory )
				
				
				/*Comment form*/
				.on('submit', '#commentform', _framework.ui.commentForm )
				.on('submit', 'form.yt-mailchimp-subscribe-form', _framework.widgets.mailchimpSubscription )

				
				.on( 'ready', function(){
					// Target your .container, .wrapper, .post, etc.
		   			$('#primary, #secondary, #tertiary').fitVids();
					
					/**
					 * Equal height
					 */
					_framework.helpers.equalHeight('.site-main .boundary-column');
					
					self.ux.mainNavigation();


					if( !$('body').hasClass('phone') ){
						var siteHeroBanner = $('.site-hero'),
							siteHeroEffect = siteHeroBanner.data('effect') !== undefined ? siteHeroBanner.data('effect') : 'flipIn';
						if (typeof(imagesLoaded) !== 'undefined' && typeof(imagesLoaded) === 'function') {
							siteHeroBanner.imagesLoaded( function(){
								siteHeroBanner.removeClass('yt-loading');
								_framework.ux.animateSequence( '.site-hero', 'article', siteHeroEffect, 100, false );

								if( siteHeroBanner.hasClass('carousel') && siteHeroBanner.find('.yeahslider').length ){
									var siteHeroBannerCarousel = siteHeroBanner.find('.yeahslider');
									Yeahthemes.Framework.ux.yeahSlider( siteHeroBannerCarousel );
								}
							});
						}else{
							_framework.ux.animateSequence( '.site-hero', 'article', siteHeroEffect, 200, false );
						}
						
						
					}else{
						$('.site-hero').removeClass('yt-loading').find('article').removeClass('visibility-hidden');
					}
					
					
					
				})
				.on( 'post-load', function () {
					/*Flexslider*/
					// New posts have been added to the page. by jetpack infinite scroll
					if( $('.yeahslider:not(.initialized)').length ){
						$('.yeahslider:not(.initialized)').each(function(index, element) {

							Yeahthemes.Framework.ux.yeahSlider( $(this) );
						});
					}
					
					/*Media element*/
					if( $( '.wp-audio-shortcode' ).length || $( '.wp-video-shortcode' ).length ){
						$('.wp-audio-shortcode:not(.mejs-container), .wp-video-shortcode:not(.mejs-container)').mediaelementplayer();
					}
					/*Media element*/
					if( $( 'article.format-video' ).length ){
						$( 'article.format-video' ).fitVids();
					}
					
					
				} )
				.on( 'touch' == $event ? 'click' : 'mouseover', '.main-navigation .sub-category-menu .sub-menu li', self.ux.mainNavigationSubmenu )
				;/*end $(document)*/
				
			
			$(window).on('scroll', function (e) {
				var scrollTop = $(this).scrollTop();
				
				var delta = null;
				if (e.wheelDelta){
					//IE Opera
					delta = e.wheelDelta;
				}else if (e.originalEvent.detail){
					//Firefox
					delta = e.originalEvent.detail * -40;	
				}else if (e.originalEvent && e.originalEvent.wheelDelta){
					//Webkit
					delta = e.originalEvent.wheelDelta;
				}
				
				if (delta >= 0) {
					
					/*Scroll up*/
				} else if (delta = 0) {
					
				} else {
					/*Scroll down*/
					
					
				}
				
				/*if (t >= 0) {
					$('#site-banner').addClass('fixed');
				} else if (t = 0) {
					$('#site-banner').removeClass('fixed')
				} else {
					$('#site-banner').removeClass('fixed')
				}*/
			});
			
			var lastScrollTop = 0,
				scrollTimer,
				scrollTop = 0,
				scrollDirection;
			
			$(window).on('scroll',function(){
				
				if(_framework.helpers.inViewport('#text-2')){
					//alert('in view port');
				}
				
				
				
				// scrollTop = $(window).scrollTop();
					
					
				// if (scrollTop > lastScrollTop) {
				// 	scrollDirection = 'down';
				// } else {
				// 	scrollDirection = 'up';
				// }

    //     		lastScrollTop = scrollTop;
				
				// if( scrollTop > 55 && 'up' == scrollDirection){
				// 	$('.site-header').addClass('fix-site-banner');
				// 	//$('.site-banner').addClass('animated fadeInDown');
				
				// }else if( scrollTop > 55 && 'down' == scrollDirection){
				// 	//$('.site-banner').removeClass('animated fadeInDown');
				// 	$('.site-header').removeClass('fix-site-banner');
				// }else if( scrollTop <= 55 && 'up' == scrollDirection){
				// 	$('.site-header').removeClass('fix-site-banner');
				// 	//$('.site-banner').removeClass('animated fadeInDown');
				// }
				
			});
			
			
			//$('.site-headlines').endlessScroll();

			function getTranslateCoordinate (value, coordinate) {
				value = value.toString();
				var coordinateValue = 0,
					pattern = /([0-9-]+)+(?![3d]\()/gi , 
					positionMatched = value.match( pattern );
			 
				if (positionMatched.length) {
					var coordinatePosition = coordinate == 'x' ? 0 : coordinate == 'y' ? 1 : 2;
					coordinateValue = parseFloat( positionMatched[coordinatePosition] );
				}
					   
				return coordinateValue;
			}
			
		},
		/**
		 * Setup
		 * @since 1.0
		 */
		setup: function(){
		},
		/**
		 * Ui
		 * @since 1.0
		 */
		ui:{
			/**
			 * toggleSlidingCat
			 * @since 1.0
			 */
			toggleSlidingCat:function(){
				var $el = $(this),
					container = $el.closest('.yt-ajax-posts-by-cat'),
					header = $el.siblings('.yt-ajax-posts-by-cat-header'),
					expandClass = 'expanded-header',
					collapseClass = 'collapsed-header';
				
				
				header.hide().fadeIn();
				
				if( 'expand' === $el.data('action') ){
					container.removeClass(collapseClass).addClass(expandClass);
					$el.data('action', 'collapse');
				}else{
					container.removeClass(expandClass).addClass(collapseClass);
					$el.data('action', 'expand');
				}
			},
		},
		ux:{
			widgetAjaxPostsByCategory:function(e) {
				// body...
				if( Yeahthemes.Framework._eventRunning ){
					e.preventDefault();
					return;
				}

				var $el = $(this),
					dataSettingsContainer = $el.closest('.yt-ajax-posts-by-cat[data-settings]'),
					dataSettings,
					dataCatsSelector = $el.closest('li[data-id]'),
					dataCatsSelectorIndex = dataCatsSelector.index(),
					dataCats = 0,
					header = $el.closest('.yt-sliding-tabs-header'),
					contentContainer = header.siblings('.yt-sliding-tabs-content');


				if( !dataSettingsContainer.length){
					//console.log('not found');
					window.location.href = $(this).attr('href');
				}else{	

					/*Prevent duplicated data*/
					Yeahthemes.Framework._eventRunning = true;				

					if( contentContainer.find('>*[data-index="' + dataCatsSelectorIndex + '"]').length ){
						Yeahthemes.Framework._eventRunning = false;
						e.preventDefault();
						return;
					}

					//console.log('preventDefault');
					dataSettings = dataSettingsContainer.data('settings');
					e.preventDefault();

					if( dataCatsSelector.length )
						dataCats = dataCatsSelector.data('id');
	
					$('<div/>', {
						'class': 'yt-loading'
					}).prependTo(contentContainer).show(100);

					//console.log(dataCatsSelector.index());
					$.ajax({
						type: 'GET',
						url: Yeahthemes._vars.ajaxurl,
						data: {
							action: 'yt-ajax-posts-by-category',
							nonce: Yeahthemes.themeVars.widgetAjaxPostsByCatNonce,
							number: dataSettings.number,
							order: dataSettings.order,
							orderby: dataSettings.orderby,
							cats: dataCats,
							index: dataCatsSelectorIndex
							//dataType: 'json',
						},
						success: function(responses){
							//console.log(responses);
							contentContainer.find('.yt-loading').hide(100, function(){ $(this).remove();});
							contentContainer.find('> *.active').fadeOut(300, function(){
								dataCatsSelector.addClass('active').siblings('li').removeClass('active');
								$(this).removeClass('active');
								$(responses).hide().appendTo(contentContainer).fadeIn();
							});
							
							Yeahthemes.Framework._eventRunning = false;
						}
					});
				}
			},
			/**
			 * mobileNavigation
			 * @since 1.0
			 */
			mobileNavigation:function(){

				if( Yeahthemes.Framework._eventRunning )
					return;

				var $el = $(this),
					loading = 'yt-loading',
				    loaded = 'yt-loaded',
				    pageWrapper = $('#page'),
				    mobileNavWrapper = '#site-mobile-navigation';

				if( $(mobileNavWrapper).length ){
					$('body').addClass('active-mobile-menu');
					$('.inner-wrapper-mask').show();
				}else{

					$el.addClass(loading);
					$('<nav/>', {
						'id': 'site-mobile-navigation',
						'class': 'site-mobile-navigation smooth-scroller hidden-md hidden-lg',
						'role': 'navigation'
					}).appendTo(pageWrapper);
					$('<div/>', {
						'class': 'inner-wrapper-mask'
					}).appendTo(pageWrapper);



				
					/*Prevent duplicated data*/
					Yeahthemes.Framework._eventRunning = true;

					$.ajax({
						type: 'GET',
						url: Yeahthemes._vars.ajaxurl,
						data: {
							nonce: Yeahthemes.themeVars.mobileMenuNonce,
							action: 'yt-site-mobile-menu',
							//dataType: 'json',
						},
						success: function(responses){

							$el.removeClass(loading);
							$(mobileNavWrapper).addClass('yt-loaded').append(responses);
							//console.log(responses);
							$('body').addClass('active-mobile-menu');
							$('.inner-wrapper-mask').show();

							$(document).on('click', mobileNavWrapper + ' ul.menu li:has(ul)', function(e){ 
								var thisLi = $(this);
								if( !thisLi.hasClass('active') ){
									if(thisLi.siblings('.active:has(ul)').length){
										thisLi.siblings('.active').find(' > ul').slideUp(function(){
											$(this).closest('li').removeClass('active');
										});
									}
									$('> ul', thisLi).slideToggle(function(){
										thisLi.toggleClass('active');
									});
									e.preventDefault();	
								}

								// if( thisLi.hasClass('active') && ( $.inArray( $('> a', thisLi).attr('href'), [ 'http://#', 'https://#', '#', '' ] ) > -1 || $('> a', thisLi).attr('href') == undefined ) ){
								// 	$('> ul', thisLi).slideToggle(function(){
								// 		thisLi.toggleClass('active');
								// 	});
								// 	e.preventDefault();	
								// }
							}).on('touchstart click', '.inner-wrapper-mask', function(){

								if( $('body').hasClass('active-mobile-menu') ){
									$('body').removeClass('active-mobile-menu');

									$('.inner-wrapper-mask').fadeOut(500);
								}
							});
							Yeahthemes.Framework._eventRunning = false;
						}
					});
				}
				
			},
			/**
			 * mainNavigation
			 * @since 1.0
			 */
			mainNavigation:function( ){
				var $el = $(this);


				var $event = Yeahthemes.Framework.helpers.isTouch() ? 'touch' : 'mouseover',
					$hoverTimeOut = 200,
					$leaveTimeOut = 100,
					menuHoverInterval;

				$( '.main-navigation' ).on( 'mouseover' == $event ? 'mouseenter' : 'click' , 'li', function(e){

					//alert($event);

			    	var $el = $(this);
					

					if( 'touch' == $event && !$el.hasClass('active') ){
						if( $el.hasClass('default-dropdown') || $el.hasClass('mega-menu-dropdown') )
							e.preventDefault();
					}

					
			    	menuHoverInterval = setTimeout( function(){

				    	$el.addClass('active');

				    	if( $el.hasClass('default-dropdown')){

				    		$( '> ul', $el ).show();

				    	}else if( $el.hasClass('menu-item-has-children') && $el.hasClass('mega-menu-dropdown') ){

				    		//console.log('responses');

				    		var thisMegamenuWrapper = $( '> .mega-menu-container', $el ),
				    			dataCats = $el.data('cats') !== undefined && $el.data('cats') ? $el.data('cats') : false,
				    			thisTitle = $el.text(),
				    			loading = 'yt-loading',
				    			loaded = 'yt-loaded';

				    		if( Yeahthemes.themeVars.megaMenu.ajax && !thisMegamenuWrapper.hasClass(loaded) && dataCats){

				    			thisMegamenuWrapper.addClass(loading).show();

				    			if( !dataCats )
				    				return;
				    			
					    		if( Yeahthemes.Framework._eventRunning ){
									return;
								}
								
								/*Prevent duplicated data*/
								Yeahthemes.Framework._eventRunning = true;

				    			$.ajax({
									type: 'GET',
									url: Yeahthemes._vars.ajaxurl,
									data: {
										nonce: Yeahthemes.themeVars.megaMenu.nonce,
										action: 'yt-site-mega-menu',
										data_cats: dataCats,
										title: thisTitle
										//dataType: 'json',
									},
									success: function(responses){
										thisMegamenuWrapper.removeClass(loading).addClass(loaded);
										var thisMegamenuInner = thisMegamenuWrapper.find('> *');
										thisMegamenuInner.hide().append(responses);
										thisMegamenuInner.find('.post-list').children(':not(.child-cat)').addClass('animated ' + ( Yeahthemes.themeVars.megaMenu.effect ? Yeahthemes.themeVars.megaMenu.effect : 'fadeIn' ));
										thisMegamenuInner.show(0, function(){
											setTimeout(function(){

												thisMegamenuWrapper.find('.post-list').children(':not(.child-cat)').removeClass('animated ' + ( Yeahthemes.themeVars.megaMenu.effect ? Yeahthemes.themeVars.megaMenu.effect : 'fadeIn' ));
											},1000);
										});

										/*Prevent duplicated data*/
										Yeahthemes.Framework._eventRunning = false;

									}
								});
				    		}else{
				    			thisMegamenuWrapper.show();
				    		}
				    	}
			    	}, $hoverTimeOut );
				}).on( 'mouseleave' , 'li', function(e){
					var $el = $(this);
				    	
			    	clearTimeout( menuHoverInterval );
			    	
			    	if( $el.hasClass('active') ){
						setTimeout( function(){
							$el.removeClass('active');


					    	if( $el.hasClass('default-dropdown')){
					    		setTimeout(function(){
									$( '> ul', $el ).hide();
					    		},300);
					    	}else if( $el.hasClass('mega-menu-dropdown') ){
					    		var thisMegamenuWrapper = $( '> .mega-menu-container', $el ),
					    			thisSubCatMenu = $('.sub-category-menu', thisMegamenuWrapper);

					    		setTimeout(function(){
									thisMegamenuWrapper.hide();
					    			thisMegamenuWrapper.find('.post-list').children(':not(.child-cat)').removeClass('animated ' + ( Yeahthemes.themeVars.megaMenu.effect ? Yeahthemes.themeVars.megaMenu.effect : 'fadeIn' ));

						    		/*Restore sub mega menu post list*/
						    		if( thisSubCatMenu.length ){
						    			thisSubCatMenu.find('[data-cat="all"]').addClass('current').siblings().removeClass('current');
							    		thisSubCatMenu.siblings( '[data-filter="all"]' ).show();
							    		thisSubCatMenu.siblings( ':not([data-filter="all"])' ).hide();
						    		}

					    		},300);
					    	}

						}, $leaveTimeOut);
					}

				});
			},
			/**
			 * mainNavigation
			 * @since 1.0
			 */
			mainNavigationSubmenu:function( e ){

				var $el = $(this),
					dataCat = $el.data( 'cat' ),
					loading = 'yt-loading',
					thisMenuNav = $el.closest('.sub-category-menu'),
					thisMegamenuWrapper = $el.closest( '.mega-menu-container' ),
					$target = $el.closest( '.post-list' );


				if( !$el.hasClass( 'current') )
					e.preventDefault();

				$el.addClass('current').siblings().removeClass('current');

				if( $el.hasClass( 'loaded') ){
					thisMenuNav.siblings().hide();
					thisMenuNav.siblings('[data-filter="' + dataCat + '"]').show();
				}else{


					if( Yeahthemes.Framework._eventRunning ){
						return;
					}
					/*Prevent duplicated data*/
					Yeahthemes.Framework._eventRunning = true;

					thisMegamenuWrapper.addClass(loading);

					$.ajax({
						type: 'GET',
						url: Yeahthemes._vars.ajaxurl,
						data: {
							nonce: Yeahthemes.themeVars.megaMenu.nonce,
							action: 'yt-site-sub-mega-menu',
							data_cat: dataCat,
							//dataType: 'json',
						},
						success: function(responses){
							$el.addClass('loaded');
							thisMegamenuWrapper.removeClass(loading);
							var thisMegamenuInner = thisMegamenuWrapper.find('> *');

							$target.append(responses);
							
							if( responses ){
								thisMenuNav.siblings().hide();
								thisMenuNav.siblings('[data-filter="' + dataCat + '"]').show();
							}

							/*Prevent duplicated data*/
							Yeahthemes.Framework._eventRunning = false;

						}
					});
				}
			}

		}
		
		
	}
	
	/**
	 * Yeahthemes.Framework
	 *
	 * @since 1.0
	 */
	Yeahthemes.Framework = {
		init:function(){

			this._eventRunning 	= false;
		},
		/**
		 * Ui
		 * @since 1.0
		 */
		ux:{
			animateSequence: function( _container, _children, _animClass, _timeOut, _randomize ){
				
				_animClass = _animClass || 'zoomIn';
				_animClass = 'animated ' + _animClass;
				
				
				_timeOut = _timeOut && false == isNaN( parseInt( _timeOut ) ) && parseInt( _timeOut ) ? _timeOut : 500;
				
				var animRandomization = _randomize == true || _randomize == false ? _randomize : true,
					hiddenClass = 'visibility-hidden',
					childrenCount = $( _container ).find(_children).length;
					
				if( !childrenCount )
					return;
				
				$( _children, $( _container )).each(function(index, element) {
					
					//console.log(index);
					
					var $el = $(this),
						delayTime = animRandomization === true ? Math.floor(Math.random() * (_timeOut * 3)) : index * _timeOut;
					
					setTimeout( function() {
						$el.removeClass(hiddenClass).addClass(_animClass);
						
						if(index == 0){
							$el.addClass('fired');	
						}
						//$el.addClass('fired');
						$el.addClass('index-'+ index);
						$el.addClass('temp-'+ index * _timeOut);
						//Remove the class after done animation
						setTimeout( function() {$el.removeClass(_animClass)}, 500*10);
					//}, (index !== 0 ? index * timeOut : 0 ));	
					}, (index == 0 && !animRandomization ? 0 : delayTime ) );	
				});
				
			},
			yeahSlider: function( $el ){
				var yeahSliderDefaultSettings = {
						namespace: 'yeahslider-',
						animation: 'slide',
						start: function(slider){ 
							slider.addClass('initialized');
						},
						before: function(slider){
							var current = slider.currentSlide == slider.count - 1 ? 0 : slider.currentSlide+1,
								css3Effect = slider.vars.css3Effect;
							if( css3Effect )
								slider.slides.removeClass( css3Effect ).eq( current).addClass( css3Effect );
						},
						after: function(slider){
						},
					},
					yeahSliderCustomSettings = $el.data('settings') !== undefined ? $el.data('settings') : {},
					yeahSliderSettings = $.extend(yeahSliderDefaultSettings, yeahSliderCustomSettings);
					/*Init*/
					$el.flexslider(yeahSliderSettings);
					//console.log('init');
			},
			tapToTop: function( e ){
				var $el = $(this);
				
				if( $el.attr('id') == undefined )
					return
				if( e.target.id != $el.attr('id') )
					return;

				$("html, body").animate({scrollTop:0},"fast")
				
			}
		},
		/**
		 * Ui
		 * @since 1.0
		 */
		ui:{
			/**
			 * socialSharing
			 * @since 1.0
			 */
			socialSharing:function(e){
				
				e.preventDefault();
			
				var $el 	= $(this),
					service= $el.attr('class'),
					wrapper= $el.closest('.yt-social-sharing'),
					w		= 560,
					h		= 350,
					x		= Number((window.screen.width-w)/2),
					y		= Number((window.screen.height-h)/2),
					url 	= encodeURIComponent( wrapper.data('url') ),
					source 	= encodeURIComponent( wrapper.data('source') ),
					title 	= encodeURIComponent( wrapper.data('title') ),
					media 	= encodeURIComponent( wrapper.data('media') ),
					href = '';
				
				if( 'twitter' === service ){
					href = 'https://twitter.com/intent/tweet?url=' + url + '&text=' + title;
				}else if( 'facebook' === service ){
					href = 'https://www.facebook.com/sharer/sharer.php?u=' + url;
				}else if( 'google-plus' === service ){
					href = 'https://plus.google.com/share?url=' + url;
				}else if( 'linkedin' === service ){
					href = 'http://www.linkedin.com/shareArticle?mini=true&url=' + url + '&title=' + title + '&source=' + source;
				}else if( 'pinterest' === service ){
					href = '//pinterest.com/pin/create/button/?url=' + url + '&media=' + media + "&description=" + title;
				}else if( 'tumblr' === service ){
					href = '//www.tumblr.com/share/photo?source='+ media +'&caption=' + title + '&clickthru=' + url;
				}else if( 'stumble-upon' === service ){
					href = '//www.stumbleupon.com/badge/?url='+ url;
				}
				
				window.open( href,'','width=' + w + ',height=' + h + ',left=' + x + ',top=' + y + ', scrollbars=no,resizable=no');
				
				//console.log(wrapper.data('title'));
			},
			
			/**
			 * socialSharingMore
			 * @since 1.0
			 */
			socialSharingMore:function(e){
				e.preventDefault();
				
				var $el = $(this),
					secondary = $el.siblings('.secondary-shares'),
					wrapper = $el.closest('.yt-social-sharing'),
					action = $el.data('action');
					
				if( 'show' === action){
					secondary.fadeIn(300);
					wrapper.addClass('show-all');
					$el.data('action', 'hide');
				}else{
					secondary.fadeOut(200);
					wrapper.removeClass('show-all');
					$el.data('action', 'show');
				}
					
				
			},
			/**
			 * fontSizeChanger
			 * @since 1.0
			 */
			fontSizeChanger:function(e){
				var $el = $(this),
					scalableContent = $('#content .entry-content'),
					defaultFontSize = parseInt(scalableContent.css('font-size')),
					parent = $el.closest('.yt-font-size-changer');
				
				if( parent.attr('data-font-size') == undefined){
					parent.attr('data-font-size', defaultFontSize);
				}
				
				if( $el.hasClass('font-size-plus')){
					if(defaultFontSize < 20){
						defaultFontSize = defaultFontSize +1;
						//console.log('xxxxxx');
					}
				}else if( $el.hasClass('font-size-minus')){
					if(defaultFontSize >12)
						defaultFontSize--;
				}else{
					defaultFontSize = parseInt(parent.attr('data-font-size'));
				}
				
				scalableContent.css('font-size',defaultFontSize);
				
				//console.log(defaultFontSize);
				//$('.yt-font-size-changer')
			},
			
			/**
			 * socialSharing
			 * @since 1.0
			 */
			smoothAnchor: function(e){
				var $el = $(this),
					target = window.location.href.split('#'),
					currentUrl = $el.attr('href').split('#'),
					id = typeof target[1] == 'undefined' ? '' : target[1];
				
				if( currentUrl[0] == target[0] && $( '#' + id ).length ){
					
					$('html, body').animate({
						scrollTop: $( '#' + id ).offset().top - 30
					}, 500);
					
					e.preventDefault();
				}
			},
			/**
			 * tabbyTabs
			 * @since 1.0
			 */
			tabbyTabs:function(e){
				
			/*	$('.yt-tabby-tabs-content').find('>*:first').addClass('active');
				$('.yt-tabby-tabs-header ul li:first').addClass('active');*/
				
				e.preventDefault();
				
				var $el = $(this),
					wrapper = $el.closest('.yt-tabby-tabs-header'),
					position = wrapper.hasClass('yt-tabby-tabs-header-bottom') ? 'bottom' : 'top',
					tabContent = wrapper.siblings('.yt-tabby-tabs-content'),
					index = $el.index();
				
				if( tabContent.find('>*[data-index]').length ){
					$el.addClass('active').siblings().removeClass('active');
					tabContent.find('>*[data-index="' + index + '"]').fadeIn(200,function(){
						//console.log('tag triggered');
					}).addClass('active').siblings().hide().removeClass('active');

				}else{
					if( tabContent.find('>*').eq(index).length ){
						$el.addClass('active').siblings().removeClass('active');
						tabContent.find('>*').eq(index).fadeIn(200).addClass('active').siblings().hide().removeClass('active');
					}
				}
			},
			
			
			hoverScrollHorizontalHelperRestoreCurrentItem: function($outer, $inner){
				var activePointer = $inner.find('li.active').length ? $inner.find('li.active') : $inner.find('li:first-child'),
					activePointerW = activePointer.width(),
					extraW = $outer.width() > activePointerW ? ($outer.width() - activePointerW)/2 : 0,
					animateTo = activePointer.is(':first-child') ? activePointer.position().left : activePointer.position().left+10-extraW;
					$outer.animate({scrollLeft:animateTo}, 800, function(){ 
						//console.log('aaa'); 
					});
			},
			
			hoverScrollHorizontal: function($outer, $inner){
				
				var extra = 100,
					divWidth = $outer.width(), /*Get menu width*/
					lastElem = $inner.find('li:last'), /*Find last image in container*/
					triggering = false;
				//Remove scrollbars
				$outer.css({
					overflow: 'hidden'
				});
				
				$outer.scrollLeft(0);
				//When user move mouse over menu
				
				$outer.on('mousemove', function(e){
					triggering = true;
					var containerWidth = lastElem.position().left + lastElem.outerWidth() + 2 * extra;
					var left = (e.pageX - $outer.offset().left) * (containerWidth-divWidth) / divWidth - extra;
					$outer.scrollLeft(left);
				});
				
				$outer.on('mouseleave', function(e){
					
					triggering = false;
					
					setTimeout(function() {
						if( !triggering)
							Yeahthemes.Framework.ui.hoverScrollHorizontalHelperRestoreCurrentItem( $outer, $inner );
					}, 2000);
					
				});
			},
			
			hoverScrollVertical: function($outer, $inner){
				var extra           = 100;
				//Get menu width
				var divHeight = $outer.height();
				//Remove scrollbars
				$outer.css({
					overflow: 'hidden'
				});
				//Find last image in container
				var lastElem = $inner.find('li:last');
				$outer.scrollTop(0);
				//When user move mouse over menu
				$outer.on('mousemove', function(e){
					var containeHeight = lastElem.position().top + lastElem.outerHeight() + 2 * extra;
					var top = (e.pageY - $outer.offset().top) * (containeHeight-divHeight) / divHeight - extra;
					$outer.scrollTop(top);
				});
			},
			
			commentForm: function(){
					
				var $el = $(this),
					email = $el.find('input#email'),
					author = $el.find('input#author'),
					captcha = $el.find('input#comment_captcha_code'),
					comment = $el.find('textarea#comment'),
					appendTo = $el.find('.comment-notes').length ? $el.find('.comment-notes') : $el.find('.logged-in-as'),
					errorMsg = '',
					focusTo = [];
				
				
				//if author is blank
				if(author.length && author.val() === ''){
					errorMsg += Yeahthemes._vars.commentErrorName;
					focusTo.push(author);
				}
				
				//if email address is blank
				if( email.length && email.val() === ''){
					errorMsg += Yeahthemes._vars.commentErrorEmail;
					focusTo.push(email);
				}
				
				//valid email address
				if( email.length && email.val() !== '' && !Yeahthemes.Framework.helpers.isValidEmailAddress(email.val()) ) {
					errorMsg += Yeahthemes._vars.commentErrorInvalidEmail;
					focusTo.push(email);
					
				}
				//console.log(self.helpers.isValidEmailAddress(email.val()));
				
				
				if(captcha.length && captcha.val() === ''){
					errorMsg += Yeahthemes._vars.commentErrorInvalidCaptcha;
					focusTo.push(captcha);
				}
				
				//comment
				if(comment.length && comment.val() === ''){
					errorMsg += Yeahthemes._vars.commentErrorComment;
					focusTo.push(comment);
				}
				
				if(errorMsg != ''){
					
					if($el.find('.comment-error').length){
						$el.find('.comment-error').html(errorMsg);
					}else{
						var output = '<div class="col-md-12">\
							<div class="comment-error alert alert-danger"><p>' + errorMsg + '</p></div>\
						</div>';
						
						$(output).hide().insertAfter(appendTo).show(200);
					}
					
					$('.site#page').animate({scrollTop:$(this).find('.comment-error').offset().top-100}, 800);
					
					focusTo[0].focus();
					//console.log(focusTo);
					
					return false;
				}else{
					
					$el.find('.comment-error').hide(200);
				}
			}
			
		},
		/**
		 * Widgets
		 * @since 1.0
		 */
		widgets:{

			mailchimpSubscription: function(e){
				e.preventDefault();

				if( Yeahthemes.Framework._eventRunning )
					return;

				var $el = $(this).closest('.yt-mailchimp-subscription-form-content'),
					nonce = $el.find('[name="yt_mailchimp_subscribe_nonce"]').val(),
					list = $el.find('[name="yt_mailchimp_subscribe_list"]').val(),
					check = $el.find('[name="yt_mailchimp_subscribe_check"]').val(),
					email = $el.find('[name="yt_mailchimp_subscribe_email"]').val(),
					fname = $el.find('[name="yt_mailchimp_subscribe_fname"]').length ? $el.find('[name="yt_mailchimp_subscribe_fname"]').val() : '',
					lname = $el.find('[name="yt_mailchimp_subscribe_lname"]').length ? $el.find('[name="yt_mailchimp_subscribe_lname"]').val() : '',
					result = $el.find('.yt-mailchimp-subscription-result');
				
				$el.addClass('yt-loading');
				Yeahthemes.Framework._eventRunning 	= true;
				result.fadeOut().html('');

				$.ajax({
					type: 'POST',
					url: Yeahthemes._vars.ajaxurl,
					data: {
						action: 'yt-mailchimp-add-member',
						nonce: nonce,
						email: email,
						fname: fname,
						lname: lname,
						list: list,
						checking: check
					},
					success: function(responses){
						Yeahthemes.Framework._eventRunning 	= false;
						$el.removeClass('yt-loading');
						//console.log(responses);
						result.html(responses).fadeIn();
						setTimeout( function(){
							result.fadeOut();
						}, 10000 );
					},
				});
			}
		},
		/**
		 * Helpers
		 * @since 1.0
		 */
		helpers: {
			isValidEmailAddress: function (emailAddress) {
				var regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    			return regex.test(emailAddress);
			},
			/**
			 * inViewport
			 */
			inViewport: function(_selectors, _extra){
				
				if(!$(_selectors).length)
					return;
				
				_extra = _extra || 0;
				var scrollTop = $(window).scrollTop(),
					docViewTop = scrollTop - _extra,
					docViewBottom = scrollTop + $(window).height(),
					elemTop = $(_selectors).offset().top,
					elemBottom = ( elemTop + $(_selectors).outerHeight() ) - _extra;
					
					//console.log( elemTop + '-' + docViewTop);
				return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
			},
			/**
			 * equalHeight
			 */
			equalHeight: function( _selectors, _all ){

				$( _selectors ).css('min-height', '');

				if( _all ){
					$( _selectors ).siblings().css('min-height', '' );
				}
				
				_all = _all || false;
				
				var height = $( _selectors ).outerHeight();
				
				$( _selectors ).siblings().each(function(index, element) {
					
					var thisHeight = $(this).outerHeight();
					
					if( thisHeight > height ){
						height = thisHeight;
					}
				});
				
				setTimeout(function(){
					$( _selectors ).css('min-height', height + 'px' );
					
					if( _all ){
						$( _selectors ).siblings().css('min-height', height + 'px' );
					}
				},100);
				
			},
			/**
			 * isTouch
			 */
			isTouch: function() {
				if( 'ontouchstart' in document.documentElement )
					return true;
				else
					return false;
			},
			/**
			 * isIOS
			 */
			isIOS: function() {
				if( navigator.userAgent.match(/(iPad|iPhone|iPod)/g) )
					return true;
				else
					return false;
			},
			/**
			 * hasParentClass
			 */
			isMobile: function() {
				var check = false;
				(function(a){if(/(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
				return check;
			},
			isBrowser: function( _class ) {
				if( !_class )
					return false;
				if( $('body').hasClass( _class + '-browser' )){
					return true;
				}else{
					return false;
				}
			},
			/**
			 * hasParentClass
			 */
			hasParentClass: function( e, classname ) {
				if(e === document) return false;
				if( $(e).hasClass(classname ) ) {
					return true;
				}
				return e.parentNode && yt_hasParentClass( e.parentNode, classname );
			}
			
		}
	};
	
	
	
	Yeahthemes.Framework.init();
	Yeahthemes.RitaMagazine.init();
	
})(jQuery);