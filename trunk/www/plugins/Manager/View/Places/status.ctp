<?php echo $this->Form->create('Place', array('action' => $action.'/'.$pid)) ?>
    <?php echo $this->Form->hidden('id', array ('value' => $pid)) ?>
    <div class="notice">
        <span>Are you sure you want to <?php echo $action ?>?</span>
        <p>
            <?php echo $this->Form->submit(ucfirst($action), array ('class' => 'submit', 'div' => false)) ?>
            <?php echo $this->Html->link('Cancel', '/manager/places/dashboard/'.$pid) ?>
        </p>
    </div>
<?php echo $this->Form->end(); ?>