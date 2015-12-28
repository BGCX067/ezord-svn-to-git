<?php
    $p_ops = array (); $r_ops = array (); $a_ops = array ();
    $active = isset($active) ? $active : null;
    if ($active == 'place') {
        $p_ops = array ('class' => 'active');
    } else if ($active == 'setting') {
        $a_ops = array ('class' => 'active');
    }
?>
<?php if (!empty($pid)) : ?>
    <div id="side-icons">
        <?php if (empty($cPlace) 
                    || (isset($cPlace['permit_report']) && $cPlace['permit_report'] == 1) 
                    || (isset($cPlace['permit_manager']) && $cPlace['permit_manager'] == 1)) : ?>
            <a href="<?php echo $this->base.'/order/tables/index/'.$pid ?>" title="Place"><?php echo $this->Html->image('place.png', $p_ops) ?></a>
        <?php endif; ?>
        <?php if (empty($cPlace) || (isset($cPlace['permit_manager']) && $cPlace['permit_manager'] == 1)) : ?>
            <a href="<?php echo $this->base.'/manager/places/dashboard/'.$pid ?>" title="Settings"><?php echo $this->Html->image('setting.png', $a_ops) ?></a>
        <?php endif; ?>
    </div>
<?php endif; ?>