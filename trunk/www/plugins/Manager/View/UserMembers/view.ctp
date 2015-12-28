<?php if ($member['Member']['active']==0) : ?>
    <div class="error-p">
        <p><?php echo $member['Member']['first_name'].' '.$member['Member']['last_name']?> is de-actived.</p>
    </div>
<?php endif; ?>
<div id="member_view">
    <div class="profile">
        <?php if (empty($member['Member']['UserProfile']['avatar'])) : ?>
            <?php echo $this->Html->image('no-profile-image.png') ?>
        <?php else: ?>
            <?php echo $this->Html->image($image_path.'/'.$member['Member']['UserProfile']['avatar']) ?>
        <?php endif; ?>
        <div class="info">
            <p>Full Name: <strong><?php echo $member['Member']['first_name'].' '.$member['Member']['last_name']?></strong></p>
            <p>Place: 
                <strong>
                     <?php if (!empty($member['Place']['id'])) : ?>
                        <?php echo $member['Place']['name'] ?>
                     <?php else: ?>
                        N/A
                     <?php endif; ?>
                </strong>
            </p>
            <p>Email: <strong><?php echo $member['Member']['email'] ?></strong></p>
            <p>Phone: <strong><?php echo $member['Member']['phone'] ?></strong></p>
            <p>Created: <strong><?php echo $this->Time->niceShort($member['Member']['created']); ?></strong></p>
            <p>Last login: <strong>
                <?php if (!empty($member['Member']['last_login'])) : ?>
                    <?php echo $this->Time->niceShort($member['Member']['last_login']); ?>
                <?php else: ?>
                    Never
                <?php endif; ?>
            </strong></p>
        </div>
        <ul>
            <li>
                <?php echo $this->Html->image('avatar_s.png')?>
                <?php echo $this->Html->link('Upload avatar', '/manager/user_members/avatar/'.$member['Member']['id']) ?>
            </li>
            <li>
                <?php echo $this->Html->image('point_s.png')?>
                <?php echo $this->Html->link('Permissions', '/manager/user_members/assign/'.$member['Member']['id']) ?>
            </li>
            <li>
                <?php echo $this->Html->image('password_s.png')?>
                <?php echo $this->Html->link('Reset password', '/manager/user_members/reset_password/'.$member['Member']['id']) ?>
            </li>
            <li>
                <?php if ($member['Member']['active']==1) : ?>
                    <?php echo $this->Html->image('deactive_s.png')?>
                    <?php echo $this->Html->link('De-active', '/manager/user_members/deactive/'.$member['Member']['id']) ?>
                <?php else: ?>
                    <?php echo $this->Html->image('active_s.png') ?>
                    <?php echo $this->Html->link('Active', '/manager/user_members/active/'.$member['Member']['id']) ?>
                <?php endif; ?>
            </li>
            <li>
                <?php echo $this->Html->image('edit_s.png')?>
                <?php echo $this->Html->link('Edit', '/manager/user_members/edit/'.$member['Member']['id']) ?>
            </li>
            <li class="last">
                <?php echo $this->Html->image('delete_s.png')?>
                <?php echo $this->Html->link('Delete', '/manager/user_members/delete/'.$member['Member']['id'], array(), 'Are you sure?') ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
    <div class="activities">
        <h3 class="underline">Activities</h3>
        <?php if (empty($activities)) : ?>
            <p>All <?php echo $member['Member']['first_name']?>'s ativities will be displayed here.</p>
        <?php else: ?>
        
        <?php endif; ?>
        <p>&nbsp;</p>
    </div>
</div>