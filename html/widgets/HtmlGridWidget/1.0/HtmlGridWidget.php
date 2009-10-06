<?php

/**
 * This class represent a Html Grid (an HTML datagrid).
 *
 * @Component
 */
class HtmlGridWidget extends DataGrid implements HtmlElementInterface {
	
	private static $number = 0;
	
	
	/**
	 * The URL that will be used to retrieve the data to be displayed.
	 * Path is relative to the ROOT_URL and should not start with a /.
	 *
	 * @Property 
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
	 * @OneOf("ASC", "DESC")
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
	 * The Expander of the data grid
	 * If set, the grid will be added a "expander" under each row, displaying expander's data
	 * @Property
	 * @var GridExpanderInterface
	 */
	public $expander;
	
	/**
	 * The current page
	 *  @var int
	 */
	public $currentPage=1;
	
	
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
		
//		echo ("Page: $this->currentPage Column: $this->currentSortColumn Order: $this->currentSortOrder");
		

		if ($this->tableId == null) {
			$tableId = "moufJqGridTableNumber".self::$number;
		} else {
			$tableId = $this->tableId;
		}
		
		if ($this->pagerId == null) {
			$pagerId = "moufJqGridPagerNumber".self::$number;
		} else {
			$pagerId = $this->pagerId;
		}
		
		if ($this->currentSortOrder == null) {
			$this->currentSortOrder = $this->defaultSortOrder;
			$this->datasource->setOrder($this->defaultSortOrder);
		}
		if ($this->currentSortColumn == null) {
			$this->currentSortColumn = $this->defaultSortColumn;
			$this->datasource->setOrderColumn($this->defaultSortColumn);
		}
		
		$fullColspan = count($this->columns);
		if ($this->expander) $fullColspan++;
		
?>
		<table class="ttd" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
			<?php 
			if ($this->expander) echo "<th></th>";
			foreach ($this->columns as $column) {
				if ($this->currentSortColumn==$column->getSortColumn() && $this->currentSortOrder=="ASC"){
					$sortUrl = $this->getSortUrl($column, "DESC");
				}else $sortUrl =  $this->getSortUrl($column, "ASC");
			?>
				<th 
					style="width: <?php echo $column->getWidth()?$column->getWidth()."px":"auto" ?>"
					<?php 
						if ($column->isSortable()){
							echo "onclick=\"window.location='$sortUrl'\" ";
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
			$count = $this->datasource->getGlobalCount();
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
			$this->datasource->load(array(), $start, $this->maxDisplay);
			foreach ($this->datasource as $row){
				$class = ($i%2)?"odd":"even";
				$i++;
			?>
				<tr class="<?php echo $class ?>">
			<?php 
				if ($this->expander) echo "<td class=\"expander\" onclick=\"appendRow(".$this->idColumn->getValue($row).", this)\">&nbsp;</td>";
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
				<td class="pager_cell" colspan="<?php echo $fullColspan ?>">
					<?php $this->getPaginateHTML($total_pages); ?>
				</td>
			</tr>
			<?php 
			if (BaseWidgetUtils::isWidgetEditionEnabled()) {
				$instanceName = $manager->findInstanceName($this);
				if ($instanceName != false) {
				?>
				<tr>
				<td colspan="<?php echo $fullColspan ?>">
					<?php echo "<a href='".ROOT_URL."mouf/mouf/displayComponent?name=".urlencode($instanceName).BaseWidgetUtils::getBackToParameter()."'>Edit table</a>"; ?>
				</td>
				</tr>
				<?php
				}
			}
			?>
		</tbody>
		</table>
<?php 
if ($this->expander){
?>
	<script type="text/javascript">
	<!--
		var tab = new Array();

		<?php if ($this->expander->singleExpand){?>
		function collapseAll(curCell){
			var currentRowIndex = curCell.parentNode.rowIndex;
			var table = curCell.parentNode.parentNode.parentNode;
			var rows = curCell.parentNode.parentNode.rows;
			for (i=0;i<rows.length;i++){
				var row = rows[i];
				if (row.cells[0].className=='rollup' && row.rowIndex!=currentRowIndex) row.cells[0].className='expander';
				if (row.className=='expandRow' && row.rowIndex!=(currentRowIndex+1)){
					table.deleteRow(row.rowIndex);
					i--;
					currentRowIndex--;
				}
			}
			
		}
		<?php }?>
	
		function appendRow(id, curCell){
			var currentRowIndex = curCell.parentNode.rowIndex;
			var table = curCell.parentNode.parentNode.parentNode;
			
			var className = curCell.className;
			if (className=='expander'){
				curCell.className='rollup';
				var newRow = table.insertRow(currentRowIndex+1);
				newRow.className = 'expandRow';
				var expanderCell = newRow.insertCell(0);
				expanderCell.className='expandLeftCell';
				var expanderContent = newRow.insertCell(1);
				expanderContent.setAttribute('colspan', '<?php echo ($fullColspan-1);?>');
				expanderContent.innerHTML=tab[id];
			}else if (className=='rollup'){
				curCell.className='expander';
				table.deleteRow(currentRowIndex+1);
			}
			<?php if ($this->expander->singleExpand) echo "collapseAll(curCell);";?>
		}
<?php 
	foreach ($this->datasource as $row){
		$id = $this->idColumn->getValue($row);
		echo "tab[$id]='".$this->expander->getExpandData($id)."';\n";
	}
?>
	//-->
	</script>
	<?php
}?>
<?php
	}
	
	function getSortUrl($column, $order){
		return $this->dataUrl."?sort=".$column->getSortColumn()."&order=$order";
	}
	
	function getPaginateUrl($pageNb){
		return $this->dataUrl."?sort=".$this->currentSortColumn."&order=$this->currentSortOrder&page=$pageNb";
	}
	
	public function getPaginateHTML($total_pages){
	?>
		<table class="pager" cellpadding="0" cellspacing="0">
			<tr>
				<td class="first_page" onclick="window.location='<?php echo $this->getPaginateUrl(1); ?>'"></td>
			<?php if ($this->currentPage!=1){ ?>
				<td class="previous_page" onclick="window.location='<?php echo $this->getPaginateUrl($this->currentPage-1); ?>'"></td>
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
							<td class="other_page"><a href="<?php echo $this->getPaginateUrl($i)?>"><?php echo $i; ?></a></td>
						<?php
						}
				}				
				?>
			<?php if ($this->currentPage != $total_pages){ ?>
				<td class="next_page" onclick="window.location='<?php echo $this->getPaginateUrl($this->currentPage+1); ?>'"></td>
			<?php } ?>
				<td class="last_page" onclick="window.location='<?php echo $this->getPaginateUrl($total_pages); ?>'"></td>
			</tr>
		</table>
	<?php
	}
	
}
?>