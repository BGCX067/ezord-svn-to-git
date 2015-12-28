<?php if (empty($places)) : ?>
	<div class="notice">
        <span>You have no menu available!</span>
        <p>&nbsp;</p>
    </div>
<?php else: ?>
	<?php echo $this->Form->create('MenuItem', array('action' => 'copy/'.$pid, 'method' => 'post')) ?>
		<ul class="radio_places">
			<?php foreach ($places as $place) : ?>
				<li>
					<input type="radio" name="data[MenuItem][pid_from]" value="<?php echo $place['Place']['id'] ?>" />
					<?php echo $place['Place']['name']; ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="clear"><br /></div>
        <p class="right">
            <?php echo $this->Form->submit('Copy', array ('div' => false, 'class' => 'button')); ?>
            <?php echo $this->Html->link('Cancel', '/manager/menu_items/index/'.$pid, array ('class' => 'button')) ?>
        </p>
	<?php echo $this->Form->end(); ?>
<?php endif;?>
<p>&nbsp;</p>
<div class="clear"></div>