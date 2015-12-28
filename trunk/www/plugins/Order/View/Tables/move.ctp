<?php if (empty($tables)) : ?>
    <div class="notice">
        <span>No any available table</span>
        <p><?php echo $this->Html->link('Cancel', '/order/tables/view/'.$pid.'/'.$tid, array ('class' => 'button')) ?></p>
    </div>
<?php else : ?>
    <?php echo $this->Form->create('Table', array('action' => 'move/'.$pid.'/'.$tid.'/'.$oid)) ?>
        <ul class="table-checks">
            <?php foreach ($tables as $table) : ?>
                <li>
                    <input type="radio" name="data[Table]" value="<?php echo $table['Table']['id'] ?>" />
                    <span><?php echo $table['Table']['name'] ?></span>
        		</li>
            <?php endforeach; ?>
       	</ul>
        <div class="clear"><br /></div>
        <p class="right">
            <?php echo $this->Form->submit('Move', array ('div' => false, 'class' => 'button')); ?>
            <?php echo $this->Html->link('Cancel', '/order/tables/view/'.$pid.'/'.$tid, array ('class' => 'button')) ?>
        </p>
    <?php echo $this->Form->end(); ?>
<?php endif; ?>
<div class="clear"><br /></div>