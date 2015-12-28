<?php if (!empty($tables)) : ?>
    <div id="button_bar" class="right">
        <?php echo $this->Html->link('Add new table', '/manager/tables/add/'.$pid, array ('class' => 'button')) ?>
    </div>
    <div class="clear"></div>
    <?php foreach ($tables as $table) : ?>
         <div class="manager_tables">
            <?php $cover_str = $table['Table']['cover']<=1 ? 'cover' : 'covers'; ?>
            <h3 id="<?php echo 'table_'.$table['Table']['id'] ?>" class="underline"><?php echo $table['Table']['name']; ?> <span><?php echo $table['Table']['cover'] .' '.$cover_str; ?></span></h3>
			<ul>
                 <li>
                    <?php echo $this->Html->image('edit_s.png') ?>
                    <?php echo $this->Html->link('Edit', '/manager/tables/edit/'.$pid.'/'.$table['Table']['id'], array ()) ?>
                 </li>
                 <li>
                    <?php echo $this->Html->image('delete_s.png') ?>
                    <?php echo $this->Html->link('Delete', 'javascript:void(0)', array ('id' => $table['Table']['id'], 'class' => 'delete_table')) ?>
                 </li>
                 <li class="last">
                     <?php if ($table['Table']['active']==1) : ?>
                        <?php echo $this->Html->image('deactive_s.png') ?>
                        <?php echo $this->Html->link('De-active', '/manager/tables/de_active/'.$pid.'/'.$table['Table']['id'], array ()) ?>
                     <?php else : ?>
                        <?php echo $this->Html->image('active_s.png') ?>
                        <?php echo $this->Html->link('Active', '/manager/tables/active/'.$pid.'/'.$table['Table']['id'], array ()) ?>
                     <?php endif; ?>
                 </li>
            </ul>
            <div class="clear"></div>
            <p><?php echo $table['Table']['description'] ?></p>
        </div>
        <div class="clear"></div>
    <?php endforeach; ?>
    <ul class="paginator right">
		<?php echo $this->Paginator->prev('Previous', array('tag' => 'li', 'class' => 'previous'), null, array('tag' => 'li', 'class' => 'page disabled')) ?>
		<?php echo $this->Paginator->numbers(array('tag' => 'li', 'class' => 'page', 'separator' => false)); ?>
        <?php echo $this->Paginator->next('Next', array('tag' => 'li', 'class' => 'next'), null, array('tag' => 'li', 'class' => 'page disabled')) ?>
	</ul>
    <div class="clear"><br /></div>
<?php else : ?>
    <div class="notice">
        <span>You have no table available!</span>
        <p>&nbsp;</p>
        <p>
            <?php echo $this->Html->link('Add table', '/manager/tables/auto_add/'.$pid, array ('class' => 'button')) ?>
        </p>
    </div>
<?php endif; ?>
<script type="text/javascript">
var BASE_URL = '<?php echo $this->base; ?>';
var pid = '<?php echo $pid; ?>';
$('.delete_table').click(function() {
    var tid = $(this).attr('id');
    $('#table_'+tid).css('color', 'red');
    $('#table_'+tid).css('text-decoration', 'line-through');
    if (confirm('Are you sure?')) {
        $.post(BASE_URL+'/manager/tables/delete/'+pid+'/'+tid, {'tid': tid}, function(data) {
            if (data=='true') {
                window.location = BASE_URL+'/manager/tables/index/'+pid;
            } else {
                alert ('Delete is failed! Please try again');
            }
        });
    } else {
        $('#table_'+tid).css('color', '#686C69');
        $('#table_'+tid).css('text-decoration', 'none');
    }
});
</script>