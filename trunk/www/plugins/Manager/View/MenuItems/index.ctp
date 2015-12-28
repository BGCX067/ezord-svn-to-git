<?php if (!empty($groups)) : ?>
    <?php foreach ($groups as $group) : ?>
        <h3 class="underline left" style="width:78%" id="<?php echo 'group_'.$group['MenuItemGroup']['id']; ?>">
            <?php echo $group['MenuItemGroup']['name'] ?>
            <span><?php echo $group['MenuItemGroup']['description'] ?></span>
        </h3>
        <div class="group_memu_icons">
            <ul class="bar_icons">
                <li>
                    <a href="<?php echo $this->base.'/manager/menu_item_groups/edit/'.$group['MenuItemGroup']['id'] ?>" title="Edit group">
                        <?php echo $this->Html->image('edit_s.png') ?> Edit
                    </a>
                </li>
                <li class="last">
                    <a href="javascript:void(0)" title="Delete group" class="delete_groups" id="<?php echo $group['MenuItemGroup']['id'] ?>">
                        <?php echo $this->Html->image('delete_s.png') ?> Delete
                    </a>
                </li>
            </ul>
        </div>
        <div class="clear"></div>
        
            <ul class="images">
                <?php if (!empty($group['MenuItem'])) : ?>
                    <?php foreach ($group['MenuItem'] as $item) : ?>
                        <li>
                            <?php if (empty($item['ItemImage'])) : ?>
                                <a href="<?php echo $this->base.'/manager/menu_items/view/'.$item['id'] ?>">
                                    <?php echo $this->Html->image('no-image-small.png') ?>
                                </a>
                            <?php else : ?>
                                <?php $image_path = $this->Upload->getUploadUrlPath('small', $cManager['id'], $item['id']); ?>
                                <a href="<?php echo $this->base.'/manager/menu_items/view/'.$item['id'] ?>">
                                    <?php echo $this->Html->image($image_path.'/'.$item['ItemImage'][0]['name']); ?>
                                </a>
                            <?php endif; ?>
                            <p><?php echo $item['name'] ?></p>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
                <li>
                    <div class="add_item"><?php echo $this->Html->link('Add item', '/manager/menu_items/add/'.$group['MenuItemGroup']['id']) ?></div>
                </li>
            </ul>
        <div class="clear"></div>
    <?php endforeach; ?>
    <h3 class="underline">&nbsp</h3><br />
    <div class="clear"></div>
    <div id="button_bar" class="right">
        <?php echo $this->Html->link('Add group', '/manager/menu_item_groups/add/', array ('class' => 'button')) ?>
    </div>
    <div class="clear"></div>
<?php else : ?>
    <div class="notice">
        <span>You have no menu setup</span>
        <p>Add group of items and add items later</p>
        <p>&nbsp;</p>
        <p>
            <?php echo $this->Html->link('Add item group', '/manager/menu_item_groups/add/', array ('class' => 'button')) ?>
        </p>
    </div>
<?php endif; ?>
<div class="clear"></div>
<script>
var BASE_URL = '<?php echo $this->base ?>';
$(function() {
    $('.delete_groups').click(function(){
        var gid = $(this).attr('id');
        $('#group_'+gid).css('color', 'red');
        $('#group_'+gid).css('text-decoration', 'line-through');
        if (confirm('Are you sure?')) {
            $.post(BASE_URL+'/manager/menu_item_groups/delete/'+gid, {'gid': gid}, function(data) {
                if (data=='true') {
                    window.location = BASE_URL+'/manager/menu_items/index/';
                } else {
                    alert ('Delete is failed! Please try again.');
                }
            });
        } else {
            $('#group_'+gid).css('color', '#686C69');
            $('#group_'+gid).css('text-decoration', 'none');
        }
    });
});
</script>