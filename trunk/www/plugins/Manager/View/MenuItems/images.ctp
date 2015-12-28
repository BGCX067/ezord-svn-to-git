<?php echo $this->Form->create('MenuItem', array ('action' => 'images/'.$iid, 'type' => 'file', 'method' => 'post')) ?>
	<?php echo $this->Form->hidden('id') ?>
    <?php echo $this->Form->hidden('menu_item_id', array('value' => $iid)) ?>
    <div class="images">
        <h3 class="underline">Images</h3>
        <?php foreach ($item['ItemImage'] as $image) : ?>
            <?php echo $this->Html->image($image_path.'/'.$image['name']) ?>
        <?php endforeach; ?>
        <?php for($i=1; $i<=$remain; $i++) : ?>
            <?php echo $this->Html->image('no-image-small.png') ?>
        <?php endfor; ?>
        <hr />
    </div>
    <fieldset>
        <?php for ($i=1; $i<=$remain; $i++) : ?>
    		<div class="input_field">
    			<label>Image <?php echo $i ?></label>
    			<?php echo $this->Form->file('Upload.'.$i.'.file', array ('type' => 'file', 'class' => 'input mediumfield', 'style' => 'margin-top: 5px')) ?>
    		</div>
		    <div class="clear"></div>
        <?php endfor; ?>
		<p class="input_field">
            <br />
            <label>&nbsp;</label>
            <?php echo $this->Form->submit('Upload', array('class' => 'submit', 'div' => false)) ?>
            <?php echo $this->Html->link('Complete', '/manager/menu_items/index/', array ('class' => 'button')) ?>
            <?php echo $this->Html->link('Cancel', '/manager/menu_items/view/'.$iid, array ('class' => 'button')) ?>
        </p>
	</fieldset>
<?php echo $this->Form->end() ?>
