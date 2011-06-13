<?php /* @var $this DocumentationController */ ?>
<h1>Documentation for installed packages</h1>

<?php 


foreach ($this->packageList as $package):
	/* @var $package MoufPackage */
	$docPages = $package->getDocPages();
	if ($docPages):
		?><h2><?php echo $package->getDisplayName() ?></h2>
		<p>Package <?php echo $package->getDescriptor()->getGroup()."/".$package->getDescriptor()->getName()." Version: ".$package->getDescriptor()->getVersion() ?></p>
		<?php 
		$this->displayDocDirectory($docPages);
		?>
		<?php
	endif;
endforeach;
?>