<?php $subnav = isset($subnav) ? $subnav : null; ?>
<?php if (!is_null($subnav)) : ?>
    <div id="subnav">
        <?php if ($subnav == SUBNAV_DASHBOARD) : ?>
            <ul>
                <li><?php echo $this->Html->link('+ Add new place', '/manager/places/add/', array ('class' => 'button')) ?></li>
        	</ul>
        <?php elseif ($subnav == SUBNAV_SHOP_DASHBOARD) : ?>
            <ul>
                <li><?php echo $this->Html->link('Team members', '/manager/accounts/team/'.$pid) ?></li>
                <li><?php echo $this->Html->link('Menu', '/manager/menu_items/index/'.$pid) ?></li>
                <li><?php echo $this->Html->link('Tables', '/manager/tables/index/'.$pid) ?></li>
                <li><?php echo $this->Html->link('Orders', 'javascript:void(0)'.$pid) ?></li>
        	</ul>
        <?php else : ?>
            <p>&nbsp;</p>
        <?php endif; ?>
    </div>
    <p>&nbsp;</p>
<?php endif; ?>