<?php echo $this->Html->script('/manager/js/validate/menu_item_price.js') ?>
<?php echo $this->Html->script('/js/jquery.timePicker.min.js') ?>
<div id="custom_price">
	<h3><?php echo $item['MenuItem']['price'] ?></h3>
	<p>Standard price</p>
	<?php if(!empty($prices)) : ?>
		<?php foreach ($prices as $i => $price) : ?>
			<h3 class="custom" id="<?php echo 'price_'.$price['MenuItemPrice']['id'] ?>"><?php echo $price['MenuItemPrice']['price'] ?></h3>
			<ul>
				<li>
					<?php echo $this->Html->image('delete_s.png'); ?>
					<?php echo $this->Html->link('Delete', 'javascript:void(0);', array('class' => 'delete_price', 'id' => $price['MenuItemPrice']['id'])) ?>
				</li>
			</ul>
			<div class="clear"></div>
			<p>From <?php echo $price['MenuItemPrice']['time_from'] ?> to <?php echo $price['MenuItemPrice']['time_to'] ?> </p>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php echo $this->Form->create('MenuItemPrice', array('id' => 'MenuItemPriceForm', 'action' => 'price/'.$iid)) ?>
		<fieldset>
			<h3>Add more price</h3>
			<div class="input_field">
	    		<label>Price</label>
	    		<?php echo $this->Form->text('MenuItemPrice.price', array('class' => 'input mediumfield')) ?>
	    	</div>
	    	<div class="input_field">
	    		<label>Time from</label>
	    		<?php echo $this->Form->text('MenuItemPrice.time_from', array('class'=>'input mediumfield time')) ?>
	    	</div>
	    	<div class="input_field">
	    		<label>Time to</label>
	    		<?php echo $this->Form->text('MenuItemPrice.time_to', array('class'=>'input mediumfield time')) ?>
	    	</div>
	    	<p class="input_field">
	            <br />
	            <label>&nbsp;</label>
	            <?php echo $this->Form->submit('Save', array('class' => 'submit', 'div' => false)) ?>
	            <?php echo $this->Html->link('Cancel', '/manager/menu_items/view/'.$iid, array ('class' => 'button')) ?>
	        </p>
	    </fieldset>
	<?php echo $this->Form->end(); ?>
	<div class="clear"></div>
</div>
<div class="clear"></div>
<script type="text/javascript">
var BASE_URL = '<?php echo $this->base ?>';
var iid = '<?php echo $iid ?>';
$(function() {
    $(".time").timePicker({
        startTime: '06:00',
        endTime: '22:00',
        show24Hours: true,
        separator: ':',
        step: 60
    });
    $('.delete_price').click(function(){
    	var prid = $(this).attr('id');
        $('#price_'+prid).css('color', 'red');
        $('#price_'+prid).css('text-decoration', 'line-through');
        if (confirm('Are you sure?')) {
            $.post(BASE_URL+'/manager/menu_item_prices/delete/'+prid, {'prid': prid}, function(data) {
                if (data=='true') {
                    window.location = BASE_URL+'/manager/menu_item_prices/price/'+iid;
                } else {
                    alert ('Delete is failed! Please try again.');
                }
            });
        } else {
            $('#price_'+prid).css('color', '#686C69');
            $('#price_'+prid).css('text-decoration', 'none');
        }
    });
});
</script>
