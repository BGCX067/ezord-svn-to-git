<div id="links-bar">
    <strong>Income:</strong> &nbsp;
    <?php echo $this->Html->link('Today', '/report/places/dashboard/'.$pid.'/today/') ?> &nbsp;
    <?php echo $this->Html->link('Yesterday', '/report/places/dashboard/'.$pid.'/yesterday/') ?>  &nbsp;
    <?php echo $this->Html->link('7 days', '/report/places/dashboard/'.$pid.'/7-days/') ?>  &nbsp;
    <?php echo $this->Html->link('30 days', '/report/places/dashboard/'.$pid.'/30-days/') ?>  &nbsp;
    <?php echo $this->Html->link('3 months', '/report/places/dashboard/'.$pid.'/3-months/') ?>  &nbsp;
</div>
<?php if(!empty($items)) : ?>
    <h3 class="right"><?php echo $income_str; ?></h3>
    <div class="clear"></div>
    <table cellspacing="0" cellpadding="0">
    	<thead>
    		<tr>
    			<th>Item</th>
    			<th>Price</th>
    			<th>Quantity</th>
    			<th>Amount</th>
                <th>&nbsp;</th>
    		</tr>
    	</thead>
    	<tbody>
            <?php $total = 0; ?>
            <?php foreach ($items as $item) : ?>
                <tr>
        			<td><?php echo $item['name'] ?></td>
        			<td><?php echo $item['price'] ?></td>
        			<td><?php echo $item['quantity'] ?></td>
        			<td><?php echo $item['price']*$item['quantity'] ?></td>
                    <td width="80px">
                        <?php echo $this->Html->image('chart_s.png') ?>
                        <?php echo $this->Html->link('View', '#') ?>
                    </td>
        		</tr>
                <?php $total += $item['price']*$item['quantity']; ?>
            <?php endforeach; ?>
    	</tbody>
    </table>
    <hr />
    <div class="clear"></div>
    <div class="right">
        <h3>Total: <?php echo $total; ?></h3> &nbsp;
    </div>
    <div class="clear"></div>
<?php else: ?>
    <div class="notice">
        <span>No income <?php echo strtolower($income_str) ?></span>
    </div>
<?php endif; ?>