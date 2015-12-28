<?php echo $this->Html->script('validate/register.js') ?>
<div class="intro-text">
    <span>Manage your places with <a href="javascript:void(0);">1000 trial bills</a></span>
</div>
<?php echo $this->Form->create('User', array ('action' => 'register', 'method' => 'post')) ?>
	<fieldset id="register-form">
        <div class="input_field">
			<label for="c">First Name</label>
            <?php echo $this->Form->text('first_name', array ('class' => 'input mediumfield')) ?>
		</div>
        <div class="clear"></div>
        <div class="input_field">
			<label for="c">Last Name</label>
            <?php echo $this->Form->text('last_name', array ('class' => 'input mediumfield')) ?>
		</div>
		<div class="clear"></div>
		<div class="input_field">
			<label for="b">Email</label>
            <?php echo $this->Form->text('email', array ('class' => 'input mediumfield')) ?>
		</div>
		<div class="clear"></div>
		<div class="input_field">
			<label for="c">Password</label>
            <?php echo $this->Form->password('password', array ('id' => 'password', 'class' => 'input mediumfield')) ?>
		</div>
		<div class="clear"></div>
        <div class="input_field">
			<label for="c">Confirm Password</label>
            <?php echo $this->Form->password('confirm-password', array ('class' => 'input mediumfield')) ?>
		</div>
		<div class="clear"></div>
        <div class="input_field">
			<label for="c">Phone Number</label>
            <?php echo $this->Form->text('phone', array ('class' => 'input mediumfield')) ?>
		</div>
        <div class="clear"></div>
        <div class="input_field">
			<label for="c">&nbsp;</label>
            <?php echo $this->Html->image('/users/captcha_image/', array ('id' => 'captcha_image', 'style' => 'margin:10px 5px')) ?>
		</div>
        <div class="clear"></div>
        <div class="clear"></div>
        <div class="input_field">
			<label for="c">Code</label>
            <?php echo $this->Form->text('captcha', array ('class' => 'input smallfield', 'value' => '')) ?>
            <?php if (isset($invalidCaptcha)) : ?>
                <div class="validate_error">The code enter is invalid.</div>
            <?php endif; ?>
		</div>
		<div class="clear"></div><br />
		<p class="input_field">
			<label for="d">&nbsp;</label>
            <?php echo $this->Form->submit('Register', array('class' => 'submit', 'div' => false)) ?>
		</p>
	</fieldset>
<?php echo $this->Form->end(); ?>
