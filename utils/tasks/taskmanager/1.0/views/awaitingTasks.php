<?php /* @var $this TaskManagerController */ ?>
<h1>List of awaiting tasks</h1>

<?php
foreach ($this->awaitingTasks as $taskManagerName => $taskArray) {
	echo "<h2>Tasks for ".$taskManagerName."</h2>";
?>
	<table>
		<tr>
			<th>Id</th>
			<th>Task</th>
			<th>Task processor</th>
			<th>Created on</th>
			<th>Last try on</th>
			<th>Next try on</th>
			<th>Nb tries</th>
			<th>Last output</th>
		</tr>
<?php 
	foreach ($taskArray as $task) {
?>
		<tr>
			<td><?php echo plainstring_to_htmloutput($task['id']) ?></td>
			<td><?php echo plainstring_to_htmloutput($task['name']) ?></td>
			<td><?php echo plainstring_to_htmloutput($task['taskProcessorName']) ?></td>
			<td><?php echo plainstring_to_htmloutput(date("Y-m-d H:i:s", $task['createdDate'])) ?></td>
			<td><?php echo plainstring_to_htmloutput(date("Y-m-d H:i:s", $task['lastTryDate'])) ?></td>
			<td><?php echo plainstring_to_htmloutput(date("Y-m-d H:i:s", $task['nextTryDate'])) ?></td>
			<td><?php echo plainstring_to_htmloutput($task['nbTries']) ?></td>
			<td><?php echo plainstring_to_htmloutput($task['lastOutput']) ?></td>
		</tr>
<?php 		
	}
?>
</table>
<?php 
}
