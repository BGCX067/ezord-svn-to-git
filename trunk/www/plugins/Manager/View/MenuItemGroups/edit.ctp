<?php echo $this->Html->script('/manager/js/validate/item_group_add.js') ?>
<?php echo $this->Form->create('MenuItemGroup', array ('id' => 'MenuItemGroupAddForm', 'action' => $action, 'type' => 'file', 'method' => 'post')) ?>
	<?php echo $this->Form->hidden('id') ?>
    <fieldset>
		<div class="input_field">
			<label>Item Group Name</label>
			<?php echo $this->Form->text('name', array ('class' => 'input mediumfield')) ?>
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
            <?php echo $this->Form->submit('Save', array('class' => 'submit', 'div' => false)) ?>
            <?php echo $this->Html->link('Cancel', '/manager/menu_items/index/', array ('class' => 'button')) ?>
        </p>
	</fieldset>
<?php echo $this->Form->end() ?>