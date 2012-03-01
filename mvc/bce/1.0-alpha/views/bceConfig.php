<?php 
/* @var $this BceConfigController */
?>
<h1>Configuration of <i>'<?php echo $this->instanceName ?>'</i> instance</h1>
<form>
	<div>
		<label>
			Main DAO:&nbsp;
			<?php
			//http://localhost/samples/mouftests/mouf/mouf/displayComponent?name=db2frDateFormater&selfedit=false 
			if ($this->mainDAOName){echo "<span><a href='".ROOT_URL."mouf/mouf/displayComponent?name=".$this->mainDAOName."&selfedit=false'>".$this->mainDAOName."</a></span>";}
			else{
			?>
			<select>
			<?php 
			foreach ($this->daoInstances as $dao) {
			?>
				<option value="<?php echo $dao?>"><?php echo $dao?></option>
			<?php
			}
			?>	
			</select>
			<?php 
			}
			?>
		</label>
		<fieldset>
			<legend>Id field</legend>
			<?php 
			$this->idFieldDescriptor->renderAdmin();			
			?>	
		</fieldset>
		<?php if ($this->existingFieldDescriptors){?>
		<fieldset>
			<legend>Existing Fields</legend>
			<?php foreach ($this->existingFieldDescriptors as $field) {
				$field->renderAdmin();
			}?>
		</fieldset>
		<?php }?>
		<?php if ($this->fields){?>
		<fieldset>
			<legend>Fields</legend>
			<table>
				<tr>
					<th>Field Name</th>
					<th>Label</th>
					<th>Getter Method</th>
					<th>Setter Method</th>
					<th>Formatter</th>
					<th>Renderer</th>
				</tr>
			<?php foreach ($this->fields as $fieldName => $data) {
			?>
				<tr>
					<td><?php echo $fieldName ?></td>
					<td><input name="fieldnames[<?php echo $fieldName ?>]" value="<?php echo $fieldName ?>"/></td>
					<td><input name="getters[<?php echo $fieldName ?>]" value="<?php echo $data['getter'] ?>"/></td>
					<td><input name="setters[<?php echo $fieldName ?>]" value="<?php echo $data['setter'] ?>"/></td>
					<td>Formatter</td>
					<td>Renderer</td>
				</tr>
			<?php
			}?>
			</table>
		</fieldset>
		<?php }?>
	</div>
</form>
