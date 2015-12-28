<div id="introduce">
    <div id="introduce-text">
        It's easy to manage <br /> coffee shops and restaurants! <br />
        <?php echo $this->Html->link('Register now', '/users/register/') ?>
    </div>
    <div id="login-box">
        <p>If you already has an account. Just login here!</p>
        <?php echo $this->Html->link('Login', '/users/login/', array('class' => 'button right')) ?>
        <?php echo $this->Html->image('paperclip.png', array('class' => 'right-paperclip')) ?>
    </div>
    <div class="clear"></div>
</div>

<div id="features">
    <ul>
        <li><?php echo $this->Html->image('data/1.png')?></li>
        <li><?php echo $this->Html->image('data/2.png')?></li>
        <li class="last"><?php echo $this->Html->image('data/3.png')?></li>
    </ul>
    <div class="clear"></div>
</div>

<div class="clear"></div>
