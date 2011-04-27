<?php

function FourOFour($message) {
		?>
	<div>
	<h1 class="admindeo">An Error has been detected</h1>
	</div>
	<div class="vertical-gap"></div>
	<div class="vertical-gap"></div>
	
		<div style="padding:20px">The page you requested does not exist.</div>
		<div class="vertical-gap"></div>
		<div class="vertical-gap"></div>
	<div class="stats-key">
		<div class="vertical-gap"></div>
			<?php
			echo $message;
			?>
		<div style="clear: both;"></div>
	<div class="vertical-gap"></div>
	
		</div>
	<div class="vertical-gap"></div>
	<div class="vertical-gap"></div>
	<div class="vertical-gap"></div>
	<div class="vertical-gap"></div>
	<?php
}
?>