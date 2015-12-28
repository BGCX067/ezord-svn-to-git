<?php echo $this->Form->create('Payment', array('action' => 'pay/'.$plan_type)) ?>
    <table cellspacing="0" cellpadding="0">
    	<thead>
    		<tr>
    			<th>Plan Name</th>
    			<th>
    				<?php
    					if ($plan_type == 'D') {
    						echo 'Expired Date';
    					} else {
    						echo 'Quantity (checkouts)';
    					}
    				?>
    			</th>
    			<th>Amount (USD)</th>
    		</tr>
    	</thead>
    	<tbody>
            <tr>
    			<td><?php echo $package['name']; ?></td>
    			<td>
                    <?php 
                        if ($plan_type=='D') {
                            $today = strtotime(date('Y-m-d H:i:s'));
                            $expired = strtotime('+3 months', $today);
                            $expired = date('dS M, Y', $expired);
                            echo $expired;
                        } else {
                            echo $package['quantity'];
                        }
                    ?>
                </td>
    			<td><strong>$<?php echo $package['price'] ?></strong></td>
    		</tr>
    	</tbody>
    </table>
    <hr />
    <div class="clear"></div>
    <div class="right">
        <strong>Total: <?php echo $package['price'] ?> USD</strong> &nbsp;
        <?php echo $this->Form->hidden('price', array ('value' => $package['price'])); ?>
    </div>
    <div class="clear"></div>
    <div class="left">
        <?php echo $this->Html->image('paypal.png'); ?>
    </div>
    <div class="right" style="margin-top: 40px;">
        <?php echo $this->Form->button('Confirm', array('div' => false, 'submit' => false, 'class' => 'button', 'id' => 'confirm')) ?>
    	<?php echo $this->Html->link('Cancel', '/manager/payments/index/', array('class' => 'button')) ?>
    </div>
    <div class="clear"><br /></div>
<?php echo $this->Form->end(); ?>