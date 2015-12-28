<?php echo $this->Html->script('/manager/js/validate/member_add.js') ?>
<?php echo $this->Form->create('User', array ('id' => 'UserMemberForm', 'action' => $action, 'method' => 'post')) ?>
	<?php echo $this->Form->hidden('User.id') ?>
    <fieldset id="register-form">
        <div class="input_field">
			<label for="a">First Name</label>
            <?php echo $this->Form->text('User.first_name', array ('class' => 'input mediumfield')) ?>
		</div>
        <div class="clear"></div>
        <div class="input_field">
			<label for="b">Last Name</label>
            <?php echo $this->Form->text('User.last_name', array ('class' => 'input mediumfield')) ?>
		</div>
		<div class="clear"></div>
		<div class="input_field">
			<label for="c">Email</label>
            <?php echo $this->Form->text('User.email', array ('class' => 'input mediumfield')) ?>
		</div>
		<div class="clear"></div>
        <div class="input_field">
			<label for="d">Phone Number</label>
            <?php echo $this->Form->text('User.phone', array ('class' => 'input mediumfield')) ?>
		</div>
		<div class="clear"></div><br />
		<p class="input_field">
			<label for="e">&nbsp;</label>
            <?php echo $this->Form->submit('Save', array('class' => 'button', 'div' => false)) ?>
            <?php echo $this->Html->link('Cancel', '/manager/accounts/me', array('class' => 'button')) ?>
		</p>
	</fieldset>
<?php echo $this->Form->end(); ?>