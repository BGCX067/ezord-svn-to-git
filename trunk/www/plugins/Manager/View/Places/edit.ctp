<?php echo $this->Html->script('/manager/js/validate/place_add.js') ?>
<?php echo $this->Form->create('Place', array ('action' => $action, 'type' => 'file', 'method' => 'post')) ?>
	<?php echo $this->Form->hidden('id') ?>
    <fieldset>
		<div class="input_field">
			<label>Place name</label>
			<?php echo $this->Form->text('name', array ('class' => 'input mediumfield')) ?>
		</div>
		<div class="clear"></div>
		<div class="input_field">
			<label>Slogan</label>
            <?php echo $this->Form->text('slogan', array ('class' => 'input mediumfield')) ?>
		</div>
        <div class="input_field">
            <label>Description</label>
            <?php echo $this->Form->textarea('description', array ('cols' => 50, 'class' => 'input textbox')) ?>
		</div>
        <div class="clear"></div>
		<p class="input_field">
            <br />
            <label>&nbsp;</label>
            <?php echo $this->Form->submit('Save', array('class' => 'submit', 'div' => false)) ?>
            <?php echo $this->Html->link('Cancel', '/manager/places/', array('class' => 'button'))?>
        </p>
	</fieldset>
<?php echo $this->Form->end() ?>
