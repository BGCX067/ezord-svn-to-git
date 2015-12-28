<div id="account">
	<h3 class="underline">Account information</h3>
	<p><strong><?php echo $user['first_name'].' '.$user['last_name']; ?></strong> (<?php echo $user['email']; ?>)</p>
	<p>Since: <?php echo $this->Time->niceShort($user['created']) ?></p>
	<h3 class="underline">Current package &amp; usage</h3>
	<p></p>
	<h3 class="underline">Export data</h3>
	<p>Coming soon ...</p>
	
	<h3 class="underline">Cancel Account</h3>
	<p>We are sorry to see you leave. All your data will be deleted within 30 days</p>
	<br />
	<p><?php echo $this->Html->link('Cancel account', '#', array('class' => 'button')) ?></p>
</div>