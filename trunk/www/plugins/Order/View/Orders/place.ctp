<script>
var BASE_URL='<?php echo $this->base ?>';
var pid = '<?php echo $pid; ?>';
</script>
<?php echo $this->Html->script('/order/js/order.js') ?>
<?php if (!empty($groups)) : ?>
    <?php if (!$is_permit_order) : ?>
        <div class="error-p">Your place has been reached the order limit. Please <a href="#">upgrade</a> to a new plan</div>
    <?php endif; ?>
    <div id="menu_order">
        <p>
            <?php echo $this->Form->text('search', array('class' => 'input mediumfield') )?>
            <?php echo $this->Form->button('Search', array('div' => false, 'submit' => false, 'id' => 'search_menu_items', 'class' => 'button')) ?> &nbsp;
        </p>
        <?php echo $this->Form->create('Order', array('action' => 'place/'.$pid.'/'.$tid.'/'.$oid)) ?>
            <?php echo $this->Form->hidden('order_items', array ('id' => 'order_items', 'value' => '')); ?>
            <div class="clear"></div>
                <div id="list_items">
                    <?php foreach ($groups as $group) : ?>
                        <?php if (empty($group['MenuItem'])) : ?>
                            <?php continue; ?>
                        <?php else : ?>
                            <h3 class="underline menu_item_group"><?php echo $group['MenuItemGroup']['name'] ?> <span><?php echo $group['MenuItemGroup']['description'] ?></span> </h3>
                            <ul class="images">
                                <?php foreach ($group['MenuItem'] as $item) : ?>
                                    <?php echo $this->Form->checkbox('item', array('id' => 'cb_'.$item['id'])) ?>
                                    <li>
                                        <?php if (empty($item['ItemImage'])) : ?>
                                            <a href="javascript:void(0)" id="<?php echo $item['id']; ?>">
                                                <?php echo $this->Html->image('no-image-small.png') ?>
                                            </a>
                                        <?php else : ?>
                                            <?php $image_path = $this->Upload->getUploadUrlPath('small', $cManager['id'], $item['id']); ?>
                                            <a href="javascript:void(0)" id="<?php echo $item['id']; ?>">
                                                <?php echo $this->Html->image($image_path.'/'.$item['ItemImage'][0]['name']); ?>
                                            </a>
                                        <?php endif; ?>
                                        <p id="<?php echo 'name_'.$item['id']; ?>"><?php echo $item['name'] ?></p>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <div class="clear"></div>
                    <?php endforeach; ?>
                </div>
            <div class="clear"></div>
            <?php if ($is_permit_order) : ?>
                <div id="note_orders">
                    <h3 class="underline" style="margin: 8px">Order Items</h3>
                    <ul></ul>
                    <p class="right" style="margin-right: 10px;"> 
                        <?php echo $this->Form->submit('Confirm', array('id' => 'confirm_order', 'div' => false, 'class' => 'button')) ?>
                        <?php echo $this->Html->link('Cancel', '/order/tables/index/'.$pid, array ('class' => 'button')) ?>
                    </p>
                </div> 
            <?php endif; ?>
        <?php echo $this->Form->end(); ?>
    </div>
<?php else : ?>
    <div class="notice">
        <span>You have no menu items</span>
        <p>Go to foods &amp; drinks page to setup</p>
        <p>&nbsp;</p>
        <p><?php echo $this->Html->link('Foods & Drinks', '/manager/menu_items/index/', array ('class' => 'button')) ?></p>
    </div>
<?php endif; ?>

