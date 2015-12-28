<div id="member_view">
    <div class="profile">
        <?php if (empty($user['UserProfile']['avatar'])) : ?>
            <?php echo $this->Html->image('no-profile-image.png') ?>
        <?php else: ?>
            <?php echo $this->Html->image($image_path.'/'.$user['UserProfile']['avatar']) ?>
        <?php endif; ?>
        <div class="info">
            <p>Full Name: <strong><?php echo $user['User']['first_name'].' '.$user['User']['last_name']?></strong></p>
            <p>Email: <strong><?php echo $user['User']['email'] ?></strong></p>
            <p>Phone: <strong><?php echo $user['User']['phone'] ?></strong></p>
            <p>Created: <strong><?php echo $this->Time->niceShort($user['User']['created']); ?></strong></p>
            <p>Last login: <strong>
                <?php if (!empty($user['User']['last_login'])) : ?>
                    <?php echo $this->Time->niceShort($user['User']['last_login']); ?>
                <?php else: ?>
                    Never
                <?php endif; ?>
            </strong></p>
        </div>
        <ul>
            <li>
                <?php echo $this->Html->image('avatar_s.png')?>
                <?php echo $this->Html->link('Upload avatar', '/manager/users/avatar/'.$uid) ?>
            </li>
            <li>
                <?php echo $this->Html->image('password_s.png')?>
                <?php echo $this->Html->link('Change password', '/manager/users/change_password/'.$uid) ?>
            </li>
            <li>
                <?php echo $this->Html->image('edit_s.png')?>
                <?php echo $this->Html->link('Edit', '/manager/users/edit/'.$uid) ?>
            </li class="last">
        </ul>
    </div>
    <div class="clear"></div>
    <div class="activities">
        <h3 class="underline">Activities</h3>
        <?php if (empty($activities)) : ?>
            <p>All your ativities will be displayed here.</p>
        <?php else: ?>
        
        <?php endif; ?>
        <p>&nbsp;</p>
    </div>
</div>