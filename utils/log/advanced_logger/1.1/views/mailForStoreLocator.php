<?php
/* @var $this AdvancedMailLogger */
?>
<h1>WebParser</h1>

<h2>Résumé :</h2>

<h3>Nombre de nouveau magasin :</h3><?php echo $this->nbNewStores ?>
<h3>Nombre de magasin supprimé :</h3><?php echo $this->nbDeletedStores ?>
<h3>Nombre de logs :</h3>
<table>
	<tr>
		<td width="200" style="background-color: #ff6d6d">FATAL</td>
		<td width="100" style="text-align: right"><?php echo isset($this->nbErrorByLevel['FATAL'])?$this->nbErrorByLevel['FATAL']:0 ?></td>
	</tr>
	<tr>
		<td width="200" style="background-color: #ffaaaa;">ERROR</td>
		<td width="100" style="text-align: right"><?php echo isset($this->nbErrorByLevel['ERROR'])?$this->nbErrorByLevel['ERROR']:0 ?></td>
	</tr>
	<tr>
		<td width="200" style="background-color: #ffbc86;">WARN</td>
		<td width="100" style="text-align: right"><?php echo isset($this->nbErrorByLevel['WARN'])?$this->nbErrorByLevel['WARN']:0 ?></td>
	</tr>
	<tr>
		<td width="200" style="background-color: #7eff87;">INFO</td>
		<td width="100" style="text-align: right"><?php echo isset($this->nbErrorByLevel['INFO'])?$this->nbErrorByLevel['INFO']:0 ?></td>
	</tr>
	<tr>
		<td width="200" style="background-color: #caffce;">DEBUG</td>
		<td width="100" style="text-align: right"><?php echo isset($this->nbErrorByLevel['DEBUG'])?$this->nbErrorByLevel['DEBUG']:0 ?></td>
	</tr>
	<tr>
		<td width="200">TRACE</td>
		<td width="100" style="text-align: right"><?php echo isset($this->nbErrorByLevel['TRACE'])?$this->nbErrorByLevel['TRACE']:0 ?></td>
	</tr>
</table>

</br>
<h2>Logs par catégorie :</h2>

<table width="100%">
	<tr>
		<th width="100"></th>
		<th>Marque</th>
		<?php if ($this->aggregateByCategory>=2): ?><th>Message</th><?php endif;?>
		<th width="80">Nb logs</th>
	</tr>
	<?php foreach ($this->errorByCategory as $row): 
			switch ($row['log_level']) {
				case "FATAL":
					$bgColor = "#ff6d6d";
					break;
				case "ERROR":
					$bgColor = "#ffaaaa";
					break;
				case "WARN":
					$bgColor = "#ffbc86";
					break;
				case "INFO":
					$bgColor = "#ff6d6d";
					break;
				case "DEBUG":
					$bgColor = "#7eff87";
					break;
				case "TRACE":
					$bgColor = "#ffffff";
					break;
			}
	?>
	<tr style="background-color: <?php echo $bgColor ?>;">
		<td><?php echo $row['log_level'] ?></td>
		<td><?php echo $row['category1'] ?></td>
		<?php if ($this->aggregateByCategory>=2): ?><td><?php echo $row['category2'] ?></td><?php endif;?>
		<td style="text-align: right"><?php echo $row['nb_logs'] ?></td>
	</tr>
	<?php endforeach;?>
</table>