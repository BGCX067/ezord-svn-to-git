<?php echo $this->Form->create('Order', array('action' => 'confirm/'.$pid.'/'.$tid.'/'.$oid)) ?>
    <?php echo $this->Form->hidden('action', array('id' => 'action', 'value' => '')) ?>
    <table cellspacing="0" cellpadding="0">
    	<thead>
    		<tr>
    			<th>Item</th>
    			<th>Price</th>
    			<th>Quantity</th>
    			<th>Amount</th>
    		</tr>
    	</thead>
    	<tbody>
            <?php $total = 0; ?>
            <?php foreach ($order['MenuItem'] as $item) : ?>
                <tr>
        			<td><?php echo $item['name'] ?></td>
        			<td><?php echo $item['price'] ?></td>
        			<td><?php echo $this->Form->text('Order.quantity.'.$item['id'], array('class' => 'input smallfield', 'value' => $item['MenuItemsOrder']['quantity'])) ?></td>
        			<td><?php echo $item['price']*$item['MenuItemsOrder']['quantity'] ?></td>
        		</tr>
                <?php $total += $item['price']*$item['MenuItemsOrder']['quantity']; ?>
            <?php endforeach; ?>
    	</tbody>
    </table>
    <hr />
    <div class="clear"></div>
    <div class="right">
        <strong>Total: <?php echo $total; ?></strong> &nbsp;
    </div>
    <div class="clear"></div>
    <p>&nbsp;</p>
    <div class="clear"></div>
    <p class="right">
        <?php echo $this->Html->link ('Add more item', '/order/orders/place/'.$pid.'/'.$tid.'/'.$oid,array ('class' => 'button')) ?>
        <?php echo $this->Form->button('Update', array('div' => false, 'submit' => false, 'class' => 'button', 'id' => 'update')) ?>
        <?php echo $this->Form->button('Confirm', array('div' => false, 'submit' => false, 'class' => 'button', 'id' => 'confirm')) ?>
    </p>
    <div class="clear"><br /></div>
<?php echo $this->Form->end(); ?>
<script>
$(function() {
    $('#update').click(function(){
        $('#action').val('update');
    });
    $('#confirm').click(function(){
        $('#action').val('confirm');
    });
});
</script>