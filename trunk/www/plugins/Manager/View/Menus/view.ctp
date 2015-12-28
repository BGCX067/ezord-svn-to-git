<?php echo $this->Html->script('/manager/js/validate/table_add.js') ?>
<?php echo $this->Form->create('MenuItem', array ('action' => 'view', 'type' => 'file', 'method' => 'post')) ?>
	<?php echo $this->Form->hidden('id') ?>
    <?php echo $this->Form->hidden('place_id', array('value' => $pid)) ?>
    <?php echo $this->Form->hidden('menu_item_id', array('value' => $item['MenuItem']['id'])) ?>
    
    <div id="image-view" class="left">
        <?php if (empty($item['ItemImage'])) : ?>
            <?php echo $this->Html->image('no-image.png') ?>
        <?php else: ?>
            <a href="javascript:void(0)" id="slider" class="left">
                <?php $image_path = $this->Upload->getUploadUrlPath('medium', $cManager['id'], $pid, $iid) ?>
                <?php foreach ($item['ItemImage'] as $i => $image) : ?>
                    <?php $options = $i==0 ? array ('class' => 'active') : array (); ?>
                    <?php echo $this->Html->image($image_path.'/'.$image['name'], $options) ?>
                <?php endforeach; ?>
            </a>
        <?php endif; ?>
    </div>
    
    <div id="item-view" class="right">
        <h3 class="underline"><?php echo $item['MenuItemGroup']['name'] .' - '. $item['MenuItem']['name']; ?></h3>
        <?php if (!empty($item['MenuItem']['description'])) : ?>
            <p><?php echo $item['MenuItem']['description']; ?></p>
        <?php endif; ?>
        <?php if (empty($item['MenuItemPrice'])) : ?>
            <h4>Price: <?php echo $item['MenuItem']['price']; ?></h4>
        <?php else: ?>
            <h4>Price:</h4>
            <ul class="normal">
                <li><strong><?php echo $item['MenuItem']['price']; ?></strong> - Standard price</li>
                <?php foreach($item['MenuItemPrice'] as $price) : ?>
                    <li><strong><?php echo $price['price']?></strong> - From <?php echo $price['time_from'] ?> to <?php echo $price['time_to'] ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <div class="clear"></div>
        <hr />
        <ul>
            <li>
                <?php echo $this->Html->image('pictures_s.png')?> 
                <?php echo $this->Html->link('Image', '/manager/menu_items/images/'.$pid.'/'.$iid) ?>
            </li>
            <li>
                <?php echo $this->Html->image('custom_price_s.png')?> 
                <?php echo $this->Html->link('Custom price', '/manager/menu_item_prices/price/'.$pid.'/'.$iid) ?>
            </li>
            <li>
                <?php echo $this->Html->image('edit_s.png')?> 
                <?php echo $this->Html->link('edit', '/manager/menu_items/edit/'.$pid.'/'.$iid) ?>
            </li>
            <li>
                <?php echo $this->Html->image('delete_s.png')?> 
                <?php echo $this->Html->link('delete', 'javascript:void(0)', array ('id' => 'delete_item')) ?>
            </li>
        </ul>
        <div class="clear"></div>
        <p>&nbsp;</p>
        <?php echo $this->Html->link('Cancel', '/manager/menu_items/index/'.$pid, array ('class' => 'button')) ?>
	</div>
    
    <div class="clear"></div>
<?php echo $this->Form->end() ?>
<script type="text/javascript">
var BASE_URL = '<?php echo $this->base; ?>';
var pid = '<?php echo $pid; ?>';
var iid = '<?php echo $iid; ?>';
$(function() {
    $('#slider img').click(function(){
        slideSwitch();
    });
    $('#delete_item').click(function(){
        $('#content h2').css('color', 'red');
        $('#content h2').css('text-decoration', 'line-through');
        if (confirm('Are you sure?')) {
            $.post(BASE_URL+'/manager/menu_items/delete/'+pid+'/'+iid, {'iid': iid}, function(data) {
                if (data=='true') {
                    window.location = BASE_URL+'/manager/menu_items/index/'+pid;
                } else {
                    alert ('Delete is failed! Please try again.');
                }
            });
        } else {
            $('#content h2').css('color', '#686C69');
            $('#content h2').css('text-decoration', 'none');
        }
    });
});
function slideSwitch() {
    var $active = $('#slider img.active');
    if ( $active.length == 0 ) $active = $('#slider img:last');
    var $next =  $active.next().length ? $active.next()
        : $('#slider img:first');
    $active.addClass('last-active');
    $next.css({opacity: 0.0})
        .addClass('active')
        .animate({opacity: 1.0}, 600, function() {
            $active.removeClass('active last-active');
    });
}
</script>