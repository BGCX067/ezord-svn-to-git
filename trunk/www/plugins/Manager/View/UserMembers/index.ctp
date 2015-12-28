<?php if (!empty($members)) : ?>
    <div id="button_bar" class="right">
        <?php echo $this->Html->link('Add member', '/manager/user_members/add/', array ('class' => 'button')) ?>
    </div>
    <div class="clear"></div>
    <?php $i=1; foreach ($members as $member) : ?>
        <?php $pos = ($i>1 && $i%3==0) ? 'last' : ''; ?>
        <div class="member<?php echo ' '.$pos; ?>">
            <div class="avatar">
                <a href="<?php echo $this->base.'/manager/user_members/view/'.$member['Member']['id']?>">
                     <?php if (empty($member['Member']['UserProfile']['avatar'])) : ?>
                        <?php echo $this->Html->image('no-profile-image.png') ?>
                     <?php else: ?>
                        <?php $image_path = $this->Upload->getUploadUrlPath('', $member['Member']['id']) ?>
                        <?php echo $this->Html->image($image_path.'/avatar/small/'.$member['Member']['UserProfile']['avatar']) ?>
                     <?php endif; ?>
                </a>
            </div>
            <div class="detail">
                <h3 class="underline">
                    <?php echo $this->Html->link($member['Member']['first_name'].' '.$member['Member']['last_name'], '/manager/user_members/view/'.$member['Member']['id']); ?>
                </h3>
                <div class="clear"></div>
                <p><strong>Email:</strong> <?php echo $member['Member']['email'] ?></p>
                <p><strong>Phone:</strong> <?php echo $member['Member']['phone'] ?></p>
            </div>
        </div>
    <?php $i++; endforeach; ?>
    <div class="clear"><br /></div>
<?php else : ?>
    <div class="notice">
        <span>You have no member available!</span>
        <p>Add member to help you manage the places</p>
        <p>&nbsp;</p>
        <p><?php echo $this->Html->link('Add member', '/manager/user_members/add/', array ('class' => 'button')) ?></p>
    </div>
<?php endif; ?>