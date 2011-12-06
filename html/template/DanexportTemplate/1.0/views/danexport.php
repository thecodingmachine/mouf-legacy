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
		if((count($this->right) != 0) && (count($this->left) != 0))
			$columnNumber = 3;
		elseif ((count($this->right) != 0) || (count($this->left) != 0))
			$columnNumber = 2;
		else
			$columnNumber = 1;
	?>
	
	
	<body>
<div id="art-page-background-simple-gradient">
        <div id="art-page-background-gradient"></div>
    </div>
    <div id="art-main">
        <div class="art-sheet">
            <div class="art-sheet-tl"></div>
            <div class="art-sheet-tr"></div>
            <div class="art-sheet-bl"></div>
            <div class="art-sheet-br"></div>
            <div class="art-sheet-tc"></div>
            <div class="art-sheet-bc"></div>
            <div class="art-sheet-cl"></div>
            <div class="art-sheet-cr"></div>
            <div class="art-sheet-cc"></div>
            <div class="art-sheet-body">
                <div class="art-header">
                    <div class="art-header-jpeg"></div>
                    <div class="art-logo">
                        <h1 id="name-text" class="art-logo-name"><a href="<?php echo ROOT_URL;?>">Dan’export</a></h1>
                        <div id="slogan-text" class="art-logo-text">The easiest way to get your information</div>
                    </div>
                </div>
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <div class="art-layout-cell art-sidebar1">
                            <div class="art-vmenublock">
                                <div class="art-vmenublock-cc"></div>
                                <div class="art-vmenublock-body">
                                            <div class="art-vmenublockcontent">
                                                <div class="art-vmenublockcontent-body">
                                            <!-- block-content -->
                                                    <?php $this->drawArray($this->header); ?>
                                            <!-- /block-content -->
                                            		<div class="cleared"></div>
                                                </div>
                                            </div>
                            		<div class="cleared"></div>
                                </div>
                            </div>
                          
                            <div class="art-block">
                                <div class="art-block-body">
                                            <div class="art-blockcontent">
                                                <div class="art-blockcontent-body">
                                            <!-- block-content -->
                                                            <div>
                                                               <?php $this->drawArray($this->left); ?>
                                                            </div>
                                            <!-- /block-content -->
                                            
                                            		<div class="cleared"></div>
                                                </div>
                                            </div>
                            		<div class="cleared"></div>
                                </div>
                            </div>
                        </div>
                        <div class="art-layout-cell art-content">
                            <div class="art-post">
                                <div class="art-post-body">
                            <div class="art-post-inner art-article">
                                            <div class="art-postcontent">
                                                <!-- article-content -->
                                                <?php $this->drawArray($this->content); ?>
                                                <!-- /article-content -->
                                            </div>
                                            <div class="cleared"></div>
                            </div>
                            
                            		<div class="cleared"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cleared"></div><div class="art-footer">
                    <div class="art-footer-t"></div>
                    <div class="art-footer-body">
                        <div class="art-footer-text">
                            <?php //print $footer_message ?>
					  		<?php //print $footer ?>
                                Copyright &#169; 2011 ---. All Rights Reserved.</p>
                        </div>
                		<div class="cleared"></div>
                    </div>
                </div>
        		<div class="cleared"></div>
            </div>
        </div>
        <div class="cleared"></div>
        <p class="art-page-footer"></p>
    </div>
</body>
</html>