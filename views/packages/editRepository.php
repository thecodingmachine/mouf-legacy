<?php /* @var $this RepositorySourceController */ ?>
<h1>Add/edit repository</h1>

<form action="save">
<input type="hidden" name="selfedit" value="<?php echo $this->selfedit ?>" />
<?php if ($this->repositoryId !== null) { ?>
<input type="hidden" name="id" value="<?php echo $this->repositoryId ?>" />
<?php } ?>

<div>
<label>Repository name:</label>
<input type="text" name="name" value="<?php if ($this->repositoryId !== null) echo plainstring_to_htmlprotected($this->repositoryUrls[$this->repositoryId]["name"]) ?>" />
</div>

<div>
<label>Repository URL:</label>
<input type="text" name="url" value="<?php if ($this->repositoryId !== null) echo plainstring_to_htmlprotected($this->repositoryUrls[$this->repositoryId]["url"]) ?>" />
</div>

<button>Save</button>
</form>