<?php if (!empty($places)) : ?>
    <div id="columns">
        <div id="col-left">
            <?php $break = round(count($places)/2); ?>
            <?php foreach ($places as $i => $place) : ?>
                <div class="box shadow" style="min-height: 100px">
                	<div id="place-dashboard-icons">
                        <a href="<?php echo $this->base.'/order/tables/index/'.$place['Place']['id'] ?>" title="Place">
                            <?php echo $this->Html->image('place.png') ?>
                        </a>
                        <a href="<?php echo $this->base.'/manager/places/dashboard/'.$place['Place']['id'] ?>" title="Settings">
                            <?php echo $this->Html->image('setting.png') ?>
                        </a>
                    </div>
                    <div id="place-dashboard-info">
                        <h3><?php echo $this->Html->link($place['Place']['name'], '/order/tables/index/'.$place['Place']['id']); ?></h3>
                    	<span><?php echo $place['Place']['slogan'] ?></span>
                        <p>Since <strong><?php echo $this->Time->niceShort($place['Place']['created']) ?></strong></p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
                <?php unset($places[$i]); ?>
                <?php if ($i>=$break-1) break; ?>
            <?php endforeach; ?>
            <?php if ($place_num>0 && $place_num%2==0) : ?>
                <div align="center" class="box shadow-back" id="add_box">
                    <h3><?php echo $this->Html->link('Add new place', '/manager/places/add') ?></h3>
                </div>
            <?php endif; ?>
        </div>
        <div id="col-right">
            <?php foreach ($places as $place) : ?>
                <div class="box shadow" style="min-height: 100px">
                    <div id="place-dashboard-icons">
                        <a href="<?php echo $this->base.'/order/tables/index/'.$place['Place']['id'] ?>" title="Place">
                            <?php echo $this->Html->image('place.png') ?>
                        </a>
                        <a href="<?php echo $this->base.'/manager/places/dashboard/'.$place['Place']['id'] ?>" title="Settings">
                            <?php echo $this->Html->image('setting.png') ?>
                        </a>
                    </div>
                    <div id="place-dashboard-info">
                        <h3><?php echo $this->Html->link($place['Place']['name'], '/order/tables/index/'.$place['Place']['id']); ?></h3>
                    	<span><?php echo $place['Place']['slogan'] ?></span>
                        <p>Since <strong><?php echo $this->Time->niceShort($place['Place']['created']) ?></strong></p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            <?php endforeach; ?>
            <?php if ($place_num>0 && $place_num%2==1) : ?>
                <div align="center" class="box shadow-back" id="add_box">
                    <h3><?php echo $this->Html->link('Add new place', '/manager/places/add') ?></h3>
                </div>
            <?php endif; ?>
        </div>                            
    </div>
<?php else : ?>
    <div class="notice">
        <span>You have no place available</span>
        <p>Add place to start</p>
    </div>
    <div id="columns">
        <div id="col-left">
            <div align="center" class="box shadow" id="add_box_first" style="min-height: 143px;">
                <h3><?php echo $this->Html->link('Add new place', '/manager/places/add') ?></h3>
            </div>
        </div>
        <div id="col-right">
            <div align="center" class="box shadow-back" id="add_box">&nbsp;</div>
        </div>
    </div>
<?php endif; ?>