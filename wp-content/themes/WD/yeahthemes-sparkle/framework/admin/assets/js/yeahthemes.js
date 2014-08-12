/**
 * YEAHTHEME OPTIONS FRAMEWORK js
 *
 * contains the core functionalities to be used
 */
if (typeof Yeahthemes == 'undefined') {
	var Yeahthemes = {};
}

;(function ($) {
	
	'use strict';
	
	/*-----------------------------------------------------------------------------------
	 * Yeahtheme Optios framework
	 * @author Yeahthemes
	 * @since 1.0
	 * @support wp 3.5+
	 *-----------------------------------------------------------------------------------*/
	
	if (typeof Yeahthemes == 'undefined') {
	   return;
	}
	
	Yeahthemes.OptionsFramework = {
		/**
		 * Init Function
		 */
		init: function(){
			
			var self = this;
			
			this._container = $('body.yeah-framework'),
			this._successPopup = 'yt-popup-save',
			this._failPopup = 'yt-popup-fail',
			this._resetPopup = 'yt-popup-reset',
			this._ytAjaxifyingClass = 'yt-ajaxifying';
			this._optionVars = {};
			
			
			
			$(window).on('load', function(){
				self.windowLoad();
				
			});
			
			$(document).ready(function() {
				self.documentReady();
			});
		},
		/**
		 * Function for onload put here
		 */
		documentReady: function(){
			
			var self = this;
			
			$('body').addClass('yt-page-loading').addClass('yt-allow-transform');
			
			/**
			 * hides warning if js is enabled
			 */			
			$('#js-warning').hide();
			
			if( $('.yt-section-oembed').length ){
				
				$('.yt-section-oembed').fitVids();
			};
			
			/*Setting panel*/
			self.optionSetingsPanel.displayRecentActivatedTab();
			self.optionSetingsPanel.optionPanelNavAction();
			self.optionSetingsPanel.toggleSettingPanelFullwidth();
			
			/*Media uploader*/
			
			Yeahthemes.OptionsFrameworkUploader.yeahMediaModel('','','');
			Yeahthemes.OptionsFrameworkUploader.removeFile();
			Yeahthemes.OptionsFrameworkUploader.mediaUpload();
			
			Yeahthemes.OptionsFrameworkUploader.galleryUpload();
				
			
			
			/*COntrols & ajaxifying actions*/
			self.optionSettingBindEvents();
			
			self.optionSettingPanelAjaxifyingActions();
		},
		/**
		 * Function for onload put here
		 */
		windowLoad: function(){
			
			var self = this;
				
			$('body').removeClass('yt-page-loading').addClass('yt-page-loaded');
			
			self.helper.doneProcessing();
		
			setTimeout(function () {
					
				$('body').addClass('yt-no-transform');
				
			}, 1000);
			
			setTimeout(function () {
					
				$('body').removeClass('yt-allow-transform');
				
				self.optionSetingsPanel.stickyPanelNav();
				
			}, 2020);
			
			/**
			 *	Tipsy
			 */
		
			if ($().tipsy) {
				$('.tip-tip, .yt-select-wrapper ').tipsy({
					fade: true,
					gravity: 's',
				});
			}
			
			if(window.location.hash) {
			  // Fragment exists
			  var hash = window.location.hash.substring(1);
			  //console.log(hash);
			  //$( 'a[href*="#' + hash + '"]' ).trigger('click');
			  //$( 'a[href*="#' + hash + '"]' ).remove();
			} else {
			  // Fragment doesn't exist
			}
			
			self.metaboxFriendlyUi.postFormatSwitcher();

			
		},
		/**
		 * Ajaxifying helper
		 * 
		 */
		helper: {
			/**
			 * Processing
			 */
			processing: function(){
				$('body').addClass( Yeahthemes.OptionsFramework._ytAjaxifyingClass);
				
				
			},
			doneProcessing: function(){
				$('body').removeClass( Yeahthemes.OptionsFramework._ytAjaxifyingClass);
			}
		},
		/**
		 * Option Settings Panel object
		 * onload
		 */
		
		optionSetingsPanel:{
			
			stickyPanelNav: function(){
				
				//console.log(this);
				if ( $( '#yt-nav' ).length ) {
					var navbarOffsetTop = $('#yt-nav').offset().top -28,
						navbarOffsetBottom = $(document).height() - ($('#yt-main').height() + $('#yt-main').offset().top);
			
					setTimeout(function () {
					
						$('#yt-nav').affix({
							offset: {
								top: navbarOffsetTop, 
								bottom: navbarOffsetBottom
							}
						})
					}, 100);
				}
			},
			
			/**
			 * Resize Theme Options height 
			 */
			resizeThemeOptionsHeight: function(){
				$('#yt-container #yt-content').css({'min-height':$('#yt-container #yt-nav').outerHeight() - 20});		
			},
			
			/**
			 * Display recent activated tab 
			 */
			displayRecentActivatedTab: function(){
				
				var self = this;
				
				/*This fucks affix*/
				// Display last current tab	
				if (!$.cookie( 'yt_current_opt' ) || !$( $.cookie( 'yt_current_opt' ) ).length ) {
					$('.yt-group:first').show(0,function(){$(this).addClass('current')});	
					$('#yt-nav li:first').addClass('current');
					
				} else {
					
					var savedID = $.cookie( 'yt_current_opt' );
					
					$(savedID).show(0,function(){$(this).addClass('current')});	
					
					$('#yt-nav').find('a[href=' + savedID + ']').parents('li').addClass('current');
					$('#yt-nav').find('a[href=' + savedID + ']')
						.closest('.sub-menu')
						.show('fast',function(){
							self.resizeThemeOptionsHeight(); 
						});
				}
					
			},
			/**
			 * Nav action
			 */
			optionPanelNavAction: function(){
				
				var self = this;
			
				
				$('#yt-nav li a').on('click', function(e){
					
					var $el = $(this);
					
					if($(this).parent().hasClass('current')){
						e.preventDefault();
						return;
					}
						
					if($(this).parent('li').hasClass('has-children')){
						$('#yt-nav li').removeClass('current');
						$(this).parent().addClass('current');
						$(this).siblings().children('li:first').addClass('current');			
						var clicked_group = $('ul.sub-menu li:first a',$(this).parent()).attr('href');
						//alert(clicked_group);
						$.cookie('yt_current_opt', clicked_group, { expires: 7, path: '/' });
						
						if( !$('body').hasClass('yt-admin-motion-enabled') ){
							$('.yt-group').not(clicked_group).removeClass('current').hide(0);
							$(clicked_group).fadeIn(300).addClass('current');	
						}else{			
							$('.yt-group').removeClass('current')
								.delay(210)
								.hide(0, function(){
									$('.yt-group').not(clicked_group).removeClass('has-transform');
								});
							$(clicked_group).addClass('has-transform').show(10,function(){$(this).addClass('current')});
						}
						
						$('#yt-nav ul.sub-menu').hide();
						$(this).siblings('ul.sub-menu').slideDown('fast',function(){
							self.resizeThemeOptionsHeight();
						});
						
					}else{
						$('#yt-nav li').removeClass('current');
						$(this).parent().addClass('current');
											
						var clicked_group = $(this).attr('href');
						//Write the current opened tab to cookies
						$.cookie('yt_current_opt', clicked_group, { expires: 7, path: '/' });
						
						if( !$('body').hasClass('yt-admin-motion-enabled') ){
						
							$('.yt-group').removeClass('current').hide(0);
							$(clicked_group).fadeIn(300).addClass('current')
						
						}else{
							$('.yt-group').removeClass('current')
								.delay(210)
								.hide(0, function(){
									$('.yt-group').not(clicked_group).removeClass('has-transform');
								});
							$(clicked_group).addClass('has-transform').show(10,function(){$(this).addClass('current')});
						}
						
						if($(this).closest('ul').hasClass('sub-menu')){
							//$(this).siblings('ul.sub-menu').show();	
						}else{
							$('#yt-nav ul.sub-menu').slideUp('fast',function(){
								self.resizeThemeOptionsHeight();
							});	
						}
						self.resizeThemeOptionsHeight();
					}
					
					e.preventDefault();
									
				});
				
				$('#yt-nav li ul.sub-menu li a').on('click', function(e){
					$(this).closest('.top-level').addClass('current');
					e.preventDefault();
				})
				
				
			},
			toggleSettingPanelFullwidth: function(){
			
				$(document).on('click', '#yt-expand-options-panel', function(e){
			
					$('#yt-container form.yt-options-panel-form').toggleClass('yt-options-panel-fullsize');
				
				});
			}
		},
		/**
		 * Setting Control in action
		 */
		optionSettingBindEvents: function(){
			var self = this;
			
			$( document )
				/*Toggle grid*/
				
				.on('keydown keypress', '.yt-section-text-number input[type="text"]', self.optionSettingControlsAction.textInputNumber )
				/* Folding checkbox */
				.on('click', '.yt-fold-trigger.yt-checkbox', self.optionSettingControlsAction.foldingCheckbox )
				/* Color Block and Images type*/
				.on('click', '.yt-radio-color-blocks > label, .yt-radio-images > label', self.optionSettingControlsAction.colorBlocksAndImages )
				/* Folding checkbox */
				.on('click', '.yt-radio-color-blocks > label, .yt-radio-images > label', self.optionSettingControlsAction.colorBlocksAndImages )
				/* Folding checkbox */
				.on('click', '.yt-section-separator', self.optionSettingControlsAction.separatorFolding )
				/* Toggle buttons */
				.on('click', '.yt-radio-toggles label', self.optionSettingControlsAction.toggleButtons )
				/* Textarea */
				.on( 'keydown', '.yt-tabifiable-textarea .yt-textarea', Yeahthemes.Helper.tabifyTextarea);
				
				
				
				
		
				/* Colorpicker */
				self.optionSettingControlsAction.colorPicker( '.yt-colorpicker' );
				/* datepicker */
				self.optionSettingControlsAction.datePicker( '.yt-input-calendar' );
				/* Repeatable fields */
				self.optionSettingControlsAction.repeatableFields();
				/* Tab group */
				self.optionSettingControlsAction.tabGroup();
				/* timepicker */
				self.optionSettingControlsAction.timePicker( '.yt-input-time' );
				/* Typography Preview */
				self.optionSettingControlsAction.typographyPreview();
				/* UI Slider */
				self.optionSettingControlsAction.uiSlider( '.yt-ui-slider' );
				
				self.optionSettingControlsAction.foldingSelect();
				
				self.optionSettingControlsAction.getOEmbed();
		},
		/**
		 * metaboxFriendlyUi
		 */
		metaboxFriendlyUi: {
			postFormatSwitcher: function(){

				var tab = '#yt_post_format_settings_metabox .yt-group-tab-wrapper:first .yt-group-tab-header li',
					formatsSelect = '#formatdiv #post-formats-select';
				
				if( ! $(formatsSelect).length )
					return;
					
				var checkedFormat = $(formatsSelect + ' input:checked'),
					checkedFormatIndex = checkedFormat.parent().children('input').index(checkedFormat);
					
				$(tab + ':eq(' + checkedFormatIndex + ')').trigger('click');
				
				//console.log(checkedFormatIndex);
				
				$(document).on('click', tab, function(){
					var $el = $(this),
						thisIndex = $el.index(),
						destination = '';			
					$( formatsSelect + ' input:eq(' + thisIndex + ')').prop('checked', true);	
					//console.log(thisIndex);		
				});

				$(document).on('change', formatsSelect + ' input', function(){
					var $el = $(this),
						id = '#' + $el.attr('id'),
						thisIndex = $el.parent().children('input').index($el),
						destination = '';
					$(tab + ':eq(' + thisIndex + ')').trigger('click');
					//console.log(thisIndex);				
				});	

			}
		},
		/**
		 * Setting Control in action
		 */
		optionSettingControlsAction: {
			textInputNumber:function(e){
				//yt-section-text-number
				// Allow: backspace, delete, tab, escape, enter and .
		        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
		             // Allow: Ctrl+A
		            (e.keyCode == 65 && e.ctrlKey === true) || 
		             // Allow: home, end, left, right
		            (e.keyCode >= 35 && e.keyCode <= 39)) {
		                 // let it happen, don't do anything
		                 return;
		        }
		        // Ensure that it is a number and stop the keypress
		        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		            e.preventDefault();
		        }
			},
			/**
			 * Color blocks
			 */
			colorBlocksAndImages: function(e){
				var $el = $(this);
				$el.addClass('active').siblings('label').removeClass('active').siblings('[type="hidden"]').val($el.data('value'));
				
				e.preventDefault();
			},
			
			/**
			 * Color picker
			 */
			colorPicker: function(_selector){
				$(_selector).each(function(index, element) {
					var $el = $(this);			
					var myOptions = {
						// you can declare a default color here,
						// or in the data-default-color attribute on the input
						defaultColor: $el.data('std'),
						// a callback to fire whenever the color changes to a valid color
						change: function(event, ui){},
						// a callback to fire when the input is emptied or an invalid color
						clear: function() {},
						// hide the color picker controls on load
						hide: true,
						// show a group of common colors beneath the square
						// or, supply an array of colors to customize further
						palettes: true
					};
					 
					$el.wpColorPicker(myOptions);
			
				});
			},
			
			/**
			 * datepicker
			 */
			datePicker: function( _selector ){
				if ( $( _selector ).length ) {
					$( _selector ).each(function () {
						$( '#' + $( this ).attr( 'id' ) ).datepicker( {showAnim: 'fadeIn' } );
					});
				}
			},
			
			/**
			 * Checbox wit Folding function
			 */
			foldingCheckbox: function(e){
				
				var $el = $(this),
				//console.log($(this).attr('checked'))
					$fold = '.f_' + this.id;
				
				$el.closest('.yt-section-checkbox').siblings($fold).slideToggle('normal', "swing", function(){
					
					if( $el.attr('checked') !== undefined){
						
						$($fold).removeClass('yt-temp-hide');
						
					}else{
						
						$($fold).addClass('yt-temp-hide');
						
					}
						
				} );
				
			},
			
			foldingSelect: function(){
				//Options relations
				
				$(document).on('change', '.yt-section-select select', function() {
					
					var $el = $(this),
						thisID = $el.attr('id');
						
					
					$('.f_' + thisID ).each(function(index, element) {
						if( $(this).data('fold').indexOf($el.val()) != -1 )
							$(this).slideDown(300);
						else
							$(this).slideUp(300);
					});
					
						
					//$('.f_' + thisID + '[data-fold="' + $el.val() + '"]' ).slideDown(300);
					
				});
				
				$.each( $('.yt-section-select select'), function () {
					var $el = $(this),
						thisID = $el.attr('id');
					
					$('.f_' + thisID ).each(function(index, element) {
						if( $(this).data('fold').indexOf($el.val()) != -1 )
							$(this).show();
					});
				});	
			},
			/**
			 * getOEmbed
			 */
			getOEmbed:function() {				
				
				$( document ).on('click', '.yt-oembed-preview', function(e){
					var $el = $( this ),
						$spinner = $el.siblings( '.spinner' ),
						data = {
							action: 'yt_field_type_oembed_get_embed',
							url: $el.parent().siblings( 'input' ).val()
						};

					//console.log($el.parent().siblings( 'input' ).val());
			
					$spinner.show(function(){$(this).css('display','inline-block')});
					$.get( ajaxurl, data, function( response )
					{
						if(response && !response.error){
							$spinner.hide();
							console.log(response);
							$el.parent().siblings( '.yt-oembed-preview-area' ).html( response.data );
							$el.siblings( '.yt-oembed-remove-button').removeClass('yt-hidden');
						}else{
							$spinner.hide();
							$el.parent().siblings( '.yt-oembed-preview-area' ).html( '<p>' + yt_optionsVars.ytMsgOembedPreviewError + '</p>' );
							$el.siblings( '.yt-oembed-remove-button').removeClass('yt-hidden');
						}
					}, 'json' );
			
					e.preventDefault();
				} ).on('click', '.yt-oembed-remove-button', function(e){
					var $el = $( this );
			
					$el.addClass('yt-hidden').parent().siblings( '.yt-oembed-preview-area' ).html('').siblings( 'input' ).val('');
			
					e.preventDefault();	
				} )
					
				
				
			},
			/**
			 * Separator ( Folding Toggles )
			 */
			separatorFolding: function(e){
					
				//if attribute "data-show" non-exists ,return
				if( $(this).attr("data-show") == undefined) return;
				
				//else, add the events
				var triggerId = $(this).attr("id").split("yt-section-");
				triggerId = triggerId[1];
				if ($(this).data('show') == 'yes') {
					$(this).siblings('.yt-section[data-folds="' + triggerId + '"]:not(.yt-temp-hide)').hide();
					$(this).data('show', 'no').addClass('collapse');
				}else{
					$(this).siblings('.yt-section[data-folds="' + triggerId + '"]:not(.yt-temp-hide)').show();
					$(this).data('show', 'yes').removeClass('collapse');
				}
				
				e.preventDefault();
			
			},
			/**
			 * Time picker
			 */
			timePicker: function( _selector ){
				if ( $( _selector ).length ) {
					//console.log('hâhhaha');
					$(_selector).each(function () {
						
						$('#' + $(this).attr('id')).on('keydown keypress', function(event) {
							if ((event.keyCode !== 38 || event.keyCode !== 40 || event.keyCode !== 13 || event.keyCode !== 27 )) {
								event.preventDefault();
							}
						});
						
						$('#' + $(this).attr('id')).timePicker({
							startTime: "07:00",
							endTime: "23:00",
							show24Hours: true,
							separator: ':',
							step: 30
						});
					});
				}
			},
			
			/**
			 * Toggles
			 */
			toggleButtons: function(e){
				
				var $el = $(this);
				$el.removeClass('button').addClass('button-primary')
				.siblings('label').removeClass('button-primary').addClass('button')
				.siblings('[type="hidden"]').val($el.data('value'));
				
				e.preventDefault();
			},
			
			/**
			 * Preview for typography
			 */
			typographyPreview: function(){
				
				var _yt_typo = [];
				
				$(document).on('click', '.yt-typography-preview', function(e){
				
					var $el = $(this),
						css = '';
					
					/*Do each() to get the css attribute*/
					$el.siblings('[data-att]').each(function(index, element) {
						
						if($(this).data('att') === 'font-family'){
						
							css += $(this).data('att') + ':' + $(this).find('option:selected').data('val') + ';' + "\n";
						
						}else if($(this).data('att') === 'color'){
						
							css += $(this).find('input[type=text]').val() ? $(this).data('att') + ':' + $(this).find('input[type=text]').val() + ';' + "\n" : '';
							
						}else{
							
							css += $(this).data('att') + ':' + $(this).find('select').val() + ';' + "\n";
						
						}
					});
					
					var selected_face = $el.siblings('[data-att="font-family"]').find('option:selected'),
						google_font = '',
						google_font_url = '',
						stylesheet;
					
					/* if selected font fade is google font*/
					if( selected_face.data('font') == 'google-font'){
						
						google_font = selected_face.val().replace('googlefont-','');
						stylesheet = '<link id="yt-typography-stylesheet" href="http://fonts.googleapis.com/css?family=' + google_font + '" rel="stylesheet" type="text/css">';
					
					}
					
					/* if isset google font, append things to browser*/
					if( google_font ){
						
						/*if google font is not in array, take it*/
						if( _yt_typo.indexOf( google_font ) < 0 ){
							_yt_typo.push( google_font );
						}
						
						if($('#yt-typography-stylesheet').length){
							
							/*Join font from array*/
							google_font_url = 'http://fonts.googleapis.com/css?family=' + _yt_typo.join('|');
							
							/*Only change the href when google_font_url != current href */
							if( $('#yt-typography-stylesheet').attr('href') !== google_font_url ){
							
								$('#yt-typography-stylesheet').attr('href', google_font_url );
								//console.log(_yt_typo);	
							}
								
						}else{
						
							$('head').append( stylesheet );
							
						}
					}
					
					/* change the preview style*/
					$(this).siblings('.yt-typography-preview-area').removeClass('yt-hidden').attr('style', css );
					
					e.preventDefault();
					
				});	
			},
			
			/**
			 * UI Sliders
			 */
			uiSlider: function( _selector){
				if ( $( _selector ).length ) {
					$( _selector ).each(function(index, element) {
						var $el = $(this),
							dataValue = parseInt($el.data('value')),
							dataMin = parseInt($el.data('min')),
							dataMax = parseInt($el.data('max')),
							dataStep = parseInt($el.data('step'));
						
						$el.slider({
							range:"min",
							value: dataValue,
							min: dataMin,
							max: dataMax,
							step: dataStep,
							slide: function( event, ui ) {
								$el.closest('.yt-controls').find('.ui-slider-textfield').val( ui.value );
							}
						});
					});
				}
			},
			
			/**
			 * Repeatable field helper
			 */
			repeatableFieldsHelper:{
				
				resetFormFields: function(field, selectors){
				
					if(!selectors)
						selectors = 'select, input[type=hidden], textarea, input[type=text]';
					
					field.find(selectors).val('');
					field.find('.yt-screenshot').html('');
				},
				
				updateFormAttr: function(child, parent){
				
					setTimeout(function(){
						$(child, parent).each(function(){
							var position = $(this).index();
							$(this).find('[name]').each(function(){
								$(this).attr('name',function(index, name) {
									return name.replace(/(\d+)/, function(fullMatch, n) {return position;});
								}).attr('id',function(index, name) {
									return name.replace(/(\d+)/, function(fullMatch, n) {return position;});
								})
							});
							
							$(this).removeAttr('style');
						});
		
					},500);
				}
				
			},
			/**
			 * Repeatable fields
			 *
			 * self.optionSettingControlsAction.repeatableFields.resetFormFields()
			 * self.optionSettingControlsAction.repeatableFields.updateFormAttr()
			 * 
			 * delete trigger
			 */
			
			repeatableFields: function( callSortableOnly ){
			
				var self = Yeahthemes.OptionsFramework,
					oddClick = true;
					
				callSortableOnly = callSortableOnly || false;
				
				if( callSortableOnly !== true){
				/*Expand*/
				$(document).on('click', '.yt-repeatable-controls.yt-repeatable-expand', function(e){
					
					var $el = $(this);
					
					if(oddClick){
						$(this).closest('.yt-repeatable-field-block').removeClass('collapsed');
						$el.text('-');
					}else{
						$(this).closest('.yt-repeatable-field-block').addClass('collapsed');
						$el.text('+');
					}
					
					oddClick = !oddClick;
					e.preventDefault();
				})
				
				/*Delete*/
				.on('click', '.yt-repeatable-controls.yt-repeatable-delete', function(e){
					var $el = $(this),
						parent = $(this).closest('.yt-repeatable-fields-wrapper'),
						count = parent.find('ul').children().length,
						clone = $( parent.data('clone') );
					
					clone.find('> .collapsed').removeClass('collapsed');

					if(count == 1){
						//alert(yt_optionsVars.ytMsgDeleteRepeatableField);
						$el.closest('li').replaceWith( clone );
						oddClick = !oddClick;
						//self.optionSettingControlsAction.repeatableFieldsHelper.resetFormFields( $el.closest('li') );
					}else{
						$el.closest('li').slideUp('medium', 'swing', function(){
							$el.closest('li').remove();
							self.optionSettingControlsAction.repeatableFieldsHelper.updateFormAttr('li', parent);
						});
					}
										
					e.preventDefault();
				})
				
				/* Add more repeatble field */
				.on('click', '.yt-repeatable-fields-wrapper .yt-button-add-more', function(e){
					
					var thisLocation = $(this),
						rParent = '.yt-repeatable-fields-wrapper',
						field = $(this).closest(rParent).data('clone'),
						sortableFields = $(this).closest(rParent).find('> ul');
					
					//console.log( $( field ));					
					//CHANGE NAME, ID, clear field
					//self.optionSettingControlsAction.repeatableFieldsHelper.resetFormFields( $(field), '');
					$( field ).appendTo(sortableFields);
					self.optionSettingControlsAction.repeatableFieldsHelper.updateFormAttr('li', $(this).closest(rParent));
					
					e.preventDefault();
					
				})
				
				/* Expand all */
				.on('click', '.yt-repeatable-fields-wrapper .yt-button-expand-collapse-all', function(e){
					
					var thisUl = $(this).siblings('ul');
					console.log(thisUl.data('collapsed'));
					if(thisUl.data('collapsed') == true){
						
						thisUl.find('li').children('.collapsed').removeClass('collapsed').find('.yt-repeatable-expand').text('-');
						thisUl.data('collapsed', false);
						
					}else{
						thisUl.find('li').children().addClass('collapsed').find('.yt-repeatable-expand').text('+');
						thisUl.data('collapsed', true);
					}
					e.preventDefault();
					
				});
				
				}
				
				$('.yt-repeatable-fields-wrapper ul').sortable({
					opacity: 0.6,
					revert: true,
					stop:function(event,ui){
						ui.item.removeAttr('style');
					},
					placeholder : 'yt-ui-sortable-placeholder',
					sort : function( event, ui ) {
						$('.yt-ui-sortable-placeholder').css({
							'height':$(this).find('.ui-sortable-helper').height()-6,
							'width':$(this).find('.ui-sortable-helper').width() -30}
						);
						$(this).addClass('sortabling');
					},
					cursor: 'move',
					update: function(event, ui) {
						self.optionSettingControlsAction.repeatableFieldsHelper.updateFormAttr('li', this);
						$(this).removeClass('sortabling');
					}
				})
				
			
			},
			
			/**
			 * Tab ( Nested Tabs/Metabox Tab ) 
			 */
			tabGroup: function(){
				if( !$('.yt-group-tab-wrapper').length )
					return;
					
				$('.yeah-framework .yt-group-tab-wrapper').each(function() {
					
					var $el = $(this),
						$tabHeading = $el.find('ul[data-ul-tab]'),
						$cookie_id = $el.attr('id');	
						
					if( $( '#post_ID[name="post_ID"]' ).length ){
						$cookie_id = $cookie_id + '_' + $( '#post_ID[name="post_ID"]' ).val();
					}
					
					if ( !$.cookie( $cookie_id ) ) {
							
						$('li:first', $tabHeading ).addClass('active');
						$('.yt-group-tab:first', $el).addClass('active');
						
					}else{
						
						$('li[data-index="' + $.cookie( $cookie_id ) + '"]', $tabHeading ).addClass('active');
						$('.yt-group-tab[data-tab="' + $.cookie( $cookie_id ) + '"]', $el).addClass('active');
					
					}
					//if(  )
					
					//$.cookie('yt_current_opt', clicked_group, { expires: 7, path: '/' });
					
					$('li', $tabHeading).on( 'click', function(){
						
						if( $(this).hasClass('active') ) return;
						
						$(this).addClass('active').siblings('li').removeClass('active');
						
						var tabIndex = $(this).index() + 1;
						
						$(this).closest( $tabHeading ).siblings('.yt-group-tab[data-tab="' + tabIndex + '"]').addClass('active').siblings('.yt-group-tab.active').removeClass('active');
						
						if( $el.attr("data-cookie") == undefined || parseInt($el.attr("data-cookie")) == 1)
							$.cookie( $cookie_id, tabIndex, { expires: 7, path: '/' });
					});
				
				});
			}
			
		},
		
		/**
		 * Ajaxifying Actions
		 */
		 
		optionSettingPanelAjaxifyingFunctions: {
			/**
			 * Backup
			 */
			backup: function( self, prefix, nonce, option_key){
				
				self.helper.processing();
				
				var data = {
					action: 'yt_ajax_save_options',
					type: 'backup_options',
					security: nonce,
					key: option_key,
					prefix: prefix
				};
							
				$.post( ajaxurl, data, function(response) {
								
					self.helper.doneProcessing();
					
					console.log(response);
					//check nonce
					if(response==-1){ //failed
									
						self._container.addClass(self._failPopup + '-active');      
						window.setTimeout(function(){
							self._container.removeClass(self._failPopup + '-active');                      
						}, 2000);
					}
								
					else {
								
						self._container.addClass(self._successPopup + '-active');
						window.setTimeout(function(){
							location.reload();                        
						}, 1000);
					}
								
				});
					
				
			},
			
			/**
			 * Restore
			 */
			restore: function( self, prefix, nonce, option_key){
				
				self.helper.processing();
			
				var data = {
					action: 'yt_ajax_save_options',
					type: 'restore_options',
					security: nonce,
					key: option_key,
					prefix: prefix
				};
							
				$.post(ajaxurl, data, function(response) {
				
					self.helper.doneProcessing();
					//check nonce
					if(response==-1){ //failed
									
						self._container.addClass(self._failPopup + '-active');
						window.setTimeout(function(){ 
							self._container.removeClass(self._failPopup + '-active');                      
						}, 2000);
					}
								
					else {
		
						self._container.addClass(self._successPopup + '-active');
						window.setTimeout(function(){
							location.reload();                        
						}, 1000);
					}	
				});
			},
			
			/**
			 * Import
			 */
			import: function( self, prefix, nonce, option_key ){
				
				self.helper.processing();
				
				var import_data = $('#yt-export-data').val();
				
				var data = {
					action: 'yt_ajax_save_options',
					type: 'import_options',
					security: nonce,
					data: import_data,
					key: option_key,
					prefix: prefix
				};
				
				$.post(ajaxurl, data, function(response) {
					console.log(response);
					self.helper.doneProcessing();
					//check nonce
					if(response==-1){ //failed
						self._container.addClass(self._failPopup + '-active');
						window.setTimeout(function(){
							self._container.removeClass(self._failPopup + '-active');
						}, 2000);
					}		
					else 
					{
						self._container.addClass(self._successPopup + '-active');
						window.setTimeout(function(){
							location.reload();                        
						}, 1000);
					}
								
				});
			
			},
			
			/**
			 * Reset
			 */
			reset: function( self, prefix, nonce, option_key ){
				
				self.helper.processing();
				
				var default_data = $('[name="yt_option_default_data"]').val();
							
				var data = {
					type: 'reset',
					action: 'yt_ajax_save_options',
					data: default_data,
					security: nonce,
					key: option_key,
					prefix: prefix
				};
							
				$.post(ajaxurl, data, function(response) {
					console.log(response);
					self.helper.doneProcessing();
					
					if (response==1)
					{
						self._container.addClass(self._resetPopup + '-active');
						window.setTimeout(function(){
							location.reload();                        
						}, 1000);
					} 
					else 
					{ 
						console.log(response);
						self._container.addClass(self._failPopup + '-active');
						window.setTimeout(function(){
							
							self._container.removeClass(self._failPopup + '-active');		
						}, 2000);
					}
				});
			},
			
			/**
			 * Save
			 */
			save: function( self, prefix, nonce, option_key, trigger){
				
				self.helper.processing();
				
				//get serialized data from all our option fields			
				var serializedReturn = $('#yt-form :input[name][name!="security"][name!="yt-reset"]').serialize();
				
				$('#yt-form :input[type=checkbox]').each(function() {     
					if (!this.checked) {
						serializedReturn += '&' + this.name;
					}
				});
				
				var data = {
					type: 'save',
					action: 'yt_ajax_save_options',
					security: nonce,
					data: serializedReturn,
					key: option_key,
					prefix: prefix
				};
							
				$.post(ajaxurl, data, function(response) {
					
					self.helper.doneProcessing();
								
					if (response==1) {
						self._container.addClass(self._successPopup + '-active');
						
						if( trigger.is('.yt-save-and-refresh')){
							window.setTimeout(function(){
								location.reload();                        
							}, 1000);
						}
						console.log(response);
					} else { 
						self._container.addClass(self._failPopup + '-active');
						console.log(response);
					}
								
					window.setTimeout(function(){
						self._container.removeClass(self._failPopup + '-active' + ' ' + self._successPopup + '-active');			
					}, 2000);
				});	
			}
			
		},
		optionSettingPanelAjaxifyingActions: function(){
			
			var self = this,
				prefix = $('[name="yt_option_prefix"]').val(),
				nonce = $('[name="yt_options_ajaxify_data_nonce"]').val(),
				option_key = $('[name="yt_option_key"]').val();
				
			$(document)
			/**
			 * Backup
			 */
			.on('click', '.yt-theme-options-framework-wrapper #yt-backup-button', function(e){
			
				var answer = confirm(yt_optionsVars.ytMsgBackup);
				
				if (answer){
					self.optionSettingPanelAjaxifyingFunctions.backup( self, prefix, nonce, option_key);
				}
				
				e.preventDefault();
							
			})
			
			/**
			 * Restore
			 */
			.on('click', '.yt-theme-options-framework-wrapper #yt-restore-button', function(e){
			//$('#yt-restore_button').live('click', function(){
			
				var answer = confirm(yt_optionsVars.ytMsgRestore);
				
				if (answer){
					self.optionSettingPanelAjaxifyingFunctions.restore( self, prefix, nonce, option_key);
				}
			
				e.preventDefault();
							
			})
			
			/**	Ajax Transfer (Import/Export) Option */
		
			.on('click', '.yt-theme-options-framework-wrapper #yt-import-button', function(e){
			
				var answer = confirm(yt_optionsVars.ytMsgImport);
				
				if (answer){
					self.optionSettingPanelAjaxifyingFunctions.import( self, prefix, nonce, option_key );
				}
				
				e.preventDefault();
							
			})
			
			/** AJAX Save And Refresh Options */
		
			.on('click', '.yt-theme-options-framework-wrapper .yt-save-theme-options', function(e){
					
				var $el = $(this);
							
				self.optionSettingPanelAjaxifyingFunctions.save( self, prefix, nonce, option_key, $el );
					
				e.preventDefault();
							
			})
			
			/* AJAX Options Reset */
			.on('click', '.yt-theme-options-framework-wrapper #yt-reset', function(e){
				
				//confirm reset
				var answer = confirm(yt_optionsVars.ytMsgReset) ;
				
				//ajax reset
				if (answer){
					self.optionSettingPanelAjaxifyingFunctions.reset( self, prefix, nonce, option_key );
				}
					
				e.preventDefault();
				
			});
		
			
		},
		
		
	}


	/*-----------------------------------------------------------------------------------
	 * Yeahtheme Media Uploader
	 * @author Yeahthemes
	 * @since 1.0
	 * @support wp 3.5+
	 *-----------------------------------------------------------------------------------*/

	Yeahthemes.OptionsFrameworkUploader = {
  		mediaModal:{
			
			selectedID: 0,
			mediaFilter:''
				
		},
		/*-----------------------------------------------------------------------------------
		 * Remove file when the "remove" button is clicked.
		 *-----------------------------------------------------------------------------------*/
  
		removeFile: function () {
		 
			$(document).on('click', '.yt-media-remove-button', function(e){
				
				var $el = $(this);
				
				/*
				 * - remove text file value
				 * - hide remove button
				 * - remove screenshot
				 */
				$el.parent().siblings('.yt-input').val('');
				$el.addClass('yt-hidden');
				//$el.parent('.yt-button-action').siblings('.yt-screenshot').html('');
				$el.parent('.yt-button-action').siblings('.yt-screenshot').children().slideUp('medium', 'swing', function(){
					$(this).remove();
				});
				e.preventDefault();
			});
		  
		}, // End removeFile
	
	
		/*-----------------------------------------------------------------------------------
		 * Upload media using wp media model
		 *-----------------------------------------------------------------------------------*/
		yeahMediaModel : function( _heading_text, _filterable, _dataFilter){
			var media = wp.media,
				Attachment  = media.model.Attachment,
				l10n;
			l10n = media.view.l10n = typeof _wpMediaViewsL10n === 'undefined' ? {} : _wpMediaViewsL10n;
			
			_filterable = _filterable || 'all',
			_heading_text = _heading_text || l10n.insertMediaTitle,
			_dataFilter = _dataFilter || '';
			
			media.controller.yeahMediaModel = media.controller.Library.extend({
				defaults: _.defaults({
					id:         'yeah-media',
					title:      l10n.mediaLibraryTitle,
					priority:   20,
					filterable: _filterable,
					searchable: true,
					editable:   true,
					allowLocalEdits: false,
					displaySettings: true,
					displayUserSettings: false,
					library:  media.query( {
						type: _dataFilter
					})
				}, media.controller.Library.prototype.defaults ),
				activate: function() {
					this.updateSelection();
					this.frame.on( 'open', this.updateSelection, this );
					media.controller.Library.prototype.activate.apply( this, arguments );
				},
		
				deactivate: function() {
					this.frame.off( 'open', this.updateSelection, this );
					media.controller.Library.prototype.deactivate.apply( this, arguments );
				},
		
				updateSelection: function() {
					
					var selection = this.get('selection'),
						id = Yeahthemes.OptionsFrameworkUploader.mediaModal.selectedID,
						attachment;
		
					if ( '' !== id && -1 !== id ) {
						attachment = Attachment.get( id );
						attachment.fetch();
					}
		
					selection.reset( attachment ? [ attachment ] : [] );
				}
			
			});
				
			
		},	
		mediaUpload: function () {
			
			// Uploading files
			var file_frame,
				heading_text,
				wp_media_post_id = wp.media.model.settings.post.id, // Store the old id
				set_to_post_id = 10,
				dataFilter,
				$ytu,
				imgID; //Selector
			
			$(document).on('click', '.yt-media-upload-button', function(e){
				
				
				$('head').append('<style id="yt-framework-media-hack">.attachment-display-settings h3 + .setting, .attachment-display-settings h3 + .setting + .setting{\
						display:none;\
					}</style>'
				);
				$ytu = $(this),
				heading_text =  $ytu.data('title'),
				//set_to_post_id = $ytu.attr('rel'),
				imgID = $ytu.attr('id'),
				dataFilter = $ytu.data('filter');
				
				
				Yeahthemes.OptionsFrameworkUploader.mediaModal.selectedID = $ytu.attr('data-id');
				//Yeahthemes.OptionsFrameworkUploader.mediaModal.mediaFilter = dataFilter;
				
				/*
				 * Mime types
				 */
				var image = /(^.*\.jpg|jpeg|png|gif|svg|ico*)/gi;
				var document = /(^.*\.pdf|doc|docx|ppt|pptx|odt*)/gi;
				var audio = /(^.*\.mp3|m4a|ogg|wav*)/gi;
				var video = /(^.*\.mp4|m4v|mov|wmv|avi|mpg|ogv|3gp|3g2*)/gi;
			
				e.preventDefault();
				
				/*
				 * If the media frame already exists, reopen it.
				 */
				if ( file_frame ) {
					
					/*
					 * Set the post ID to what we want
					 */
					//file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					
					$('.media-modal .media-frame-title h1').html(heading_text);
					
					/*
					 * Open frame
					 */
						
					file_frame.open();
					
					return;
					
				} else {
					/*
					 * Set the wp.media post id so the uploader grabs the ID we want when initialized
					 */
					//wp.media.model.settings.post.id = set_to_post_id;
				}
				
				/*
				 * Create the media frame.
				 */
				file_frame = wp.media.frames.file_frame = wp.media({
					state:    'yeah-media',
					button: {
						text: yt_optionsVars.ytUseImage
					},
					multiple: false  // Set to true to allow multiple files to be selected
					//library : { type : 'all'},
					
				});
				
				
				/*file_frame.states.add([

					new wp.media.controller.Library({
						id:         'yeah-media',
						title:      heading_text,
						priority:   20,
						filterable: 'all',
						searchable: true,
						editable:   true,
						allowLocalEdits: false,
						displaySettings: true,
						displayUserSettings: false,
						library:  wp.media.query( {
							type: dataFilter
						})
					}),
				]);*/
				file_frame.states.add([
					new wp.media.controller.yeahMediaModel()
				]);
				
				file_frame.on('open', function(){
				});
				/*
				 * When escape
				 */
				file_frame.on('escape', function(){
					//Do some stuff here
					//console.log('escape');
				});
				/*
				 * When an image is selected, run a callback.
				 */
				file_frame.on( 'select', function() {
					
					//console.log($ytu.parent().prev('.yt-input').attr('id'));
					/*
					 * We set multiple to false so only get one image from the uploader
					 */
					
					var attachment = file_frame.state().get('selection').first().toJSON(),
						preview,
						attachment_url = attachment.url;
						
						
					if( $('.attachment-display-settings select option:selected') !== 'full' && $('.attachment-display-settings select[data-setting="size"]').length ){
						
						var attachment_size = $('.attachment-display-settings select[data-setting="size"]').val();
						
						if( typeof( attachment['sizes'] ) != 'undefined' && typeof( attachment.sizes[attachment_size] ) != 'undefined' && typeof( attachment.sizes[attachment_size].url ) != 'undefined' ){
							attachment_url = attachment.sizes[attachment_size].url;
						}
					}
					
						
						
					//do something with attachment variable, for example attachment.filename
					//Object:
					//attachment.alt - image alt
					//attachment.author - author id
					//attachment.caption
					//attachment.dateFormatted - date of image uploaded
					//attachment.description
					//attachment.editLink - edit link of media
					//attachment.filename
					//attachment.height
					//attachment.icon - don't know WTF?))
					//attachment.id - id of attachment
					//attachment.link - public link of attachment, for example ""http://site.com/?attachment_id=115""
					//attachment.menuOrder
					//attachment.mime - mime type, for example image/jpeg"
					//attachment.name - name of attachment file, for example "my-image"
					//attachment.status - usual is "inherit"
					//attachment.subtype - "jpeg" if is "jpg"
					//attachment.title
					//attachment.type - "image"
					//attachment.uploadedTo
					//attachment.url - http url of image, for example "http://site.com/wp-content/uploads/2012/12/my-image.jpg"
					//attachment.width
					
					/*
					 * Do something with attachment.id and/or attachment.url here
					 */
					if (attachment_url.match(image)) {
						preview = '<a class="yt-uploaded-image" href="' + attachment_url + '">\
							<span class="yt-img-border yt-transparent-bg"><img class="yt-option-image" id="image_' + imgID + '" src="' + attachment_url + '" alt="" /></span>\
						</a>';
					}else{
						if( $ytu.attr("data-format") !== undefined && $ytu.data('format') == 'mixed' ){
							
						}else{
							preview = '<p>' + yt_optionsVars.ytNotImage + ' <a href="' + attachment_url + '" target="_blank" rel="external">' + yt_optionsVars.ytViewFile + '</a></p>';
						
						}
					}
					$ytu.parent().siblings('.yt-media-input').val( $ytu.data('by') === 'id' ? attachment.id : attachment_url /*+ '?id=' + attachment.id*/ );
					$ytu.parent().siblings('.yt-screenshot').fadeIn().html(preview);
					$ytu.next('span').removeClass('yt-hidden');
					$ytu.attr('data-id', attachment.id);
					
					/*
					 * Restore the main post ID
					 */
					//wp.media.model.settings.post.id = wp_media_post_id;
					
					$('head').find('#yt-framework-media-hack').remove();
					
				});
				
				/*
				 * Finally, open the modal
				 */
				file_frame.open();
			});
			
			/*
			 * Restore the main ID when the add media button is pressed
			 */
			$('a.media-button').on('click', function() {
				
				//console.log(wp.media.model.settings.post.id + '-' + wp_media_post_id);
				//wp.media.model.settings.post.id = wp_media_post_id;
			});
		
		}, // End mediaUpload
		
		galleryUpload: function () {
			
				
			// Uploading files
			var gallery_frame,
				attachment_ids,
				$image_gallery_list,
				$image_gallery_ids,
				$ytgu;
			
			
			$(document).on('click', '.yt-gallery-add-image', function(e){
				
				$ytgu = $(this);
				
				$image_gallery_list = $ytgu.siblings('.yt-gallery-list'),
				$image_gallery_ids = $ytgu.siblings('.yt-gallery-hidden-input'),
				attachment_ids = $image_gallery_ids.val();
				
				e.preventDefault();
				
				// If the media frame already exists, reopen it.
				if ( gallery_frame ) {
					gallery_frame.open();
					return;
				}
				
				// Create the media frame.
				gallery_frame = wp.media.frames.gallery_frame = wp.media({
					// Set the title of the modal.
					title: $ytgu.data('title'),
					button: {
						text: $ytgu.data('button')
					},
					multiple: true,
					library : { type : 'image'}
				});
				
				// When an image is selected, run a callback.
				gallery_frame.on( 'select', function() {

					var selection = gallery_frame.state().get('selection');

					selection.map( function( attachment ) {

						attachment = attachment.toJSON();
						
						//console.log(attachment);
						
						var attachment_url = attachment.url
						
						if( typeof( attachment['sizes'] ) != 'undefined' && typeof( attachment.sizes['thumbnail'] ) != 'undefined' && typeof( attachment.sizes.thumbnail['url'] ) != 'undefined' ){
							attachment_url = attachment.sizes.thumbnail.url;
						}

						if ( attachment.id ) {
							
							attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

							$image_gallery_list.append('\
								<li class="image yt-transparent-bg" data-attachment-id="' + attachment.id + '">\
									<span><img src="' + attachment_url + '" /></span>\
									<ul class="yt-gallery-actions">\
										<li><a href="#" class="yt-gallery-delete" title="' + yt_optionsVars.ytDeleteTitle + '"><i class="yt-icon-trash"></i></a></li>\
									</ul>\
								</li>'
							);
							
							$image_gallery_list.siblings('.yt-gallery-delete-all').removeClass('yt-hidden');
								
						}
						
						
						
					});

					$image_gallery_ids.val( attachment_ids );
					
					
					
				});
				
				/*
				 * When escape
				 */
				gallery_frame.on('escape', function(){
					//Do some stuff here
					//console.log('escape');
					
				});
				

				// Finally, open the modal.
				gallery_frame.open();
				
			});
			
			/*
			 * Restore the main ID when the add media button is pressed
			 */
			$('a.media-button').on('click', function() {
				
				//console.log(wp.media.model.settings.post.id + '-' + wp_media_post_id);
				//wp.media.model.settings.post.id = wp_media_post_id;
			});
			
			/**
			 * Image ordering
			 */
			$('.yt-gallery-list').sortable({
				items: 'li.image',
				opacity: 0.6,
				revert: true,
				placeholder: 'yt-ui-sortable-placeholder',
				stop: function(event, ui) {
					$('li',this).removeAttr('style');	
				},
				sort : function( event, ui ) {
					$('.yt-ui-sortable-placeholder').css({
						'height':$(this).find('.ui-sortable-helper').height()-6,
						'width':$(this).find('.ui-sortable-helper').width() -6}
					);
				},
				update: function(event, ui) {
					
					var attachment_ids = '',
						$image_gallery_ids = $(this).siblings('.yt-gallery-hidden-input');
						
					$(this).find('li.image').each(function() {
						var attachment_id = $(this).data( 'attachment-id' );
						attachment_ids = attachment_ids + attachment_id + ',';
					});

					$image_gallery_ids.val( attachment_ids );
				}
			});
			
			// Remove images
			$(document).on( 'click', '.yt-gallery-delete', function(e) {
				
				var $el = $(this),
					attachment_ids = '',
					$image_gallery_list = $el.closest('.yt-gallery-list'),
					$image_gallery_ids = $image_gallery_list.siblings('.yt-gallery-hidden-input');
					
				
					
				$el.closest('li.image').remove();
				
				if( !$image_gallery_list.children().length ){
					$image_gallery_list.siblings('.yt-gallery-delete-all').addClass('yt-hidden');
				}

				$image_gallery_list.find('li.image').each(function() {
					var attachment_id = $(this).data( 'attachment-id' );
					attachment_ids = attachment_ids + attachment_id + ',';
				});

				$image_gallery_ids.val( attachment_ids );

				e.preventDefault();
			} );
			
			// Remove all images
			$(document).on( 'click', '.yt-gallery-delete-all', function(e) {
				
				var $el = $(this),
					$image_gallery_list = $el.siblings('.yt-gallery-list'),
					$image_gallery_ids = $el.siblings('.yt-gallery-hidden-input');
				
				var answer = confirm($el.data('confirm'));
				
				if(answer){
					
					
					//$image_gallery_list.children().remove();
					
					var $childLi = $image_gallery_list.children(),
						$length = $childLi.length - 1;
					
					$childLi.each( function(index, element){
						var $children = $(this);
						setTimeout( function(){
							
							$children.addClass('yt-animate-slide-out-left');
							
							if( index === $length ){
							
								$childLi.slideUp('medium', 'swing', function(){
									$childLi.remove();
								});	
							
							}
						}, index*100 );
					})
					$image_gallery_ids.val( '' );
					$el.addClass('yt-hidden');
				}
				
				e.preventDefault();
			});
		
		} // End mediaUpload
	   
	}; // End yt_OptionsYMU Object // Don't remove this, or the sky will fall on your head.
	
	/*-----------------------------------------------------------------------------------
	 * Yeahtheme Helper
	 * @author Yeahthemes
	 * @since 1.0
	 * @support wp 3.5+
	 *-----------------------------------------------------------------------------------*/
	
	
	Yeahthemes.Helper = {
		triggering: function(_selector, _trigger){
			if( $(_selector).length )
				$(_selector).trigger(_trigger);
				
			return false;
		},
		has3d:function() {
			var el = document.createElement('p'), 
				has3d,
				transforms = {
					'webkitTransform':'-webkit-transform',
					'OTransform':'-o-transform',
					'msTransform':'-ms-transform',
					'MozTransform':'-moz-transform',
					'transform':'transform'
				};
		
			// Add it to the body to get the computed style.
			document.body.insertBefore(el, null);
		
			for (var t in transforms) {
				if (el.style[t] !== undefined) {
					el.style[t] = "translate3d(1px,1px,1px)";
					has3d = window.getComputedStyle(el).getPropertyValue(transforms[t]);
				}
			}
		
			document.body.removeChild(el);
		
			return (has3d !== undefined && has3d.length > 0 && has3d !== "none");
		},
		tabifyTextarea: function( e ){
			var keyCode = e.keyCode || e.which;

			if (keyCode == 9) {
			    e.preventDefault();
			    var start = $(this).get(0).selectionStart;
			    var end = $(this).get(0).selectionEnd;

			    // set textarea value to: text before caret + tab + text after caret
			    $(this).val($(this).val().substring(0, start)
			                + "\t"
			                + $(this).val().substring(end));

			    // put caret at right position again
			    $(this).get(0).selectionStart =
			    $(this).get(0).selectionEnd = start + 1;
			}else if(event.shiftKey && event.keyCode == 9) { 
				//shift was down when tab was pressed
			}
		}
	}
	
	Yeahthemes.OptionsFramework.init();
	
	
})(jQuery);

