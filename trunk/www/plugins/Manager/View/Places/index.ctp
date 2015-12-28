<?php if (!empty($places)) : ?>
    <?php foreach ($places as $place) : ?>
        <div class="manager_places">
    		<h3 class="underline">
                <?php echo $place['Place']['name']; ?> 
                <span><?php echo $place['Place']['slogan']; ?></span>
            </h3>
			<ul>
                <li>
                    <?php echo $this->Html->image('edit_s.png') ?>
                    <?php echo $this->Html->link('Edit', '/manager/places/edit/'.$place['Place']['id']) ?>
                </li>
                <li class="last">
                    <?php if ($place['Place']['active'] == 1) : ?>
                        <?php echo $this->Html->image('deactive_s.png') ?>
                        <?php echo $this->Html->link('De-active', '/manager/places/deactive/'.$place['Place']['id']) ?>
                    <?php else : ?>
                        <?php echo $this->Html->image('active_s.png') ?>
                        <?php echo $this->Html->link('Active', '/manager/places/active/'.$place['Place']['id']) ?>
                    <?php endif; ?>
                </li>
            </ul>
            <div class="clear"></div>
            <p><?php echo $place['Place']['description'] ?></p>
  		</div>
        <div class="clear"></div>
    <?php endforeach; ?>
    <div class="clear"><br /></div>
<?php else : ?>
    <div class="notice">
        <span>You have no place available!</span>
        <p>&nbsp;</p>
        <p><?php echo $this->Html->link('Add place', '/manager/places/add/', array ('class' => 'button')) ?></p>
    </div>
<?php endif; ?>