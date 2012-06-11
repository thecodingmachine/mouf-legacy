<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">


	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?php if ($this->favIconUrl) { ?>
		<link rel="icon" type="image/png" href="<?php echo ROOT_URL.$this->favIconUrl; ?>" />
		<?php } ?>
		<title><?php print $this->title ?></title>
		<?php print $this->getCssFiles() ?>
		<?php $this->drawArray($this->head); ?>
		<script type="text/javascript">
		<!--
			var settings = {
				baseUrl : "<?php echo ROOT_URL; ?>"
			}
		//-->
		</script>
		<?php print $this->getJsFiles() ?>
		
	</head>

<!-- default margin = default layout -->
<body>

<div class="container">

	<div id="header" class="header">
				<div id="logo">
					<a href="<?php echo ROOT_URL ?>">
						<span><?php print $this->title ?></span>
					</a>
				</div>
	</div> 

	<div class="stripes"><span></span></div>
	
			
				
	
	<?php if (count($this->header) != 0) { ?>
	<div class="nav">
		<?php $this->drawArray($this->header); ?>
		<div class="clearer"><span></span></div>
	</div>
		<?php } ?>
	<div class="stripes"><span></span></div>

	<div class="main">
	
	<?php if (count($this->left) != 0) { ?>
				<div id="sidebar-left" class="left">
					<?php $this->drawArray($this->left);?>
				</div>
			<?php } ?>
	
	

			
		<?php /*if (count($this->right) != 0) { ?>
				<div id="sidebar-right" class="right">
					<?php $this->drawArray($this->right);?>
				</div>
			<?php }*/ ?>
     <div id="content" class="right">

				<?php $this->drawArray($this->content); ?>
			</div>

		<div class="clearer"><span></span></div>

	</div>
	
	
	<?php if (count($this->footer) != 0) { ?>
				<div class="footer" id="footer" >
						<?php $this->drawArray($this->right);?>
				</div>
			<?php } ?>

						
			<div class="bottom">
				
				

				<div class="clearer"><span></span></div>

			</div>

	</div>

</div>

</body>

</html>

