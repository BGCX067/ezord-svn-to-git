<div id="columns">
    <div id="col-left" class="inner-window">
        <h3 class="underline"><?php echo $this->Html->link('Table', '/manager/tables/index/'.$pid) ?></h3>
        <?php $table_str = ($table_num>1) ? "There are $table_num tables" : "There is $table_num table"; ?>
        <?php if ($table_num==0) $table_str = 'There is no table setup'; ?>
        <p><?php echo $table_str; ?></p>
        <h3 class="underline"><a href="javascript:void(0)">Menu</a></h3>
        <p>Customize menu in advance</p>
        <!--h3 class="underline"><a href="javascript:void(0)">Service</a></h3>
        <p>Customize service in advance</p-->
    </div>
    <div id="col-right" class="inner-window">
        <h3 class="underline"><?php echo $this->Html->link('Place edit', '/manager/places/edit/'.$pid) ?></h3>
        <p>Edit place information</p>
        <h3 class="underline"><?php echo $this->Html->link('Archive', '/manager/places/archive/'.$pid) ?></h3>
        <p>Archive this place</p>
    </div>
    <div class="clear"><br /></div>
</div>