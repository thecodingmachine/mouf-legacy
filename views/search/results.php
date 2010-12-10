<?php /* @var $this SearchController */ ?>
<h1>Results for <em><?php echo plainstring_to_htmlprotected($this->query) ?></em></h1>

<div id="results"></div>
<script type="text/javascript">
jQuery(function() {
	var services = <?php echo json_encode($this->searchUrls); ?>;
	for (var i=0; i<services.length; i++) {
		var name = services[i].name;
		var url = services[i].url;

		var result = jQuery("#results").append("<div id='searchdiv"+i+"'><div class='loading'>Searching "+name+"</div></div>");
		result.load(url,{
			query: "<?php echo plainstring_to_htmlprotected($this->query) ?>",
			selfedit: "<?php echo plainstring_to_htmlprotected($this->selfedit) ?>"
		}, function(response, status, xhr) {
			if (status == "error") {
				var msg = "An error occured while fetching search results for "+name+"<br/>";
				result.html(msg + xhr.status + " " + xhr.statusText);
			}
		}
		)
	}
});
</script>