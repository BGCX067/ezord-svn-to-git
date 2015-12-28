<?php echo $this->Form->create('User', array ('action' => 'login', 'method' => 'post')) ?>
    <fieldset>
		<div class="input_field">
			<label for="b">Email</label>
            <?php echo $this->Form->text('email', array ('class' => 'input mediumfield')) ?>
		</div>
		<div class="clear"></div>
		<div class="input_field">
			<label for="c">Password</label>
            <?php echo $this->Form->password('password', array ('class' => 'input mediumfield')) ?>
		</div>
		<div class="clear"></div>
        <br clear="all" />
		<p class="input_field">
			<label for="d">&nbsp;</label>
            <?php echo $this->Form->submit('Login', array('class' => 'submit', 'div' => false)) ?> | 
            <?php echo $this->Html->link('Forgot your password?', '#') ?>
		</p><p>&nbsp;</p>
		<div class="clear"></div>
	</fieldset>
<?php echo $this->Form->end(); ?>
