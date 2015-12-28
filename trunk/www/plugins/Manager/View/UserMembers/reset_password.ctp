<?php echo $this->Html->script('/manager/js/validate/reset_password.js') ?>
<?php echo $this->Form->create('UserMember', array ('id' => 'UserMemberForm', 'action' => 'reset_password/'.$mid, 'method' => 'post')) ?>
	<?php echo $this->Form->hidden('User.id') ?>
    <fieldset id="register-form">
        <div class="input_field">
			<label for="a">Password</label>
            <?php echo $this->Form->password('User.password', array ('id' => 'password', 'class' => 'input mediumfield')) ?>
		</div>
        <div class="clear"></div>
        <div class="input_field">
			<label for="b">Confirm password</label>
            <?php echo $this->Form->password('User.confirm-password', array ('class' => 'input mediumfield')) ?>
		</div>
		<div class="clear"></div><br />
		<p class="input_field">
			<label for="e">&nbsp;</label>
            <?php echo $this->Form->submit('Save', array('class' => 'button', 'div' => false)) ?>
            <?php echo $this->Html->link('Cancel', '/manager/user_members/view/'.$mid, array('class' => 'button')) ?>
		</p>
	</fieldset>
<?php echo $this->Form->end(); ?>