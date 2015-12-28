<div id="logo">
    <ul id="logo-menu">
        <li>
    		<?php if ($cUser['user_type'] == USER_TYPE_MEMBER) : ?>
                <?php echo $this->Html->image('logo.gif'); ?>
            <?php else: ?>
                <?php echo $this->Html->link($this->Html->image('logo.gif'), '#', array('escape' => false, 'class' => 'drop', 'tabindex' => 1)); ?>
                <ul>
                    <li class="separator">&nbsp;</li>
                    <li><?php echo $this->Html->link('Account', '/manager/accounts/detail/') ?></li>
                    <li><?php echo $this->Html->link('Plans & Upgrades', '/manager/payments/') ?></li>
                    <li><?php echo $this->Html->link('Help & Guide', '#') ?></li>
                    <li class="separator">&nbsp;</li>
        		</ul>
            <?php endif; ?>
    	</li>
    </ul>
</div>
<div id="nav">
    <?php if (!empty($cPlace)) : ?>
        <ul>
            <li<?php if ($this->name == 'Accounts' && $this->action == 'place') echo ' class="active"'; ?>><?php echo $this->Html->link($cPlace['name'], '/order/tables/index/'.$cPlace['id']) ?></li>
            <!--li<?php if ($this->name == 'Accounts' && $this->action == 'team') echo ' class="active"'; ?>><?php echo $this->Html->link('My team', '/manager/user_members/') ?></li-->
            <li> &nbsp; | &nbsp;</li>
            <li<?php if ($this->name == 'Accounts' && $this->action == 'me') echo ' class="active"'; ?>><?php echo $this->Html->link('Me', '/manager/accounts/me/') ?></li>
		</ul>
    <?php else: ?>
        <ul>
            <li<?php if ($this->name == 'Accounts' && $this->action == 'dashboard') echo ' class="active"'; ?>><?php echo $this->Html->link('My places', '/manager/accounts/dashboard/') ?></li>
            <li<?php if ($this->name == 'MenuItems' && $this->action == 'index') echo ' class="active"'; ?>><?php echo $this->Html->link('Foods & Drinks', '/manager/menu_items/index/') ?></li>
            <!--li<?php if ($this->name == 'Services' && $this->action == 'index') echo ' class="active"'; ?>><?php echo $this->Html->link('Services', '/manager/services/index/') ?></li-->
            <li> &nbsp; | &nbsp;</li>
            <li<?php if ($this->name == 'Accounts' && $this->action == 'team') echo ' class="active"'; ?>><?php echo $this->Html->link('Members', '/manager/user_members/') ?></li>
            <li<?php if ($this->name == 'Accounts' && $this->action == 'me') echo ' class="active"'; ?>><?php echo $this->Html->link('Me', '/manager/accounts/me/') ?></li>
            <li> &nbsp; | &nbsp;</li>
            <li<?php if ($this->name == 'Accounts' && $this->action == 'report') echo ' class="active"'; ?>><?php echo $this->Html->link('Reports', '/report/accounts/dashboard/') ?></li>
        </ul>
    <?php endif; ?>
</div>
<div id="session">
	<span>Logged as <strong><?php echo $this->Html->link(ucfirst($cUser['first_name'].' '.$cUser['last_name']), '/manager/accounts/me/'); ?></strong> (<?php echo $this->Html->link('Logout', '/users/logout/') ?>)</span>
</div>