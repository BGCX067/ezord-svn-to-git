<?php if (empty($reserves)) : ?>
<div class="notice">
    <span>No reserve available!</span>
    <p>&nbsp;</p>
    <p>
        <?php echo $this->Html->link('Place a reserve', '/order/reserves/add/'.$pid, array ('class' => 'button')) ?>
    </p>
</div>

<?php else : ?>
    <p class="right">
        <?php echo $this->Html->link('Place a reserve', '/order/reserves/add/'.$pid, array('class' => 'button')) ?>
    </p>
    <div class="clear"></div>
    <?php foreach ($reserves as $reserve) : ?>
        <div class="reserves">
            <h3 id="<?php echo 'reserve_'.$reserve['Reserve']['id'] ?>" class="underline">
                <?php echo $this->Time->niceShort($reserve['Reserve']['time']) ?>
                <?php if (!empty($reserve['Table']['id'])) : ?>
                    at <?php echo strtolower($reserve['Table']['name']) ?>
                <?php endif; ?>
            </h3>
            <ul>
                <li>
                    <?php if (empty($reserve['Table']['id'])) : ?>
                        <?php echo $this->Html->image('assign_s.png') ?>
                        <?php echo $this->Html->link('Assign', '/order/reserves/assign/'.$pid.'/'.$reserve['Reserve']['id']) ?>
                    <?php else : ?>
                        <?php echo $this->Html->image('un_assign_s.png') ?>
                        <?php echo $this->Html->link('Un-assign', '/order/reserves/un_assign/'.$pid.'/'.$reserve['Reserve']['id']) ?>
                    <?php endif; ?>
                </li>
                <li>
                    <?php echo $this->Html->image('edit_s.png') ?>
                    <?php echo $this->Html->link('Edit', '/order/reserves/edit/'.$pid.'/'.$reserve['Reserve']['id']) ?>
                </li>
                <li class="last">
                    <?php echo $this->Html->image('delete_s.png') ?>
                    <?php echo $this->Html->link('Delete', 'javascript:void(0);', array ('id' => $reserve['Reserve']['id'], 'class' => 'delete_reserve')) ?>
                </li>
            </ul>
            <div class="clear"></div>
            <p><?php echo $reserve['Reserve']['memo']; ?></p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<script type="text/javascript">
var BASE_URL = '<?php echo $this->base; ?>';
var pid = '<?php echo $pid; ?>';
$(function() {
    $('.delete_reserve').click(function(){
        var rid = $(this).attr('id');
        $('#reserve_'+rid).css('color', 'red');
        $('#reserve_'+rid).css('text-decoration', 'line-through');
        if (confirm('Are you sure?')) {
            $.post(BASE_URL+'/order/reserves/delete/'+pid+'/'+rid, {'rid': rid}, function(data) {
                if (data=='true') {
                    window.location = BASE_URL+'/order/reserves/index/'+pid;
                } else {
                    alert ('Delete is failed! Please try again');
                }
            });
        } else {
            $('#reserve_'+rid).css('color', '#686C69');
            $('#reserve_'+rid).css('text-decoration', 'none');
        }
    });
});
</script>