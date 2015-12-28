<?php if (empty($tables)) : ?>
    <div class="notice">
        <span>No any available table</span>
        <p><?php echo $this->Html->link('Cancel', '/order/reserves/index/'.$pid, array ('class' => 'button')) ?></p>
    </div>
<?php else : ?>
    <?php echo $this->Form->create('Reserve', array('action' => 'assign/'.$pid.'/'.$rid)) ?>
        <ul class="table-checks">
            <?php foreach ($tables as $table) : ?>
                <?php $checked = ($table['Table']['id']==$tid) ? true : false ?>
                <li>
                    <input type="radio" name="data[Reserve][table_id]" value="<?php echo $table['Table']['id'] ?>"<?php if ($checked) echo ' checked="checked"' ?> />
                    <span><?php echo $table['Table']['name'] ?></span>
        		</li>
            <?php endforeach; ?>
       	</ul>
        <div class="clear"><br /></div>
        <p class="right">
            <?php echo $this->Form->submit('Assign', array ('div' => false, 'class' => 'button')); ?>
            <?php echo $this->Html->link('Cancel', '/order/reserves/index/'.$pid, array ('class' => 'button')) ?>
        </p>
    <?php echo $this->Form->end(); ?>
<?php endif; ?>
<div class="clear"><br /></div>