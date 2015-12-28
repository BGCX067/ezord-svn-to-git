<?php echo $this->Html->script('/js/jquery.timePicker.min.js') ?>
<?php echo $this->Html->script('/order/js/validate/reserve_add.js') ?>
<?php echo $this->Form->create('Reserve', array ('id' => 'ReserveAddForm', 'action' => $action.'/'.$pid, 'type' => 'file', 'method' => 'post')) ?>
	<?php echo $this->Form->hidden('id') ?>
    <?php echo $this->Form->hidden('place_id', array('value' => $pid)) ?>
    <fieldset>
		<div class="input_field">
			<label>Time</label>
			<?php echo $this->Form->text('time', array ('id' => 'time', 'class' => 'input mediumfield')) ?>
		</div>
		<div class="clear"></div>
        <div class="input_field">
            <label>Memo</label>
            <?php echo $this->Form->textarea('memo', array ('cols' => 50, 'class' => 'input textbox')) ?>
		</div>
        <div class="clear"></div>
		<p class="input_field">
            <br />
            <label>&nbsp;</label>
            <?php echo $this->Form->submit('Save', array('class' => 'submit', 'div' => false)) ?>
            <?php echo $this->Html->link('Cancel', '/order/reserves/index/'.$pid, array ('class' => 'button')) ?>
        </p>
	</fieldset>
<?php echo $this->Form->end() ?>
<script type="text/javascript">
$(function() {
    $("#time").timePicker({
        startTime: '06:00',
        endTime: '22:00',
        show24Hours: true,
        separator: ':',
        step: 15
    });
});
</script>