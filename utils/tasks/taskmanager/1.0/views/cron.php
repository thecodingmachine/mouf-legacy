<h1>Task Manager installation</h1>

<h2>Using CRON</h2>
<p>You want to register TaskManager as a cron task that will run periodically. Otherwise, the failed tasks would never be attempted again.</p>
<p>If you are running a Linux machine, the easiest way to do this is to use cron.</p>
<p>To register a new cron task, use the <code>crontab -e</code> command.</p>
<p>Note: you might want to run this command as the Apache user if you want to have the same access rights than Apache (in this case, you can use <code>su www-data -c "crontab -e"</code>)</p>

<p>Inside the editor, add this line:</p>

<pre>
*/10 * * * * /usr/bin/php <?php echo ROOT_PATH ?>plugins/utils/tasks/taskmanager/1.0/cron.php
</pre>

<p>This will run the TaskManager every 10 minutes to retry any task that would have failed and passed the retry interval.</p>