<?php

/**
 * This class represent a Html Grid (an HTML datagrid).
 *
 * @Component
 */
class AjaxHtmlGridWidget extends DataGrid implements HtmlElementInterface {
	
	//TODO: use number to set default table container's Id
	private static $number = 0;
	
	/**
	 * The Id of the DataGrid's container
	 * @Compulsory
	 * @Property 
	 * @var string
	 */
	public $containerId;
	
	/**
	 * The width of the table
	 * @Compulsory
	 * @Property 
	 * @var int
	 */
	public $tableWidth;
	
	/**
	 * The URL that will be used to retrieve the data to be displayed.
	 * Path is relative to the ROOT_URL and should not start with a /.
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $dataUrl;
	
	/**
	 * The name of the column which the default sort will be performed on.
	 *
	 * @Property 
	 * @Compulsory
	 * @var string
	 */
	public $defaultSortColumn;
	
	/**
	 * The default sort order (ASC or DESC).
	 *
	 * @Property
	 * @Compulsory
	 * @OneOf("ASC", "DESC")
	 * @var string
	 */
	public $defaultSortOrder = "ASC";
	
	/**
	 * The name of the column which sort is currently performed on.
	 * @var string
	 */
	private $currentSortColumn;
	
	/**
	 * The current sort order (ASC or DESC).
	 * @var string
	 */
	private $currentSortOrder;
	
	/**
	 * The max number of rows displayed
	 * @Property
	 * @Compulsory
	 * @var int
	 */
	public $maxDisplay;
	
	/**
	 * The current page
	 *  @var int
	 */
	public $currentPage=1;
	
	public function firstHtml(){
		$this->drawJS();
		$this->toHtml();
	}
	
	private function drawJS(){
?>
	<script type="text/javascript">
	<!--
	function paginate(page, sort, order){
		$("#ttd_loader").show();
		$.ajax({
			type: "GET",
			url: "<?php echo $this->dataUrl ?>",
			data: "page="+page+"&sort="+sort+"&order="+order,
			success: function(response){
				$("#<?php echo $this->containerId ?>").html(response);
				$("#ttd_loader").hide();
			},
			error: function(xhr, opt, error){
				$("#ttd_loader").hide();
				alert("Ajax error\nStatus : "+xhr.status);
			}
		});
	}
	
	function sort(sort, order){
		$("#ttd_loader").show();
		$.ajax({
			type: "GET",
			url: "<?php echo $this->dataUrl ?>",
			data: "sort="+sort+"&order="+order,
			success: function(response){
				$("#<?php echo $this->containerId ?>").html(response);
				$("#ttd_loader").hide();
			},
			error: function(xhr, opt, error){
				$("#ttd_loader").hide();
				alert("Ajax error\nStatus : "+xhr.status);
			}
		});
	}
	//-->
	</script>
<?php
	}

	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	function toHtml($page=1, $sortCol=null, $sortOrder=null) {
		self::$number++; 
		$this->currentPage = empty($page)?1:$page;
		$this->currentSortColumn = $sortCol;
		$this->currentSortOrder = $sortOrder;
		
		$this->datasource->setOrderColumn($sortCol);
		$this->datasource->setOrder($sortOrder);
		
		if ($this->containerId == null){
			$this->containerId = "moufJqGridTableNumber".self::$number;
		}
		if ($this->currentSortOrder == null) {
			$this->currentSortOrder = $this->defaultSortOrder;
			$this->datasource->setOrder($this->defaultSortOrder);
		}
		if ($this->currentSortColumn == null) {
			$this->currentSortColumn = $this->defaultSortColumn;
			$this->datasource->setOrderColumn($this->defaultSortColumn);
		}
		
		
			
	?>
	<div id="<?php echo $this->containerId ?>" style="position: relative; width: <?php echo $this->tableWidth; ?>px">
	<table class="ttd" cellpadding="0" cellspacing="0" style="width: <?php echo $this->tableWidth; ?>px">
	<tbody>
		<tr>
		<?php 
		foreach ($this->columns as $column) {
			if ($this->currentSortColumn==$column->getSortColumn() && $this->currentSortOrder=="ASC")
				$sortOrder = "DESC";
			else 
				$sortOrder = "ASC";
		?>
			<th 
				style="width: <?php echo $column->getWidth()?$column->getWidth()."px":"auto" ?>"
				<?php 
					if ($column->isSortable()){
						echo "onclick=\"sort('".$column->getSortColumn()."', '$sortOrder')\" ";
						$className = "sortable";
						if ($this->currentSortColumn==$column->getSortColumn()){
							$className.=" activeSort";
						}
						echo "class=\"$className\"";
					}
				?>
			>
				<table cellpadding="0" cellspacing="0">
				<tr>
				<td>
					<?php echo $column->getTitle();
					?>
				</td>
				<td>
				<?php 
				if ($column->isSortable()){
					
				?>
					<?php
					if ($this->currentSortColumn==$column->getSortColumn() && $this->currentSortOrder=="ASC"){
					?>
						<div class="sort asc">&nbsp;</div>
					<?php
					}else{?>
						<div class="sort desc">&nbsp;</div>
					<?php }
				}
				
				?>
				</td>
				<?php
				if (BaseWidgetUtils::isWidgetEditionEnabled()){
					$manager = MoufManager::getMoufManager();
					$instanceName = $manager->findInstanceName($column);
				?>
				<td>
					<?php echo "<a href='".ROOT_URL."mouf/mouf/displayComponent?name=".urlencode($instanceName).BaseWidgetUtils::getBackToParameter()."' style='margin-left: 5px'>edit</a>"; ?>
				</td>
				<?php
				}
				?>
				</tr></table>
			</th>
		<?php
		}
		?>
		</tr>
		<?php
		$i=0;
		$count = $this->datasource->getGlobalCount($this->dsParams);
		// calculate the total pages for the query 
		if( $count > 0 ) { 
			$total_pages = ceil($count/$this->maxDisplay); 
		} else { 
			$total_pages = 0; 
		} 
		// if for some reasons the requested page is greater than the total 
		// set the requested page to total page 
		if ($page > $total_pages) $page=$total_pages;
		 
		// calculate the starting position of the rows 
		$start = $this->maxDisplay*$page - $this->maxDisplay;
		// if for some reasons start position is negative set it to 0 
		// typical case is that the user type 0 for the requested page 
		if($start <0) $start = 0; 
		$this->datasource->load($this->dsParams, $start, $this->maxDisplay);
		foreach ($this->datasource as $row){
			$class = ($i%2)?"odd":"even";
			$i++;
		?>
			<tr class="<?php echo $class ?>">
		<?php 
			foreach ($this->columns as $column) {
		?>
				<td><?php echo $column->getValue($row) ?></td>
		<?php
			}
		 ?>
			</tr>
		<?php	
		}?>
		<tr>
			<td class="pager_cell" colspan="<?php echo (count($this->columns)) ?>">
				<?php $this->getPaginateHTML($total_pages); ?>
			</td>
		</tr>
		<?php 
		if (BaseWidgetUtils::isWidgetEditionEnabled()) {
			$instanceName = $manager->findInstanceName($this);
			if ($instanceName != false) {
			?>
			<tr>
			<td colspan="<?php echo (count($this->columns)) ?>">
				<?php echo "<a href='".ROOT_URL."mouf/mouf/displayComponent?name=".urlencode($instanceName).BaseWidgetUtils::getBackToParameter()."'>Edit table</a>"; ?>
			</td>
			</tr>
			<?php
			}
		}
		?>
	</tbody>
	</table>
	<div id="ttd_loader">Loading...</div>
	</div>
	<?php
	}

	public function getPaginateHTML($total_pages){
	?>
		<table class="pager" cellpadding="0" cellspacing="0">
			<tr>
				<td class="first_page" onclick="<?php echo "paginate(1,'$this->currentSortColumn', '$this->currentSortOrder')" ?>"></td>
			<?php if ($this->currentPage!=1){ ?>
				<td class="previous_page" onclick="<?php echo "paginate('".($this->currentPage-1)."','$this->currentSortColumn', '$this->currentSortOrder')" ?>"></td>
			<?php } ?>
				<?php 
				for ($i=1;$i<=$total_pages;$i++){
						if ($i == $this->currentPage){
						?>
							<td class="current_page"><?php echo $i; ?></td>
						<?php
						}
						else{
						?>
							<td class="other_page"><a onclick="<?php echo "paginate($i,'$this->currentSortColumn', '$this->currentSortOrder')" ?>"><?php echo $i; ?></a></td>
						<?php
						}
				}				
				?>
			<?php if ($this->currentPage != $total_pages){ ?>
				<td class="next_page" onclick="<?php echo "paginate(".($this->currentPage+1).",'$this->currentSortColumn', '$this->currentSortOrder')" ?>"></td>
			<?php } ?>
				<td class="last_page" onclick="<?php echo "paginate($total_pages,'$this->currentSortColumn', '$this->currentSortOrder')" ?>"></td>
			</tr>
		</table>
	<?php
	}
	
}
?>