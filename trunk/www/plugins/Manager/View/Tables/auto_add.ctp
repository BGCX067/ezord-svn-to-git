<?php echo $this->Html->script('/manager/js/validate/table_auto_add.js') ?>
<?php echo $this->Form->create('Table', array ('id' => 'TableAutoAddForm', 'action' => 'auto_add/'.$pid, 'method' => 'post')) ?>
    <fieldset>
        <div class="input_field">
			<label>Number of tables</label>
			<?php echo $this->Form->text('number', array ('class' => 'input mediumfield')) ?>
		</div>
        <div class="clear"></div>
		<p class="input_field">
            <br />
            <label>&nbsp;</label>
            <?php echo $this->Form->submit('Submit', array('class' => 'submit', 'div' => false)) ?>
            <?php echo $this->Html->link('Cancel', '/manager/tables/index/'.$pid, array ('class' => 'button')) ?>
        </p>
	</fieldset>
<?php echo $this->Form->end() ?>