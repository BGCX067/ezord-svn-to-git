<div class="refresh">
    <a href="javascript:void(0);" title="Refresh" onclick="window.location.reload()">
        <?php echo $this->Html->image('refresh.png') ?>
    </a>
</div>
<?php if (!empty($tables)) : ?>
    <?php $table_str = $table_num>1 ? ' tables' : ' table'; ?>
    <div id="links-bar-left" align="left">
        <strong>There are </strong>
        <a href="javascript:void(0);" class="all-tables"><?php echo $table_num.$table_str ?></a> 
        <?php if ($taken_num>0) : ?>
            <strong>including:</strong>
            <a href="javascript:void(0);" class="available-tables"><?php echo $available_num; ?> available</a>
            <strong>and</strong>
            <a href="javascript:void(0);" class="taken-tables"><?php echo $taken_num?> taken</a>
        <?php else: ?>
            <strong>in total</strong>
        <?php endif; ?>
    </div>
    <div id="links-bar-right">
        <?php echo $this->Html->image('reserve_s.png') ?>
        <?php if ($reserve_num>0) : ?>
            <?php echo $this->Html->link('Reserve ('.$reserve_num.')', '/order/reserves/index/'.$pid) ?>
        <?php else : ?>
            <?php echo $this->Html->link('Reserve', '/order/reserves/index/'.$pid) ?>
        <?php endif; ?>
    </div>
    <div class="clear"></div>
    <ul id="tables">
        <?php foreach ($tables as $table) : ?>
            <?php $class = empty($table['Order']) ? 'available' : 'taken'; ?>
            <li class="<?php echo $class; ?>">
                <?php if ($table['Table']['merged_tables_count']>0) : ?>
                    <div class="table-back <?php echo $class; ?>">
                <?php endif; ?>
                    <div class="table <?php echo $class; ?>">
                        <strong>
                            <?php echo $this->Html->link($table['Table']['name'], '/order/tables/view/'.$pid.'/'.$table['Table']['id']) ?>
                            <?php if ($table['Table']['merged_tables_count']>0) : ?>
                                and ... 
                            <?php endif; ?>
                        </strong>
                        <div class="table-icons">
                            <?php if (empty($table['Order'])) : ?>
                                <?php if (empty($table['Reserve'])) : ?>
                                    <?php $cover_str = $table['Table']['cover']<=1 ? 'cover' : 'covers'; ?>
                                    <p>Free (<?php echo $table['Table']['cover'] .' '.$cover_str ?>)</p>
                                    <a href="<?php echo $this->base.'/order/orders/place/'.$pid.'/'.$table['Table']['id'] ?>" title="Order">
                                        <?php echo $this->Html->image('order.png', array('width' => 25)) ?>
                                    </a>
                                <?php else : ?>
                                    <div class="reserved">
                                        Reserved <?php echo $this->Time->niceShort($table['Reserve'][0]['time']); ?> <br />
                                        <?php echo $table['Reserve'][0]['memo'] ?>
                                    </div>
                                <?php endif; ?>
                            <?php else : ?>
                                <?php $order_str = count($table['Order'])>1 ? 'orders' : 'order'; ?>
                                <?php $time = get_time_passed($table['Order'][0]['created']); ?>
                                <?php $h_str = ($time['h']>1) ? 'hrs' : 'hr'; ?>
                                <?php $i_str = ($time['i']>1) ? 'mins' : 'min'; ?>
                                <div class="ordered">
                                    <?php echo count($table['Order']) .' '. $order_str; ?><br />
                                    (<?php echo $time['h'].' '.$h_str.' '.$time['i'].' '.$i_str; ?>)
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php if ($table['Table']['merged_tables_count']>0) : ?>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="clear"></div>
    <p>&nbsp;</p>
<?php else : ?>
    <div class="notice">
        <span>You have no table available!</span>
        <p>Go to settings page to setup.</p>
        <p>&nbsp;</p>
        <p><?php echo $this->Html->link('Settings', '/manager/places/dashboard/'.$pid, array ('class' => 'button')) ?></p>
    </div>
<?php endif; ?>
<script>
$(function() {
   $('.all-tables').click(function(){
        $('ul#tables li').hide();
        $('ul#tables li').fadeIn();
   }); 
   $('.available-tables').click(function(){
        $('ul#tables li').hide();
        $('ul#tables li.available').fadeIn(300);
   });
   $('.taken-tables').click(function(){
        $('ul#tables li').hide();
        $('ul#tables li.taken').fadeIn(300);
   })
});
</script>