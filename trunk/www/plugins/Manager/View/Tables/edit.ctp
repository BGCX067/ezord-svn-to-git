<?php echo $this->Html->script('/manager/js/validate/table_add.js') ?>
<?php echo $this->Form->create('Table', array ('id' => 'TableAddForm', 'action' => $action.'/'.$pid, 'type' => 'file', 'method' => 'post')) ?>
	<?php echo $this->Form->hidden('id') ?>
    <?php echo $this->Form->hidden('place_id', array('value' => $pid)) ?>
    <fieldset>
		<div class="input_field">
			<label>Table Name</label>
			<?php echo $this->Form->text('name', array ('class' => 'input mediumfield')) ?>
		</div>
		<div class="clear"></div>
        <div class="input_field">
            <label>Cover</label>
            <?php for ($i=1; $i<10; $i++) { $c_opt[$i] = $i; } ?>
            <?php echo $this->Form->select('cover', $c_opt, array ('class' => 'input smallfield')) ?>
        </div>
        <div class="clear"></div>
        <div class="input_field">
            <label>Description</label>
            <?php echo $this->Form->textarea('description', array ('cols' => 50, 'class' => 'input textbox')) ?>
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
