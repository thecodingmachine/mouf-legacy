<?php /* @var $this RepositorySourceController */ ?>
<h1>Configured repositories</h1>

<?php
foreach ($this->repositoryUrls as $key=>$repUrl) {
?>
<div class="file">
<label>Name:</label> <?php echo $repUrl['name']; ?><br/>
<label>Url:</label> <?php echo $repUrl['url']; ?><br/>
<label>&nbsp;</label> <a href="edit?selfedit=<?php echo $this->selfedit ?>&id=<?php echo $key ?>">edit</a>
</div>
<?php 	
}
?>
<form action="add">
<input type="hidden" name="selfedit" value="<?php echo $this->selfedit ?>" />
<button>Add new repository</button>
</form>