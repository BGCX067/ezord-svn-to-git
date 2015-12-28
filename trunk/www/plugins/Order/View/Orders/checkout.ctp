<?php echo $this->Form->create('Order', array('action' => 'checkout/'.$pid.'/'.$tid)) ?>
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
            <?php foreach ($items as $item) : ?>
                <tr>
        			<td><?php echo $item['name'] ?></td>
        			<td><?php echo $item['price'] ?></td>
        			<td><?php echo $item['MenuItemsOrder']['quantity']; ?></td>
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
    <p>&nbsp;</p>
    <div class="clear"></div>
    <p class="right">
        <?php echo $this->Html->link('Print Bill', '#', array('class' => 'button')) ?>
        <?php echo $this->Form->submit('Confirm paid', array('div' => false, 'submit' => false, 'class' => 'button', 'id' => 'update')) ?>
        <?php echo $this->Html->link ('Cancel', '/order/tables/index/'.$pid, array ('class' => 'button')) ?>
    </p>
    <div class="clear"><br /></div>
<?php echo $this->Form->end(); ?>