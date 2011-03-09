<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php print $this->title ?></title>
		<?php print $this->getCssFiles() ?>
		<?php $this->drawArray($this->head); ?>
		<?php print $this->getJsFiles() ?>

	</head>
	<?php
		$class = array();
		if(count($this->left) != 0)
			$class[] = "sidebar-left";
		if(count($this->right) != 0)
			$class[] = "sidebar-right";
	?>
	<body>
		<div id="container">
			<?php if (count($this->header) != 0) { ?>
				<div id="header"><?php $this->drawArray($this->header);?></div>
			<?php } ?>
			<div id="page">
				<?php if (count($this->left) != 0) { ?>
					<div id="sidebar-left">
						<div class="sidebar-top"></div>
						<div class="sidebar-middle">
							<?php $this->drawArray($this->left);?>
						</div>
						<div class="sidebar-bottom"></div>
					</div>
				<?php }
				if (count($this->right) != 0) { ?>
					<div id="sidebar-right">
						<div class="sidebar-top"></div>
						<div class="sidebar-middle">
							<?php $this->drawArray($this->right);?>
						</div>
						<div class="sidebar-bottom"></div>
					</div>
				<?php } 
				if (count($this->content) != 0) { ?>
					<div id="main" class="<?php echo implode(" ", $class)?>"><?php $this->drawArray($this->content); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>
			<?php if (count($this->footer) != 0) { ?>
				<div id="footer"><?php $this->drawArray($this->footer);?></div>
			<?php } ?>
		</div>
		<!-- &copy; Copyright 2011 (TM) -->
	</body>
</html>