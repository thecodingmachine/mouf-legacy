<?php
class BceAdminBaseFieldBean{
	
	/**
	 * @var string
	 */
	public $instanceName;
	
	/**
	 * @var string
	 */
	public $fieldName;
	
	/**
	* @var string
	*/
	public $label;
	
	/**
	* @var string
	*/
	public $getter;
	
	/**
	* @var string
	*/
	public $setter;
	
	//TODO type this property
	public $validator;
	
	//TODO type this property
	public $formatter;
	
	public function __construct(MoufInstanceDescriptor $desc){
		$this->instanceName = $desc->getName();
		$this->fieldName = $desc->getProperty('fieldName')->getValue();
		$this->label = $desc->getProperty('label')->getValue();
		$this->getter = $desc->getProperty('getter')->getValue();
		$this->setter = $desc->getProperty('setter')->getValue();
	}
	
	public function renderAdmin(){
	?>
		<div>
			<div>
				<label>Field Name</label>
				<span><?php echo $this->fieldName?></span>
			</div>
			<div>
				<label>Label</label>
				<input type="text" name="label[<?php echo $this->instanceName?>]" value="<?php echo $this->label?>"/>
			</div>
			<div>
				<label>Getter</label>
				<input type="text" name="getter[<?php echo $this->instanceName?>]" value="<?php echo $this->getter?>"/>
			</div>
			<div>
				<label>Setter</label>
				<input type="text" name="setter[<?php echo $this->instanceName?>]" value="<?php echo $this->setter?>"/>
			</div>
		</div>
	<?php
	}
}

class BceAdminFKFieldBean extends BceAdminBaseFieldBean{
	
	//TODO type this property
	public $dao;
	
	/**
	 * @var string
	 */
	public $linkedFieldGetter;
	
	/**
	 * @var string
	 */
	public $linkedValueGetter;
	
	/**
	 * @var BceConfigController
	 */
	public $context;
	
	public function __construct(MoufInstanceDescriptor $desc, $context){
		parent::__construct($desc);
		
		$this->linkedFieldGetter = $desc->getProperty('linkedFieldGetter')->getValue();
		$this->linkedValueGetter = $desc->getProperty('linkedValueGetter')->getValue();
		
		$this->context = $context;
	}
	
	public function renderAdmin(){
		?>
			<div>
				<div>
					<label>Field Name</label>
					<span><?php echo $this->fieldName; ?></span>
				</div>
				<div>
					<label>Label</label>
					<input type="text" name="label[<?php echo $this->instanceName; ?>]" value="<?php echo $this->label; ?>"/>
				</div>
				<div>
					<label>Getter</label>
					<input type="text" name="getter[<?php echo $this->instanceName; ?>]" value="<?php echo $this->getter; ?>"/>
				</div>
				<div>
					<label>Setter</label>
					<input type="text" name="setter[<?php echo $this->instanceName; ?>]" value="<?php echo $this->setter; ?>"/>
				</div>
				<div>
					<label>DAO</label>
					<select>
					<?php 
					/*foreach ($this->context->daoInstances as $dao) {
					?>
						<option value="<?php echo $dao?>"><?php echo $dao?></option>
					<?php
					}*/
					?>	
					</select>					
				</div>
				<div>
					<label>Field Getter</label>
					<input type="text" name="fieldGetter[<?php echo $this->instanceName?>]" value="<?php echo $this->linkedFieldGetter?>"/>
				</div>
				<div>
					<label>Value Getter</label>
					<input type="text" name="valueGetter[<?php echo $this->instanceName?>]" value="<?php echo $this->linkedValueGetter?>"/>
				</div>
			</div>
		<?php
		}
}