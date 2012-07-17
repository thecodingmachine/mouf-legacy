
/**
 * A flexible datagrid that refreshes with Ajax and can export to CSV.
 * 
 * options: 
 * {
 *  filterForm: "selector", // A jQuery selector pointing to the form containing the filters (if any) 
 * 	filterCallback: function, // A function taking 0 arguments and returning a map of filters (passed as arguments to the Ajax URL). This is applied before the filterForm
 *  url: url, // The Ajax URL
 *  tableClasses : "table", // The CSS class of the table
 *  limit  : 100, // The maximum number of rows to be returned in one page
 *  pagerId : 'listePager' // The ID of the pager,
 *  columns: [...] // A list of columns,
 *  export_csv: true // Whether we can export to CSV or not,
 *  loadOnInit: true // Whether we should start loading the table (true) or wait for the user to submit the search form (false)
 * }
 * 
 * Any parameter (except URL) can be dynamically passed from the server side.
 */
(function ($){
	var defaultOptions = {
			"loadOnInit": true,
			"export_csv": true,
			"limit": 100
	}
	
	/**
	 * Returns the list of filters to be applied to the query.
	 * Some filters can be passed directly to the function. In that case only those filters are taken into account
	 */
	var _getFilters = function(descriptor, filters) {
		if (filters) {
			return filters;
		}
		
   		if(descriptor.filterCallback) {
    		return descriptor.filterCallback();
    	}
   		
   		if (descriptor.filterForm) {
   			return $(descriptor.filterForm).serializeArray();
    	}
	
		return {};
	};
	
	var methods = {
	    init : function( options ) {
	    	var descriptor = $.extend(true, {}, defaultOptions, options);
	    	
	    	return this.each(function(){
                $(this).data('descriptor', descriptor);
                
                var $this = $(this);
                if (descriptor.filterForm) {
                	$(descriptor.filterForm).submit(function() {
                		try {
                			$this.evolugrid('refresh', 0);
                		} catch (e) {
                			console.error(e);
                		}
                		return false;
                	});
            	}
                if (descriptor.loadOnInit) {
                	$(this).evolugrid('refresh',0);
                }
	        });
	    },
	    csvExport : function(filters) {
	    	var descriptor=$(this).data('descriptor');
	    	
	    	var filters = _getFilters(descriptor, filters);
	    	
	    	var url = descriptor.url;
	    	if (url.indexOf("?") == -1) {
	    		url += "?";
	    	} else {
	    		url += "&";
	    	}
	    	for (var i=0; i<filters.length; i++) {
	    		if (filters[i]['value']) {
	    			url += filters[i]['name']+"="+encodeURIComponent(filters[i]['value'])+"&";
	    		}
	    	}
	    	url += "output=csv";
	    	
	    	window.open(url);
	    },
	    refresh : function( noPage, filters ) {
	    	var descriptor=$(this).data('descriptor');
	    	
	    	
	    	$this=$(this);
	    	filters = _getFilters(descriptor, filters);
	    	filters.push({"name":"offset", "value": noPage*descriptor.limit});
	    	filters.push({"name":"limit", "value": descriptor.limit});

	    	$.ajax({url:descriptor.url, dataType:'json', data : filters,
	    	success: function(data){
	    		
		    	var extendedDescriptor=$.extend(true, {}, descriptor, data.descriptor)

	    		//Display Count
	    		if(!extendedDescriptor.countTarget){
	    			countTarget = "#count";
	    		} else {
	    			countTarget=extendedDescriptor.countTarget
	    		}
	    		$(countTarget).html(data.count);
	    		//construct th
	    		$this.html("");
	    		var table=$('<table>').appendTo($this);
	    		var tr=$('<tr>');
	    		table.append(tr);
	    		table.addClass(extendedDescriptor.tableClasses)
	    		for(var i=0;i<extendedDescriptor.columns.length;i++){
	    			tr.append($('<th>').html(extendedDescriptor.columns[i].title))
	    		}
	    		//construct td
	    		for (var i=0;i<data.data.length;i++){
	    			tr=$('<tr>');
	    			table.append(tr);
	    			for(var j=0;j<extendedDescriptor.columns.length;j++){
	    				var td=$('<td>');
	    				// jsdisplay is used when the data comes in JSON from the server (and you want js display)
	    				// if jsdipslay is used, display is ignored.
	    				var jsdisplay=extendedDescriptor.columns[j].jsdisplay;
	    				if (jsdisplay) {
	    					// Let's eval the function (its evil) and let's execute it.
	    					var myfunc = (new Function("return " + jsdisplay))();
	    					var html=myfunc(data.data[i]);
	    				} else {
		    				var display=extendedDescriptor.columns[j].display;
		    				if (display) {
			    				if(typeof display == 'function'){
			    					var html=display(data.data[i]);
			    				}else {
			    					var html=data.data[i][display];
			    				}
		    				}
	    				}
	    				if(html){
	    					td.html(html);
		    			}
	    				tr.append(td);
		    		}   			
	    		}
	    		//construct pager
	    		pager=$('<div>').addClass("pager");
	    		if(extendedDescriptor.pagerId){
	    			pager.attr('id',extendedDescriptor.pagerId);
	    		}
	    		
	    		if (extendedDescriptor.export_csv) {
	    			pager.append($('<i>').addClass('icon-file pointer export-csv').text("Export to CSV").click(function(){$this.evolugrid('csvExport');}));
	    		}
	    		
	    		if(noPage>0){
	    			pager.append($('<i>').addClass('icon-chevron-left pointer pager-cursor').text("<").click(function(){$this.evolugrid('refresh',noPage-1);}));
	    		}
	    		var pagerText = "Page "+(noPage+1);
	    		var pageCount;
	    		if (data.count != null) {
	    			pageCount=Math.floor(data.count/extendedDescriptor.limit);
	    			pagerText += " de "+(pageCount+1);
	    		}
	    		pager.append($('<span>').text(pagerText));
	    		
	    		if((data.count != null && noPage<pageCount) || (data.count == null && extendedDescriptor.limit && data.data.length == extendedDescriptor.limit)){
	    			pager.append($('<i>').addClass('icon-chevron-right pointer pager-cursor').text(">").click(function(){$this.evolugrid('refresh',noPage+1);}));
	    		}
	    		$this.append(pager);
	    		
	    	},
	    	error : function(err,status) { 
	    		console.error("Error on ajax callback: "+status);
	    		alert("An error occurred while displaying table.");
	    	}
	    	
	    	})
	    }
	  };

	  $.fn.evolugrid = function( method ) {
	    
	    // Method calling logic
	    if ( methods[method] ) {
	      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
	    } else if ( typeof method === 'object' || ! method ) {
	      return methods.init.apply( this, arguments );
	    } else {
	      $.error( 'Method ' +  method + ' does not exist on jQuery.evolugrid' );
	    }    
	  
	  };
	

	
	
})(jQuery);