<script type="text/javascript">
<?php
$phpJitNodes = $this->getJitJson($this->instanceName);
//$phpJitNodes = $this->getJitJsonAllInstances();
if (count($phpJitNodes)>1) {
?>
	jsonNodes = <?php echo json_encode($phpJitNodes); ?>;
	Event.observe(window, 'load', initJit);
<?php
}
?>

</script>

<div id="infovis" style="height:600px;width:600px"></div>    
<div id="log"></div>
<div id="inner-details"></div>
