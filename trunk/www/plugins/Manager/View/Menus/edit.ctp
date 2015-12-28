<?php echo $this->Html->script('/manager/js/validate/menu_item_add.js') ?>
<?php echo $this->Form->create('MenuItem', array ('id' => 'MenuItemAddForm', 'action' => $action.'/'.$pid, 'type' => 'file', 'method' => 'post')) ?>
	<?php echo $this->Form->hidden('id') ?>
    <?php echo $this->Form->hidden('place_id', array('value' => $pid)) ?>
    <fieldset>
		<div class="input_field">
			<label>Item Name</label>
			<?php echo $this->Form->text('name', array ('class' => 'input mediumfield')) ?>
		</div>
		<div class="clear"></div>
        <div class="input_field">
			<label>Menu Item Group</label>
			<?php $gid = isset($gid) ? $gid : null; ?>
            <?php $group_options = $this->requestAction('/manager/menu_item_groups/getOptionMenuItemGroups/'.$pid) ?>
            <?php echo $this->Form->select('menu_item_group_id', $group_options, array ('value' => $gid, 'class' => 'input mediumfield')) ?>
		</div>
		<div class="clear"></div>
        <div class="input_field">
			<label>Price</label>
			<?php echo $this->Form->text('price', array ('class' => 'input mediumfield')) ?>
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
            <?php if (isset($iid)) : ?>
                <?php echo $this->Html->link('Cancel', '/manager/menu_items/view/'.$pid.'/'.$iid, array ('class' => 'button')) ?>
            <?php else :  ?>
                <?php echo $this->Html->link('Cancel', '/manager/menu_items/index/'.$pid, array ('class' => 'button')) ?>
            <?php endif; ?>
        </p>
	</fieldset>
<?php echo $this->Form->end() ?>