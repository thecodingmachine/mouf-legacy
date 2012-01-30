		<style>
			body {
				font-family: sans-serif;
				font-size: 11px;	
			}
			table.calendar {
				border-collapse: collapse;
				padding: 0px;
			}
			
			table.calendar tr,
			table.calendar tr td,
			table.calendar tr th {
				padding: 0px;
			}
			
			tr.hour, tr.hour td {
				border-top: 1px solid #BBBBBB;
			}
			tr.hour_half, tr.hour_half td {
				border-top: 1px dashed #BBBBBB;
			}
			table.calendar td {
				border-right: 1px solid #BBBBBB;
				cursor: default;
			}
			table.calendar td.days_name {
				width: 150px;
				text-align: center;
				font-size: 11px;
			}
			.over {
				background-color: #99BBFF;
			}
			.over_start, .event_start {
				border-top-left-radius: 10px;
				border-top-right-radius: 10px;
			}
			.over_end, .event_end{
				border-bottom-left-radius: 10px;
				border-bottom-right-radius: 10px;
			}
			.drag_bottom {
				height: 10px;
				width: 100%;
				background-color: red;
				position: absolute;
				bottom: 0px;
				left: 0;
			}
			.drag_top {
				height: 10px;
				width: 100%;
				background-color: red;
				position: absolute;
				top: 0px;
				left: 0;
				z-index: -1;
			}
			
			.display_none {
				display: none
			}
		</style>
		<script src="<?php echo SITE_URL ?>util/jquery/jquery/1.6/jquery-1.6.min.js"></script>
		<script src="<?php echo SITE_URL ?>util/jquery/jquery-ui/1.8.9/js/jquery-ui-1.8.9.custom.min.js"></script>
		<script src="<?php echo SITE_URL ?>util/jquery/jquery-jqal/0.1-alpha/js/date.js"></script>
		<script src="<?php echo SITE_URL ?>util/jquery/jquery-jqal/0.1-alpha/js/language.js"></script>
		<script>
		
		/*
		if ('undefined' !== typeof this.onselectstart) {
		    this.onselectstart = function () { return false; };
		} 
		*/
			(function( $ ){
				// List of the display day
				var listdays = new Array();
				// Type of selection create, size down or size up
				var typeSelect = null;
				// First date selected to add event
				var startSelect = null;
				// End date selected to add event
				var endSelect = null;
				// Start or end of event. This date cannot be move with the other selected date
				var startSelectEventLimit = null;
				// Start date of calendar
				var startDate = null;
				// End date of calendar
				var endDate = null;
				// Event List
				var events = new Array();
				// First index of event list that must be check to add a new event.
				var indexStartEvent = -1;
				
				// Size of column
				var columnWidth = 61;
				// Size of first column
				var columnWidthFirst = 46;
				var columnWidth = 151;
				// Size for one minute in pixel
				var oneMinute = 40/60;
				
				/**
				 * Draw the calendar
				 */
				var draw = function(elementJquery) {
					element = $(this[0]);
					// Display table
					var table = $('<table class="calendar">');
					var tr = $('<tr style="height: 25px">');
					$("<td>")
						.appendTo(tr);
					// Add all day title
					for (i = 0; i <= 6; i ++) {
						$('<td class="days_name">')
							.text(dateLanguage['fr'].day[i])
							.appendTo(tr);
					}
					// Display 24 hours for each day
					startDate = new Date(<?php echo date('Y', $dateFrom); ?>, <?php echo date('m', $dateFrom); ?>, <?php echo date('d', $dateFrom); ?>, 0, 0, 0, 0).getTime()/1000;
					for (i = 0; i <= 23; i ++) {
						// 0 is 0 minute and 1 is 30 minutes
						for(j = 0; j <= 1; j ++) {
							table.append(tr);
							if(j == 0)
								tr = $('<tr class="hour">');
							else
								tr = $('<tr class="hour_half">');
							$('<td style="width: 45px; height: 20px">')
								.text(i+'h'+(j*30))
								.appendTo(tr);
							// 7 days of week
							for (d = 0; d <= 6; d ++) {
								var timestamp = new Date(<?php echo date('Y', $dateTo); ?>, <?php echo date('m', $dateTo); ?>, <?php echo date('d', $dateTo); ?>, i, j*30, 0, 0).getTime()/1000;
								listdays.push(timestamp);
								$('<td id="'+timestamp+'" onselectstart="return false">')
									.addClass('day_element')
									.html('&nbsp;')
									.appendTo(tr);
							}
							table.append(tr);
						}
					}
					endDate = timestamp;
					listdays.sort();
					element.html(table);
					selectEvent();
					if ('undefined' !== typeof $('.calendar').onselectstart) {
						alert('oui');
						$('.calendar').onselectstart = function () { return false; };
					} 
					//TODO Recuperer la largeur et la hauteur de la premiere colonne et de la premiere ligne.
				}

				/**
				 * Add mouse event to the day display. This is to add or move event
				 */
				selectEvent = function() {
					/**
					 * When the user press his mouse to create new event
					 */
					$('.day_element').mousedown(function() {
						// Remove all class
						$('.day_element').removeClass('over');
						// Add class to display the day selected
						$(this).addClass('over');
						$(this).addClass('over_start');
						$(this).addClass('over_end');
						// Initialize the position
						startSelect = parseInt(this.id);
						endSelect = null;
						typeSelect = 'create';
						//TODO Bind mouseover !!!!!!!
						return false;
					});
					
					/**
					 * When the user release the click. end of event
					 */
					$('.day_element').mouseup(function() {
						// If the user has press the mouse to create event
						if(typeSelect == 'create') {
							$('.day_element').removeClass('over_start');
							$('.day_element').removeClass('over_end');
							$('.day_element').removeClass('over');
							// End date
							endSelect = parseInt(this.id);
							var date = new Date();
							date.setTime(endSelect*1000);
							//TODO fire event
							alert(startSelect+' - '+(date.addMinutes(30).getTime()/1000 - 1));
							startSelect = null;
							typeSelect = null;
						}
						// If the user has press the mouse to resize event
						if(typeSelect == 'sizedown') {
							typeSelect = null;
							resizeEvent = null;
							endSelectedEvent = parseInt(this.id);
							// Remove all class display to help the user
							$('.day_element').removeClass('over_start');
							$('.day_element').removeClass('over_end');
							$('.day_element').removeClass('over');
							$('.day_element').attr('style', '');
							// Remove the block display to help the user
							$('.temp_drag_start').remove();
							// Display the old event
							$('.'+event_select_drag).css('display', 'block');
							// If the user select a date before the event start
							if(startSelectEventLimit > endSelectedEvent) {
								startSelect = null;
								endSelectedEvent = null;
								return null;
							}
							
							//TODO fire event
							var date = new Date();
							date.setTime(endSelectedEvent*1000);
							alert(startSelectEvent_real+' - '+(date.addMinutes(30).getTime()/1000 - 1));
							startSelect = null;
							endSelectedEvent = null;
						}
						// If the user has press the mouse to resize event
						if(typeSelect == 'sizeup') {
							typeSelect = null;
							resizeEvent = null;
							startSelectedEvent = parseInt(this.id);
							// Remove all class display to help the user
							$('.day_element').removeClass('over_start');
							$('.day_element').removeClass('over_end');
							$('.day_element').removeClass('over');
							$('.day_element').attr('style', '');
							// Remove the block display to help the user
							$('.temp_drag_end').remove();
							// Display the old event
							$('.'+event_select_drag).css('display', 'block');
							// If the user select a date before the event start
							if(startSelectedEvent > endSelectEventLimit) {
								endSelect = null;
								startSelectedEvent = null;
								return null;
							}
							
							//TODO fire event
							var date = new Date();
							date.setTime(startSelectedEvent*1000);
							endSelect = null;
							startSelectedEvent = null;
							alert((date.addMinutes(30).getTime()/1000 - 1)+' -- '+endSelectEvent_real);
							$(this).trigger("dateMove", {start: (date.addMinutes(30).getTime()/1000 - 1), end: endSelectEvent_real});
						}
					});

					/**
					 * When the user over a cell
					 */
					$('.day_element').mouseover(function() {
						// Retrieve the position of mouse
						var temp = parseInt(this.id);
						// If the user has already initialize the movement to create event
						if(typeSelect == 'create' && temp != endSelect) {
							
							// Remove all class to add after
							$('.day_element').removeClass('over');
							$('.day_element').removeClass('over_start');
							$('.day_element').removeClass('over_end');
							endSelect = temp;
							// If the start is to smaller than end
							if(startSelect < endSelect) {
								// Search index of element in the table day list
								start_index = jQuery.inArray(startSelect, listdays);
								end_value = endSelect;
								start_value = startSelect;
							}
							// Revert start and end
							else {
								jQuery.inArray(endSelect, listdays);
								end_value = startSelect;
								start_value = endSelect;
							}
							// Start selection
							var current = start_index;
							// Check all day has selected
							while(listdays[current] <= end_value) {
								var valueCurrent = listdays[current];
								// If the first element, add class: over_start
								if(valueCurrent == start_value)
									$('#'+start_value).addClass('over_start');
								// If the last element, add class: over_end
								if(valueCurrent == end_value)
									$('#'+end_value).addClass('over_end');
								// Add over class
								if(valueCurrent >= start_value) {
									$('#'+listdays[current]).addClass('over');
								}
								current ++;
							}
						}
						// If the user has already initialize the movement to resize event 
						if(typeSelect == 'sizedown' && temp != endSelectedEvent) {
							
							// Remove all class to add after
							$('.day_element').removeClass('over');
							$('.day_element').removeClass('over_start');
							$('.day_element').removeClass('over_end');
							// Remove temporary block
							$('.temp_drag_start').removeClass('event_end');
							$('.temp_drag_start').removeClass('display_none');
							// Remove all style of day cell
							$('.day_element').attr('style', '');
							// Recover the event color
							backgroundColor = $('.'+event_select_drag)[0].style.backgroundColor;
							endSelectedEvent = parseInt(this.id);
							// If the start is to smaller than end
							if(startSelectEventLimit <= endSelectedEvent) {
								start_index = jQuery.inArray(startSelect, listdays);
								end_value = endSelectedEvent;
								start_value = startSelect;
							}
							// Else don't display this element
							else {
								$('.temp_drag_start').addClass('display_none');
								return null;
							}
							// If the user select only one cell, display start and end in the same block
							if(startSelectEventLimit == endSelectedEvent)
								$('.temp_drag_start').addClass('event_end');
							// Check all day has selected
							var current = start_index;
							while(listdays[current] <= end_value) {
								var valueCurrent = listdays[current];
								if(valueCurrent == end_value)
									$('#'+end_value).addClass('over_end');
								if(valueCurrent >= start_value) {
									$('#'+listdays[current]).addClass('over');
								}
								$('#'+listdays[current])[0].style.backgroundColor = backgroundColor;
								current ++;
							}
						}
						// If the user has already initialize the movement to resize event 
						if(typeSelect == 'sizeup' && temp != startSelectedEvent) {
							// Remove all class to add after
							$('.day_element').removeClass('over');
							$('.day_element').removeClass('over_start');
							$('.day_element').removeClass('over_end');
							// Remove temporary block
							$('.temp_drag_end').removeClass('event_start');
							$('.temp_drag_end').removeClass('display_none');
							// Remove all style of day cell
							$('.day_element').attr('style', '');
							// Recover the event color
							backgroundColor = $('.'+event_select_drag)[0].style.backgroundColor;
							startSelectedEvent = parseInt(this.id);
							// If the start is to smaller than end
							if(startSelectedEvent <= endSelectEventLimit) {
								start_index = jQuery.inArray(startSelectedEvent, listdays);
								end_value = endSelectEventLimit;
								start_value = startSelectedEvent;
							}
							// Else don't display this element
							else {
								$('.temp_drag_end').addClass('display_none');
								return null;
							}
							// If the user select only one cell, display start and end in the same block
							if(endSelectEventLimit == startSelectedEvent)
								$('.temp_drag_end').addClass('event_start');
							// Check all day has selected
							var current = start_index;
							while(listdays[current] <= end_value) {
								var valueCurrent = listdays[current];
								if(valueCurrent == start_value)
									$('#'+start_value).addClass('over_start');
								if(valueCurrent >= start_value) {
									$('#'+listdays[current]).addClass('over');
								}
								$('#'+listdays[current])[0].style.backgroundColor = backgroundColor;
								current ++;
							}
						}
					});
				}

				/**
				 * Event object
				 */
				function Event() {
					this.id = '';
					this.start = 0;
					this.end = 0;
					this.title = '';
					this.location = '';
					this.categorie = '';
					this.status = '';
					this.decription = '';
					
					this.setStart = function(value) {
						this.start = new Date(value * 1000);
					}
					this.setEnd = function(value) {
						this.end = new Date(value * 1000);
					}
					
					// Used in the week display
					this.week = {
									display: {
										width: 0,
										position: -1,
										on: 0
									}
								}
				}

				/**
				 * Return a position to add event. 
				 */
				function givePosition(event) {
					if(indexStartEvent != -1)
						index = indexStartEvent;
					else
						index = 0;
					var indexToMove = new Array();
					var maxOn = 0;
					var forbiddenPosition = new Array();
					var end = false;
					for ( var i = 0; i < events.length && !end; i++) {
						var eventTemp = events[i];
						if(indexStartEvent == -1) {
							if(eventTemp.end >= event.start)
								indexStartEvent = i;
						}
						if(eventTemp.end >= event.start) {
							if(eventTemp.start <= event.end) {
								if(maxOn < eventTemp.week.display.on)
									maxOn = eventTemp.week.display.on;
								indexToMove.push(i);
								if(jQuery.inArray(eventTemp.week.display.position, forbiddenPosition) == -1)
									forbiddenPosition.push(eventTemp.week.display.position);
							}
							else
								end = true;
						}
					}
					if(maxOn == 0) {
						maxOn = 1;
						position = 0;
					}
					else {
						forbiddenPosition.sort();
						position = 0;
						for ( var i in forbiddenPosition) {
							if(forbiddenPosition[i] != position)
								break
							position ++;
						}
						if(position >= maxOn) {
							maxOn ++;
							//Move all
							for ( var i in indexToMove) {
								moveEventPosition(events[indexToMove[i]], maxOn);
							}
						}
					}
					
					return {position: position, on: maxOn};
				}
				
				function moveEventPosition(event, on) {
					width = columnWidth *0.85 * 2 / (on + 1);
					left = (((event.week.display.position)/(on + 1)) - ((event.week.display.position)/(event.week.display.on + 1))) * (columnWidth * 0.85);
					
					$('.'+event.id).each(function(index) {
						old = $(this)[0].style.left.substring(0, $(this)[0].style.left.length -2);
						$(this)[0].style.left = (parseFloat(old) + left)+'px';
					})
					$('.'+event.id).css('width', width);
					event.week.display.on = on;
				}
				
				function compareEvent(a, b) {
					if(a.start < b.start)
						return -1;
					if(a.start > b.start)
						return 1;
					return 0;
				}
				
				var addEvent = function (options) {
					// if option is a Event object
					if(options instanceof Event) {
						event = options;
						var startTimestamp = event.start;
						var endTimestamp = event.end;
					}
					else {
						var event = new Event();
						if(options.title)
							event.title = options.title;
						
						var startTimestamp = new Date(options.start * 1000);
						var endTimestamp = new Date(options.end * 1000);
	
						event.start = startTimestamp;
						event.end = endTimestamp;
					}
					
					// Switch start and end if start > end
					if(startTimestamp.getTime() > endTimestamp.getTime()) {
						tempTimestamp = startTimestamp;
						startTimestamp = endTimestamp;
						endTimestamp = tempTimestamp;
					}
					
					// Check if start is in the display date of calendar
					if((startTimestamp.getTime() / 1000) > endDate)
						return null;
					else {
						var id = startTimestamp.getTime()+''+(endTimestamp.getTime())+Math.round(Math.random() * 100000);
						event.id = id;
						// Number of pixel for one minute
						 
						// If the start is out the calendar, initialize to first day
						if((startTimestamp.getTime() / 1000) < startDate) {
							startWeekDay = 0;
							startTimestamp = new Date(startDate * 1000);
						}
						else {
							// Else retrieve the start day in week
							var startPosition = new Object();
							var startWeekDay = startTimestamp.getDay();
							if(startWeekDay == 0)
								startWeekDay = 6;
							else
								startWeekDay --;
							var classes = 'event_start';
						}

						// Test ici de la position !!!--------------------------------------------------------------------
						var position = givePosition(event);
						
						// Initialize the position of the event start
						var startPosition = new Object();
						startPosition.left = startWeekDay * columnWidth + (columnWidthFirst) + ((position.position)/(position.on + 1)) * (columnWidth * 0.8);
						startPosition.top = (startTimestamp.getHours() * 60 + startTimestamp.getMinutes()) * oneMinute + 24;
						
						// Check if the start and end are in the different week. If yes, the end is out of calendar
						differentWeek = (startTimestamp.getWeekOfYear() != endTimestamp.getWeekOfYear());
						
						// Retrieve day in week for the end
						var endWeekDay = endTimestamp.getDay();
						if(endWeekDay == 0)
							endWeekDay = 6;
						else
							endWeekDay --;
						
						// Display the event
						var endPosition = new Object();
						
						endPosition.left = columnWidth *0.8 * 2 / (position.on + 1);
						// If the start and end are in the same day
						
						if(startWeekDay == endWeekDay && !differentWeek) {
							endPosition.top = ((endTimestamp.getHours() * 60 + endTimestamp.getMinutes()) * oneMinute + 24) - startPosition.top;
							this.append('<div class="'+id+' '+classes+' drag event_end" style="position: absolute; top: '+startPosition.top+'px; left: '+startPosition.left+'px; width: '+endPosition.left+'; height: '+endPosition.top+'; background-color: '+options.color+'; z-index: '+(position.position +10)+'">'
											+'<div id="drag_top_'+id+'" class="drag_top event_start" style="cursor: n-resize"></div>'				
											+event.title
											+'<div id="drag_bottom_'+id+'" class="drag_bottom event_end" style="cursor: n-resize;"></div>'
											+'</div>');
							
						}
						else {
							endPosition.top = ((23 * 60 + 59) * oneMinute + 24) - startPosition.top;
							
							// Make the first element
							this.append('<div class="'+id+' '+classes+'" style="position: absolute; top: '+startPosition.top+'px; left: '+startPosition.left+'px; width: '+endPosition.left+'; height: '+endPosition.top+'; background-color: '+options.color+'; z-index: '+(position.position +10)+'">'
											+'<div id="drag_top_'+id+'" class="drag_top event_start" style="cursor: n-resize"></div>'
											+event.title
										+'</div>');

							endPosition.top = ((23 * 60 + 59) * oneMinute);
							
							// Make others element
							classes = '';
							dragBottom = '';
							currentWeekDay = startWeekDay + 1;
							var tempPosition = new Object();
							while(((currentWeekDay <= endWeekDay && !differentWeek) || (currentWeekDay <= 6 && differentWeek)) && currentWeekDay <= 6) {
								tempPosition.left = currentWeekDay * columnWidth + (columnWidthFirst) + (position.position/position.on) * (columnWidth * 1/2);
								tempPosition.top = 24;
								
								if(currentWeekDay == endWeekDay) {
									endPosition.top = ((endTimestamp.getHours() * 60 + endTimestamp.getMinutes()) * oneMinute + 24) - tempPosition.top;
									classes = 'event_end';
									dragBottom = '<div id="drag_bottom_'+id+'" class="drag_bottom event_end" style="cursor: n-resize"></div>';
								}
								this.append('<div class="'+id+' '+classes+'" style="position: absolute; top: '+tempPosition.top+'px; left: '+tempPosition.left+'px; width: '+endPosition.left+'; height: '+endPosition.top+'; background-color: '+options.color+'; z-index: '+(position.position +10)+'">'
												+event.title
												+dragBottom
											+'</div>');

								currentWeekDay ++;
							}
						}
						var divTest = this;

						$('#drag_top_'+id).mousedown(function() {
							typeSelect = 'sizeup';
							var end_temp = endTimestamp.getTime() / 1000;
							var current = 0;
							while(listdays[current] <= end_temp) {
								current ++;
							}
							current--;
							// Event limit, use to mouse up
							endSelect = listdays[current];
							endSelectEventLimit = listdays[current - 1];
							endSelectEvent_real = end_temp;
							event_select_drag = id;
							var lastHeight = listdays[current - 1];
							current ++;//= current + 2;
							var heightTimestamp = new Date(lastHeight * 1000);
							if(heightTimestamp.getDay() != startTimestamp.getDay())
								var heightPosition = ((heightTimestamp.getHours()+24) * 60 + heightTimestamp.getMinutes()) * oneMinute + 24;
							else
								var heightPosition = (heightTimestamp.getHours() * 60 + heightTimestamp.getMinutes()) * oneMinute + 24;
							var height = (startPosition.top + endPosition.top) - heightPosition;
								$('#test2').html(heightPosition+' + '+(startPosition.top + endPosition.top)
												+'<br />resultat: '+height
												+'<br /> start pos limit'+endSelectEventLimit
												+'<br /> start pos'+endSelect
												+'<br /> start pos real'+endSelectEvent_real);
							$('.'+event_select_drag).css('display', 'none');
							divTest.append('<div class="temp_drag_end event_end" style="position: absolute; z-index: 1; top: '+(heightPosition)+'px; left: '+(startWeekDay * columnWidth + (columnWidthFirst))+'px; width: '+columnWidth+'px; height: '+height+'; background-color: '+options.color+';"></div>');
							$('.temp_drag_end').mouseup(function () {
								$('#'+endSelectEventLimit).mouseup();
							})
							$('.temp_drag_end').mouseover(function () {
								$('#'+endSelectEventLimit).mouseover();
								$(this).addClass('event_end');
							})
							return false;
						});
						$('#drag_bottom_'+id).mousedown(function() {
							typeSelect = 'sizedown';
							var start_temp = startTimestamp.getTime() / 1000;
							var current = 0;
							while(listdays[current] <= start_temp) {
								current ++;
							}
							startSelectEventLimit = listdays[current - 1];
							startSelect = listdays[current];
							startSelectEvent_real = start_temp;
							event_select_drag = id;
							var lastHeight = listdays[current];
							current ++;
							var heightTimestamp = new Date(lastHeight * 1000);
							if(heightTimestamp.getDay() != startTimestamp.getDay())
								var heightPosition = ((heightTimestamp.getHours()+24) * 60 + heightTimestamp.getMinutes()) * oneMinute + 24;
							else
								var heightPosition = (heightTimestamp.getHours() * 60 + heightTimestamp.getMinutes()) * oneMinute + 24;
							var height = heightPosition - startPosition.top;
								$('#test2').html(heightPosition+' + '+startPosition.top
												+'<br />resultat: '+height
												+'<br /> start pos limit'+startSelectEventLimit
												+'<br /> start pos'+startSelect
												+'<br /> start pos real'+startSelectEvent_real);
							$('.'+event_select_drag).css('display', 'none');
							divTest.append('<div class="temp_drag_start event_start" style="position: absolute; z-index: 1; top: '+startPosition.top+'px; left: '+(startWeekDay * columnWidth + (columnWidthFirst))+'px; width: '+columnWidth+'px; height: '+height+'; background-color: '+options.color+';"></div>');
							$('.temp_drag_start').mouseup(function () {
								$('#'+startSelectEventLimit).mouseup();
							})
							$('.temp_drag_start').mouseover(function () {
								$('#'+startSelectEventLimit).mouseover();
								$(this).addClass('event_end');
							})
							return false;
						});
					}
					event.week.display.position = position.position;
					event.week.display.on = position.on;
					event.color = options.color;
					events.push(event);
					events.sort(compareEvent);
					return event;
				}
				
				var removeEvent = function removeEvent(event) {
					if(!event)
						return false;
					index = -1;
					for(x in events) {
						if(events[x].id == event.id) {
							index = x;
							break;
						}
					}
					if(index == -1)
						return false;
					// Remove from display
					$('.'+events[index].id).remove();
					// Remove element
					events.splice(index,1);
					return true;
				}
				
				var refreshEvent = function refreshEvent(event) {
					this.calendar('removeEvent', event);
					this.calendar('addEvent', event);
				}
				
				function moveEvent(event) {
					// Prendre l evenement ou l id
					if(!isObject(event)) {
						// Recup l object
					}
					eventTemp = event;
					
					// Tester s il est en concurrence avec d autre si oui s il a le meme on
					var sameDate = new Array();
					for(x in events) {
						if(events[x].id != eventTemp.id) {
							if(events[x].start < eventTemp.end && events[x].end > eventTemp.start)
								sameDate.push(events[x]);
						}
					}
					
					if(sameDate.lenght() > 0)
						alert('Il y a '+sameDate.lenght+' element a la meme place');
					else
						alert('La vie est belle');
					
					//Supprimer l objet dans la liste.
					
					//Faire un givePosition
					
					//Supprimer l'objet physiquement
					
					//Afficher l'objet
				}
				
				var methods = {
				    draw : draw,
				    selectEvent : selectEvent,
				    hide : function( ) {  },
				    addEvent : addEvent,
				    removeEvent : removeEvent,
				    refreshEvent : refreshEvent
				};
				
				
			  $.fn.calendar = function(method) {

					if ( methods[method] ) {
				      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
				    } else if ( typeof method === 'object' || ! method ) {
				      return methods.draw.apply( this, arguments );
				    } else {
				      $.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
				    }

					return this;
			  };
			})( jQuery );
			
			$(document).ready(function () {
				$('#test').calendar();
				
				$('#test').calendar('addEvent', {start: 1319504400, end: 1319511599, title: 'test ie 2h', color: '#CCFF99', colorTitle: 'red'});
				$('#test').calendar('addEvent', {start: 1319493600, end: 1319500799, title: 'test ie 2h', color: '#99FF99', colorTitle: 'red'});
				$('#test').calendar('addEvent', {start: 1319589002, end: 1319619599, title: 'test', color: '#FF9999', colorTitle: 'red'});
				$('#test').calendar('addEvent', {start: 1319751000, end: 1319752799, title: 'test', color: '#FFCC99', colorTitle: 'red'});
				$('#test').calendar('addEvent', {start: 1319425200, end: 1319434199, title: 'test1', color: '#FF3399', colorTitle: 'red'});
				$('#test').calendar('addEvent', {start: 1319515200, end: 1319522399, title: 'test3', color: '#FF3333', colorTitle: 'red'});
				$('#test').calendar('addEvent', {start: 1319946800, end: 1319956199, title: 'test2', color: '#FFCCCC', colorTitle: 'red'});
				$('#test').calendar('addEvent', {start: 1319797800, end: 1319801399, title: 'test2', color: '#FFCCCC', colorTitle: 'red'});
			});
			
			
		</script>
	
	<div id="test" style="float: left; position: relative;"></div>