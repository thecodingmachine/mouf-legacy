<?php /* @var $this DarkTemplate */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html
	xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if ($this->favIconUrl) { ?>
<link rel="icon" type="image/png"
	href="<?php echo ROOT_URL.$this->favIconUrl; ?>" />
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
	<div id="page">
	<?php if ($this->logoImg): ?>
			<div id="header">
				<div id="logo">
					<a href="<?php echo ROOT_URL ?>">
						<img src="<?php echo ROOT_URL.$this->logoImg ?>" alt="" />
					</a>
				</div>
			</div>
			<?php endif; ?>
			<?php if ($this->header != null) { ?>
				<?php $this->drawArray($this->header);?>
			<?php } ?>
	</div>

	<div class="container">
		<div class="row">
			<?php if ($this->left != null) { ?>
			<div id="sidebar-left"
				class="sidebar span<?php echo $this->leftColumnSize ?>">
			<?php $this->drawArray($this->content);?>
			</div>
			<?php } ?>

			<div id="content" class="span<?php echo $this->contentSize; ?>">
				<?php 
				if ($this->content != null) {
					$this->drawArray($this->content);
				}
				?>
			</div>

			<?php if ($this->right != null) { ?>
			<div id="sidebar-right"
				class="sidebar span<?php echo $this->rightColumnSize ?>">
				<?php $this->drawArray($this->right);?>
			</div>
			<?php } ?>
		</div>
		<?php if ($this->footer != null) { ?>
			<div class="row">
			<div class="span12" id="footer">
				<?php $this->drawArray($this->footer);?>
			</div>
			</div>
		<?php } ?>		
	</div>
	<div style="height: 10px;"></div>
</body>
</html>
