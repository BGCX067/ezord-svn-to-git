<?php echo $this->Form->create('Table', array('action' => 'delete/'.$pid)) ?>
    <?php echo $this->Form->hidden('id', array ('value' => $tid)) ?>
    <?php echo $this->Form->hidden('place_id', array ('value' => $pid)) ?>
    <div class="notice">
        <span>Are you sure you want to delete?</span>
        <p>
            <?php echo $this->Form->submit('Delete', array ('class' => 'submit', 'div' => false)) ?>
            <?php echo $this->Html->link('Cancel', '/manager/tables/index/'.$pid) ?>
        </p>
    </div>
<?php echo $this->Form->end(); ?>