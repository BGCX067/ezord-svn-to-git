<div id="merge_tables">
    <a href="<?php echo $this->base.'/order/tables/merge/'.$pid.'/'.$tid; ?>">
        <?php echo $this->Html->image('merge_tables.png') ?>
    </a>
</div>
<?php if (empty($table['Order'])) : ?>
    <div class="notice">
        <span>This table is empty.</span>
        <p>&nbsp;</p>
        <p>
            <?php echo $this->Html->link('Place an order', '/order/orders/place/'.$pid.'/'.$tid, array ('class' => 'button')) ?>
        </p>
    </div>
<?php else : ?>
    <?php foreach ($orders as $order) : ?>
        <div id="<?php echo 'order_'.$order['id'] ?>">
            <h3 class="underline left" style="width: 76%"><?php echo $this->Time->niceShort($order['created']) ?></h3>
            <div class="order_icons">
                <ul class="bar_icons">
                     <li>
                        <a href="<?php echo $this->base.'/order/tables/move/'.$pid.'/'.$tid.'/'.$order['id'] ?>" title="Move">
                            <?php echo $this->Html->image('move_s.png') ?> Move
                        </a>
                     </li>
                     <li>
                        <a href="javascript:void(0)" class="delete_order" id="<?php echo $order['id'] ?>" title="Delete">
                            <?php echo $this->Html->image('delete_s.png') ?> Delete
                        </a>
                     </li>
                </ul>
            </div>
            <div class="clear"></div>
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
        </div>
        <div class="clear"><br /></div>
    <?php endforeach; ?>
    <p class="right">
        <?php echo $this->Html->link('Order more', '/order/orders/place/'.$pid.'/'.$tid, array ('class' => 'button')) ?>
        <?php echo $this->Html->link('Checkout', '/order/orders/checkout/'.$pid.'/'.$tid, array ('class' => 'button'))?>
    </p>
    <div class="clear"><br /></div>
<?php endif; ?>
<script>
var BASE_URL = '<?php echo $this->base; ?>';
var pid = '<?php echo $pid; ?>';
var tid = '<?php echo $tid; ?>';
$(function() {
    $('.order_icons a.delete_order').click(function() {
        var oid = $(this).attr('id');
        $('#order_'+oid+' h3.underline').css('color', 'red');
        $('#order_'+oid+' h3.underline').css('text-decoration', 'line-through');
        if (confirm('Are you sure?')) {
            $.post(BASE_URL+'/order/orders/delete/'+pid+'/'+tid+'/'+oid, {}, function(data) {
                if (data!='false') {
                    var number = data.split(',',2).pop();
                    if (number>0)
                        $('#order_'+oid).fadeOut(300);
                    else 
                        window.location = BASE_URL+'/order/tables/index/'+pid;
                }
            });
        } else {
            $('#order_'+oid+' h3.underline').css('color', '#686868');
            $('#order_'+oid+' h3.underline').css('text-decoration', 'none');
        }
    });
});
</script>
