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
			.drag_bottom {
				height: 10px;
				width: 100%;
				position: absolute;
				bottom: 0px;
				left: 0;
			}
			.drag_top {
				height: 15px;
				width: 100%;
				position: absolute;
				top: 0px;
				left: 0;
				z-index: -1;
			}
			.drag{
				border: 1px dashed gray;
			}
			.display_none {
				display: none
			}
			.event_start.drag.event_end.main{
				overflow: hidden;
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
					startDate = new Date(<?php echo date('Y', $dateFrom); ?>, <?php echo date('n', $dateFrom) -1 ; ?>, <?php echo date('j', $dateFrom); ?>, 0, 0, 0, 0).getTime()/1000;
					for (i = 0; i <= 6; i ++) {
						startDateH = new Date(<?php echo date('Y', $dateFrom); ?>, <?php echo date('n', $dateFrom) -1 ; ?>, <?php echo date('j', $dateFrom); ?> + i, 0, 0, 0, 0);
						$('<td class="days_name">')
							.text(dateLanguage['fr'].day[i] + " " + startDateH.getDate() + "/" + (startDateH.getMonth()+1))
							.appendTo(tr);
					}
					// Display 24 hours for each day
					for (i = 0; i <= 23; i ++) {
						if (i<=7) hide = ' style=\'display: none\'}';
						else hide = '';
						// 0 is 0 minute and 1 is 30 minutes
						for(j = 0; j <= 1; j ++) {
							table.append(tr);
							if(j == 0)
								tr = $('<tr class="hour"'+hide+'>');
							else
								tr = $('<tr class="hour_half"'+hide+'>');
							$('<td style="width: 45px; height: 20px">')
								.text(i+'h'+(j*30))
								.appendTo(tr);
							// 7 days of week
							for (d = 0; d <= 6; d ++) {
								var timestamp = new Date(<?php echo date('Y', $dateFrom); ?>, <?php echo date('n', $dateFrom) - 1; ?>, <?php echo date('j', $dateFrom); ?> + d, i, j*30, 0, 0).getTime()/1000;
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
					if ('undefined' !== typeof $('.calendar').onselectstart) {
						alert('oui');
						$('.calendar').onselectstart = function () { return false; };
					} 
					//TODO Recuperer la largeur et la hauteur de la premiere colonne et de la premiere ligne.
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
					width = columnWidth *0.85 * 2 / (on + 1) - 10;
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
						
						options.start = options.start-8*3600;
						options.end = options.end-8*3600;

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
						startPosition.left = startWeekDay * columnWidth + (columnWidthFirst) + ((position.position)/(position.on + 1)) * (columnWidth * 0.8) + 2;
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
						
						endPosition.left = columnWidth *0.95 * 2 / (position.on + 1);
						// If the start and end are in the same day
						
						if(startWeekDay == endWeekDay && !differentWeek) {
							endPosition.top = ((endTimestamp.getHours() * 60 + endTimestamp.getMinutes()) * oneMinute + 24) - startPosition.top;
							this.append('<div class="main '+id+' '+classes+' drag event_end" style="position: absolute; top: '+startPosition.top+'px; left: '+startPosition.left+'px; width: '+endPosition.left+'; height: '+endPosition.top+'; background-color: '+options.color+'; z-index: '+(position.position +10)+'">'
											+'<div id="drag_top_'+id+'" class="drag_top event_start"></div>'			
											+event.title
											+'<div id="drag_bottom_'+id+'" class="drag_bottom event_end"></div>'
											+'</div>');
							this.append('<div class="hidden '+id+' '+classes+' drag event_end" style="position: absolute; top: '+startPosition.top+'px; left: '+startPosition.left+'px; width: '+endPosition.left+'; height: auto; background-color: white; z-index: '+(position.position +100)+'; display: none">'
									+'<div id="hidden_drag_top_'+id+'" class="drag_top event_start"></div><b>'			
									+(startTimestamp.getHours()+8)+'h'+(startTimestamp.getMinutes() < 10 ? '0' : '')+startTimestamp.getMinutes()+' - '
									+(endTimestamp.getHours()+8)+'h'+(endTimestamp.getMinutes() < 10 ? '0' : '')+endTimestamp.getMinutes()+'</b><br/>'
									+event.title
									+'<div id="hidden_drag_bottom_'+id+'" class="drag_bottom event_end"></div>'
									+'</div>');
							
						}
						else {
							endPosition.top = ((23 * 60 + 59) * oneMinute + 24) - startPosition.top;
							
							// Make the first element
							this.append('<div class="'+id+' '+classes+'" style="position: absolute; top: '+startPosition.top+'px; left: '+startPosition.left+'px; width: '+endPosition.left+'; height: '+endPosition.top+'; background-color: '+options.color+'; z-index: '+(position.position +10)+'">'
											+'<div id="drag_top_'+id+'" class="drag_top event_start"></div>'
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
									dragBottom = '<div id="drag_bottom_'+id+'" class="drag_bottom event_end"></div>';
								}
								this.append('<div class="'+id+' '+classes+'" style="position: absolute; top: '+tempPosition.top+'px; left: '+tempPosition.left+'px; width: '+endPosition.left+'; height: '+endPosition.top+'; background-color: '+options.color+'; z-index: '+(position.position +10)+'">'
												+event.title
												+dragBottom
											+'</div>');

								currentWeekDay ++;
							}
						}
						var divTest = this;
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
				<?php 
				$i = 0;
				foreach ($calendar as $calendarDate) {
					/* @var $calendarDate SF_Calendar */
				?>
					$('#test').calendar('addEvent', {start: <?php echo $calendarDate->beginDate ?>, end: <?php echo $calendarDate->endDate ?>, title: '<?php echo addslashes( str_replace(array("\r\n", "\r", "\n"), ' ', utf8_decode($calendarDate->summary)) . "<br/>" . utf8_decode($calendarDate->room->adr2) .  "<br/><b>" . implode(", ", $calendarDate->clientNames)."</b>"); ?>', color: '<?php echo $calendarDate->room->color; ?>', colorTitle: '#CCFF99'});
				<?php
					$i ++;
				}
				foreach ($constraints as $constraintDate) {
					/* @var $calendarDate SF_Constraint */
					?>
					$('#test').calendar('addEvent', {start: <?php echo $constraintDate->beginDate ?>, end: <?php echo $constraintDate->endDate ?>, title: '<?php echo addslashes( str_replace(array("\r\n", "\r", "\n"), ' ', utf8_decode($constraintDate->comment))); ?>', color: '<?php echo $constraintDate->color; ?>', colorTitle: '#CCFF99'});
				<?php
					$i ++;
				}
				?>

				var lastElement = null;
				
				$('.event_start.drag.event_end.main').mouseenter(
					 function () {
						if (lastElement != null) lastElement.hide();
					    lastElement = $(this).next('.hidden')
					    lastElement.show();
					 }
				);

				$('.event_start.drag.event_end.hidden').mouseleave(
						 function () {
						    $(this).hide();
						  }
				);
			});
			
			
		</script>
	
	<div id="test" style="float: left; position: relative; width: 1104px;"></div>