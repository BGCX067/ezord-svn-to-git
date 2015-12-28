<?php echo $this->Form->create('UserMember', array ('action' => 'avatar/'.$mid, 'type' => 'file', 'method' => 'post')) ?>
<div id="upload_avatar">
    <div class="avatar">
        <?php if (!empty($member['Member']['UserProfile']['avatar'])) : ?>
            <?php echo $this->Html->image($image_path.'/'.$member['Member']['UserProfile']['avatar']) ?>
            <hr />
            <span>
                <?php echo $this->Html->image('delete_s.png', array('class' => 'icon')) ?>
                <?php echo $this->Html->link('Delete this avatar', '/manager/user_members/delete_avatar/'.$mid, array(), 'Are you sure?') ?>
            </span>
        <?php else: ?>
            <?php echo $this->Html->image('no-image-small.png') ?>
            <hr />
        <?php endif; ?>
    </div>
    <fieldset>
		<div class="input_field">
			<label>Avatar</label>
			<?php echo $this->Form->file('Upload.file', array ('type' => 'file', 'class' => 'input mediumfield', 'style' => 'margin-top: 5px')) ?>
		</div>
	    <div class="clear"></div>
		<p class="input_field">
            <br />
            <label>&nbsp;</label>
            <?php echo $this->Form->submit('Upload', array('class' => 'submit', 'div' => false)) ?>
            <?php echo $this->Html->link('Cancel', '/manager/user_members/view/'.$mid, array ('class' => 'button')) ?>
        </p>
	</fieldset>
</div>
<?php echo $this->Form->end() ?>