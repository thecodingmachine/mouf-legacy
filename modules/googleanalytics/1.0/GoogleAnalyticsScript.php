<?php

/**
 * This class can be used to insert Google Analytics Script.
 *
 * @Component
 */
class GoogleAnalyticsScript implements HtmlElementInterface {
	
    /**
     * The google Analytics Key provided when you subscribed to your account (in the form UA-xxxxx-x).
     *
     * @Property
     * @Compulsory
     * @var string
     */
    public $accountKey;
    
    /**
     * The base domain name to track (if you are tracking sub-domains). In the form: ".example.com"
     *
     * @Property
     * @var string
     */
    public $domainName;
    
    
    /**
     * Display link to insert css and script to insert js
     * @see HtmlElementInterface::toHtml()
     */
	public function toHtml() {
		if($this->accountKey) {
?>
<script type="text/javascript">
//Google Analytics
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo addslashes($this->accountKey) ?>']);
<?php 
	if ($this->domainName) {
		echo "_gaq.push(['_setDomainName', '".addslashes($this->domainName)."']);";
		echo "_gaq.push(['_setAllowLinker', true]);";	
	}
?>
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<?php 
		}
	}
}
?>