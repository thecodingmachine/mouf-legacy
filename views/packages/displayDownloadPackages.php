<?php /* @var $this PackageDownloadController */ ?>
<h1>Packages List</h1>

<?php 
foreach ($this->repositoryUrls as $key=>$arr) {
	$name = $arr['name'];
	$url = $arr['url'];
?>
	<h2>Repository '<?php echo plainstring_to_htmlprotected($name) ?>':</h2>
	<div id="repository<?php echo $key ?>"><div class="loading">Loading packages list</div></div>
	<script type="text/javascript">
	jQuery(document).ready(function() {

		var url = "<?php echo ROOT_URL ?>mouf/packagetransfer/proxylist?url=<?php echo plainstring_to_urlprotected($url)?>&selfedit=<?php echo $this->selfedit ?>";

		jQuery.ajax({url:url, 
			success: function(html) {
				jQuery("#repository<?php echo $key ?>").html(html);
			},
			error: function() {
				alert("error");
			}
							
		});

	});
	</script>
<?php 
}?>

<br/>