<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php print $this->title ?></title>
		<?php print $this->getCssFiles() ?>
		<?php $this->drawArray($this->head); ?>
		<?php print $this->getJsFiles() ?>
		
	</head>
	<?php
	$class = ''; 
	if (count($this->left) != 0)
		$class .= 'sidebar-left-body';
	if (count($this->right) != 0) {
		if($class)
			$class .= ' ';
		$class .= 'sidebar-right-body';
	}
	?>
	<body class="<?php echo $class ?>">
		<div id="page">
			<div id="header">
				<div id="logo">
					<a href="<?php echo ROOT_URL ?>">
						<img src="<?php echo ROOT_URL.$this->logoImg ?>" alt="Mouf" />
					</a>
				</div>
			</div>
			<?php if (count($this->header) != 0) { ?>
				<?php $this->drawArray($this->header);?>
			<?php } ?>
			<?php if (count($this->left) != 0) { ?>
				<div id="sidebar-left" class="sidebar">
					<?php $this->drawArray($this->left);?>
				</div>
			<?php } ?>
			<?php if (count($this->right) != 0) { ?>
				<div id="sidebar-right" class="sidebar">
						<?php $this->drawArray($this->right);?>
				</div>
			<?php } ?>
				
			<div id="content">
				<?php $this->drawArray($this->content); ?>
			</div>
			<div style="height: 10px;"></div>
		</div>
	</body>
</html>

<?php 
/*
 <div id="art-main">
				<div class="art-sheet">
				    <div class="art-sheet-body">
						<div class="art-nav">
	          				<div class="region region-navigation">
								<div id="art-menu-id">
									<!-- 
									<ul class="art-menu">
										<li class="first leaf"><a href="/" title=""><span class="l"> </span><span class="r"> </span><span class="t">Home</span></a></li>
										<li class="expanded active-trail active"><a href="/node/3" class="active-trail active active"><span class="l"> </span><span class="r"> </span><span class="t">Documentation</span></a></li>
										<li class="leaf"><a href="/node/4" title=""><span class="l"> </span><span class="r"> </span><span class="t">Download small</span></a></li>
										<li class="leaf"><a href="/node/5" title=""><span class="l"> </span><span class="r"> </span><span class="t">Tutorials</span></a></li>
										<li class="last leaf"><a href="/node/6" title=""><span class="l"> </span><span class="r"> </span><span class="t">Upload</span></a></li>
									</ul>
									 -->
								</div>
	  						</div>
	   					</div>
						<div class="art-content-layout">
	    					<div class="art-content-layout-row">
								<div class="art-layout-cell art-sidebar1">
									<div class="region region-sidebar-left">
	    								<div class="block block-menu-block" id="block-menu-block-1">
											<div class="art-block">
	      										<div class="art-block-body">
													<div class="art-blockcontent">
			    										<div class="art-blockcontent-body">
															<div class="content">
																<div class="menu-block-wrapper menu-block-1 menu-name-main-menu parent-mlid-0 menu-level-2">
																	<?php $this->drawArray($this->right);?>
																	<!-- 
	  																<ul class="menu">
	  																	<li class="first expanded menu-mlid-341">
	  																		<a href="/node/3" title="">Documentation</a>
		  																	<ul class="menu">
		  																		<li class="first leaf menu-mlid-335"><a href="/node/12">Installing Mouf</a></li>
																				<li class="leaf menu-mlid-340"><a href="/node/13">Using components</a></li>
																				<li class="last leaf menu-mlid-342"><a href="/node/14">Building components</a></li>
																			</ul>
																		</li>
																		<li class="last expanded active-trail menu-mlid-344">
																			<a href="/node/15" title="" class="active-trail">Writing Mouf packages</a>
																			<ul class="menu">
																				<li class="first leaf menu-mlid-343"><a href="/node/15">Writing your own Mouf validator</a></li>
																				<li class="leaf menu-mlid-345"><a href="/node/16">Integrating your module with Mouf full-text search</a></li>
																				<li class="leaf menu-mlid-346"><a href="/node/17">Writing an install process for your package</a></li>
																				<li class="last leaf active-trail active menu-mlid-347"><a href="/writing_packages_documentation" class="active-trail active">Writing documentation for your packages</a></li>
																			</ul>
																		</li>
																	</ul>
																	 -->
																</div>
															</div>
			    										</div>
													</div>
			    								</div>
											</div>
										</div>
									</div>
								</div>
								<div class="art-layout-cell art-content">              
									<div class="region region-content">
										<div class="region region-content">
											<div class="block block-system" id="block-system-main">
												<div id="node-18" class="node node-page">
													<div class="art-post">
														<div class="art-post-tl"></div>
														<div class="art-post-tr"></div>
													    <div class="art-post-bl"></div>
													    <div class="art-post-br"></div>
													
													    <div class="art-post-tc"></div>
													    <div class="art-post-bc"></div>
													    <div class="art-post-cl"></div>
													    <div class="art-post-cr"></div>
													    <div class="art-post-cc"></div>
													    <div class="art-post-body" style="margin: 0; padding: 0">
													    	
													    <!-- 
															<div class="art-post-inner">
															<h2 class="art-postheader">Writing documentation for your packages</h2>
															<div class="art-postcontent">
																<div class="field field-name-body field-type-text-with-summary field-label-hidden">
																	<div class="field-items">
																		<div class="field-item even">
																			<p>A good package should be documented. Indeed, what's the use of writing reusable code if nobody can use it? Mouf makes documentation writing easy for developers. The idea is simple: you write your documentation inside your package directory, in HTML. The documentation will be available to the users of the package (accessible through the "documentation" menu in the Mouf admin interface), and will also be directly published to the Mouf website if you decide to upload your package.</p>
																			<h2>The documentation root directory</h2>
																			<p>By default, you should place your documentation in the "doc" directory. This "doc" directory should be in your package's main directory. This "doc" directory is what we call the "documentation root directory". Any HTML file, any image from your documentation should be in this directory.</p>
																			<p>The documentation is declared in the package.xml file. You can change the root directory of your documentation by playing with the "root" attribute of the &lt;doc&gt; tag, as shown below:</p>
																			<pre>
																				&lt;package&gt;
																					...
																					&lt;doc root='doc'&gt;
																				
																						&lt;page title="Introduction to my package" url="index.html" /&gt;
																						&lt;page title="My package chapter 1" url="chapter1.html"&gt;
																							&lt;page title="My package page 1" url="chapter1/page1.html"/&gt;
																							&lt;page title="My package page 2" url="chapter1/page2.html"/&gt;
																						&lt;/page&gt;
																						...
																					&lt;/doc&gt;
																				
																					...
																				&lt;/package&gt;
																			</pre>
																			<h2>Declaring documentation pages</h2>
																			<p>Any file ending with the ".html" extension in the "doc" directory will be accessible as a documentation page. However, it will not automatically appear in the documentation menu. To have a link to your page displayed in the documentation menu, you must declare it in the package.xml file. As you can see in the sample above, you must use the &lt;page&gt; tag to declare a page.</p>
																			<p>The &lt;page&gt; tag accepts 2 attributes:</p>
																			<ul>
																				<li><b>title</b>: this is the text of the menu</li>
																				<li><b>url</b>: the is the URL to your file, relative to the package's documentation root directory</li>
																			</ul>
																			<h2>A few things to know</h2>
																			<p>Only pure HTML files are accessible in the documentation. You cannot use PHP files.</p>
																			<p>Only files ending with the ".html" extension will be displayed as HTML files.</p>
																			<p>You can use images (PNG, JPG, etc...)</p>
																			<p>You do not need to write a full HTML file, you can start directly with the content of the &lt;body&gt; tag.</p>
																			<p>Only the &lt;body&gt; tag will be displayed. The content of the &lt;head&gt; tag will be discarded.</p>
																		</div>
																	</div>
																</div>
	    														 -->
															</div>
															<div class="cleared"></div>
														</div>
														<div class="cleared"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="cleared"></div>
					<div class="art-footer">
						<div class="art-footer-body">
							<div class="art-footer-text">
								<?php $this->drawArray($this->footer);?>
								<!-- 
								<p>
			                		<a href="/node/8">Contact Us</a>
			                		&nbsp;|&nbsp;
			                		<a href="/node/9">Terms of Use</a>
			                		&nbsp;|&nbsp;
			
			                		<a href="/node/10">Trademarks</a>
			                		&nbsp;|&nbsp;
			                		<a href="/node/11">Privacy Statement</a>
			                		<br />Copyright &#169; 2011&nbsp;Mouf Web Site.&nbsp;All Rights Reserved.
			                	</p>
			                	 -->
		                	</div>
							<div class="cleared"></div>
						</div>
					</div>
					<div class="cleared"></div>
				</div>
			</div>
			*/