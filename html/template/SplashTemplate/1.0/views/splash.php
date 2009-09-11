<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php print $this->title ?></title>
		<?php print $head ?>
		<?php print $this->getCssFiles() ?>
		<?php print $this->getJsFiles() ?>
		<?php $this->drawArray($this->head); ?>
	</head>
	<?php
		if((count($this->right) != 0) && (count($this->left) != 0))
			$columnNumber = 3;
		elseif ((count($this->right) != 0) || (count($this->left) != 0))
			$columnNumber = 2;
		else
			$columnNumber = 1;
	?>
	<body>
		<div id="container">
			<div id="headpiece_top" class="headpiece"></div>
			<div id="page">
				<div id="header">
					<div id="logo">
						<a href="<?php echo ROOT_URL ?>"><img src="<?php echo ROOT_URL.$this->logoImg ?>" alt="Splash" /></a>
					</div>
					<?php if (count($this->header) != 0) { ?>
						<div id="nav">
							<?php $this->drawArray($this->header); ?>
						</div>
					<?php } ?>
				</div>
				<table border="0" cellpadding="0" cellspacing="0" id="content">
					<tr>
					    <?php if (count($this->left) != 0) { ?>
						<td id="sidebar-left">
							<div class="sidebar_left"></div>
							<div class="sidebar_right"></div>
							<div class="sidebar_middle">
								<div class="sidebar_top"></div>
								<div class="sidebar_center">
									<div class="sidebar_content">
										<?php $this->drawArray($this->left); ?>
									</div>
								</div>
								<div class="sidebar_bottom"></div>
							</div>
					    </td><?php } ?>
					    <td valign="top">
							<?php if ($mission) { ?><div id="mission"><?php print $mission ?></div><?php } ?>
							<div id="main">
							    <?php print $top_content; ?>
								<div id="main_left<?php echo $columnNumber; ?>"></div>
								<div id="main_right<?php echo $columnNumber; ?>"></div>
								<div id="main_middle<?php echo $columnNumber; ?>">
									<div id="main_top<?php echo $columnNumber; ?>"></div>
									<div id="main_center<?php echo $columnNumber; ?>">
										<div id="main_content">
											<?php $this->drawArray($this->content); ?>
											<div style="clear: both"></div>
										</div>
									</div>
									<div id="main_bottom<?php echo $columnNumber; ?>"></div>
									<?php print $bottom_content; ?>
						        </div>
							</div>
					    </td>
					    <?php if (count($this->right) != 0) { ?>
							<td id="sidebar-right">
								<div class="sidebar_left"></div>
								<div class="sidebar_right"></div>
								<div class="sidebar_middle">
									<div class="sidebar_top">
									</div>
									<div class="sidebar_center">
										<div class="sidebar_content">
											<?php print $this->drawArray($this->right); ?>
										</div>
									</div>
									<div class="sidebar_bottom">
									</div>
								</div>
						    </td><?php } ?>
					</tr>
				</table>
				<div id="footer">
					<!-- &copy; Copyright 2008 Apideo (TM) -->
					  <?php print $footer_message ?>
					  <?php print $footer ?>
				</div><?php print $closure ?>
			</div>
			<div id="headpiece_bottom"></div></div>
	</body>
</html>