<?php /* @var $this DarkTemplate */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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

<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse"
				data-target=".nav-collapse"> <span class="icon-bar"></span> <span
				class="icon-bar"></span> <span class="icon-bar"></span> </a> <a
				class="brand" href="<?php echo ROOT_URL ?>"><?php print $this->title ?></a>
				
				<?php if ($this->menuRight) { ?>
				<div class="btn-group pull-right">
					<?php $this->menuRight->toHtml(); ?>
				</div>
				<?php } ?>
			<!-- <div class="btn-group pull-right">
				<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <i
					class="icon-user"></i> Username <span class="caret"></span> </a>
				<ul class="dropdown-menu">
					<li><a href="#">Profile</a></li>
					<li class="divider"></li>
					<li><a href="#">Sign Out</a></li>
				</ul>
			</div>
			<div class="nav-collapse">
				<ul class="nav">
					<li class="active"><a href="#">Home</a></li>
					<li><a href="#about">About</a></li>
					<li><a href="#contact">Contact</a></li>
				</ul>
			</div> -->
			<!--/.nav-collapse -->
		</div>
	</div>
</div>

<div class="container-fluid content">
<div class="row-fluid">
	<?php if (count($this->header) != 0) { ?>
	<div  class="header span12">
	<div class="nav">
		<?php $this->drawArray($this->header); ?>
		<div class="clearer"><span></span></div>
	</div>
	</div> 
	<?php } ?>

	<div class="span12">
	<div class="row-fluid">
	<?php if (count($this->left) != 0) { ?>
				<div id="sidebar-left" class="span2">
					<?php $this->drawArray($this->left);?>
				</div>
			<?php } ?>
	
	

			
		<?php /*if (count($this->right) != 0) { ?>
				<div id="sidebar-right" class="right">
					<?php $this->drawArray($this->right);?>
				</div>
			<?php }*/ ?>
     <div class="span10 well">

				<?php $this->drawArray($this->content); ?>
			</div>
</div>

	</div>
	
	
	<?php if (count($this->footer) != 0) { ?>
				<div class="span12" id="footer" >
						<?php $this->drawArray($this->right);?>
				</div>
			<?php } ?>

						
			<div class="bottom span12">
				
				

				<div class="clearer"><span></span></div>

			</div>

	</div>

</div>
</body>
</html>