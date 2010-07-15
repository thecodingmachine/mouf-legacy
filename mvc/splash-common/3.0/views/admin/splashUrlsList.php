<h1>Splash Apache Configuration</h1>

<p>This page let's you see all the URLs that are managed by Splash.</p>

<table>
	<tr>
		<th>URL</th>
		<th>Controller</th>
		<th>Action</th>
	</tr>
<?php foreach ($this->splashUrlsList as $splashUrl) { 
	/* @var $splashUrl SplashCallback */
	?>
	<tr>
		<td><?php echo $splashUrl->url ?></td>
		<td><?php echo $splashUrl->controllerInstanceName ?></td>
		<td><?php echo $splashUrl->methodName ?></td>
	</tr>
<?php } ?>
</table>