<?php /* @var $this TaskManagerController */ ?>
<h1>List of awaiting tasks</h1>

<?php
if (!$this->awaitingTasks) {
	echo "No awaiting tasks found.";
} else {
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
				<th></th>
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
				<td class="white-space: pre-wrap"><?php echo plainstring_to_htmloutput($task['lastOutput']) ?></td>
				<td><a href="deleteTask?id=<?php echo plainstring_to_htmloutput($task['id']) ?>&taskmanager=<?php echo urlencode($taskManagerName) ?>&selfedit=<?php echo $this->selfedit ?>" onclick="return confirm('Are you sure you want to delete this task?')"><img src="<?php echo ROOT_URL ?>plugins/utils/tasks/taskmanager/1.0/views/images/cross.png" alt="Delete" /></a></td>
			</tr>
<?php 		
		}
?>
		</table>
<?php 
	}
}
