<?php echo $this->Html->script('/manager/js/validate/assign_place.js') ?>
<?php if (empty($places)) : ?>
    <div class="notice">
        <span>No place available</span><br />
        <p><?php echo $this->Html->link('Add a place', '/manager/places/add/', array ('class' => 'button')) ?></p>
    </div>
<?php else : ?>
    <?php echo $this->Form->create('UserMember', array('id' => 'MemberAssignForm', 'action' => 'assign/'.$mid)) ?>
        <fieldset>   
    		<div class="input_field">
    			<label>Place</label>
    			<?php echo $this->Form->select('place_id', $places, array ('class' => 'input mediumfield', 'value' => $pid)) ?>
    		</div>
            <div class="clear"><br /></div>
            <div class="input_field">
    			<label>&nbsp;</label>
                <div>
                    <strong>Permit to</strong>
                    <hr style="margin-left: 145px; width: 200px;" />
                </div>
            </div>
            <div class="input_field">
    			<label>&nbsp;</label>
                <div style="margin-left: 145px; width: 200px;">
                    <!--
        			 <?php echo $this->Form->checkbox('permit_order') ?> Manage Place <br />
                    -->
                    <?php echo $this->Form->checkbox('permit_report') ?> View Report <br />
                    <?php echo $this->Form->checkbox('permit_manager') ?> Change Settings 
                    <hr />
                </div>
    		</div>
            <div class="clear"></div>
    		<p class="input_field">
                <br />
                <label>&nbsp;</label>
                <?php echo $this->Form->submit('Save', array('class' => 'submit', 'div' => false)) ?>
                <?php echo $this->Html->link('Cancel', '/manager/user_members/view/'.$mid, array ('class' => 'button')) ?>
            </p>
    	</fieldset>
    <?php echo $this->Form->end(); ?>
<?php endif; ?>
<div class="clear"><br /></div>