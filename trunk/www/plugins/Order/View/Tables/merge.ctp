<?php if (empty($tables)) : ?>
    <div class="notice">
        <span>No any available table</span>
        <p><?php echo $this->Html->link('Cancel', '/order/tables/view/'.$pid.'/'.$tid, array ('class' => 'button')) ?></p>
    </div>
<?php else : ?>
    <?php echo $this->Form->create('Table', array('action' => 'merge/'.$pid.'/'.$tid)) ?>
        <ul class="table-checks">
            <?php $i=1; foreach ($tables as $table) : ?>
                <?php $checked = (isset($table['Table']['checked'])) ? true : false; ?>
                <?php $last = ($i>0 && $i%5==0) ? ' class="last"' : ''; ?>
                <li<?php echo $last; ?>>
        			<?php echo $this->Form->checkbox('Table.'.$table['Table']['id'], array('checked' => $checked)) ?>
                    <span><?php echo $table['Table']['name'] ?></span>
        		</li>
            <?php $i++; endforeach; ?>
       	</ul>
        <div class="clear"><br /></div>
        <p class="right">
            <?php echo $this->Form->submit('Save', array ('div' => false, 'class' => 'button')); ?>
            <?php echo $this->Html->link('Cancel', '/order/tables/view/'.$pid.'/'.$tid, array ('class' => 'button')) ?>
        </p>
    <?php echo $this->Form->end(); ?>
<?php endif; ?>
<div class="clear"><br /></div>