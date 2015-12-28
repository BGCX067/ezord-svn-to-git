<?php echo $this->Html->script('/manager/js/validate/change_password.js') ?>
<?php echo $this->Form->create('User', array ('id' => 'UserForm', 'action' => 'change_password/', 'method' => 'post')) ?>
    <?php echo $this->Form->hidden('User.email', array('value'=>$email)) ?>
    <fieldset id="register-form">
        <div class="input_field">
			<label for="a">Current password</label>
            <?php echo $this->Form->password('User.current-password', array ('class' => 'input mediumfield')) ?>
		</div>
        <div class="input_field">
			<label for="a">New password</label>
            <?php echo $this->Form->password('User.password', array ('id' => 'password', 'class' => 'input mediumfield')) ?>
		</div>
        <div class="clear"></div>
        <div class="input_field">
			<label for="b">Confirm new password</label>
            <?php echo $this->Form->password('User.confirm-password', array ('class' => 'input mediumfield')) ?>
		</div>
		<div class="clear"></div><br />
		<p class="input_field">
			<label for="e">&nbsp;</label>
            <?php echo $this->Form->submit('Save', array('class' => 'button', 'div' => false)) ?>
            <?php echo $this->Html->link('Cancel', '/manager/accounts/me', array('class' => 'button')) ?>
		</p>
	</fieldset>
<?php echo $this->Form->end(); ?>