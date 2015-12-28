<h3 class="underline">Search "<?php echo $keyword; ?>"</h3>
<ul class="images">
    <?php foreach ($menu_items as $item) : ?>
        <?php echo $this->Form->checkbox('item', array('id' => 'cb_'.$item['MenuItem']['id'])) ?>
        <li>
            <?php if (empty($item['ItemImage'])) : ?>
                <a href="javascript:void(0)" id="<?php echo $item['MenuItem']['id']; ?>">
                    <?php echo $this->Html->image('no-image-small.png') ?>
                </a>
            <?php else : ?>
                <?php $image_path = $this->Upload->getUploadUrlPath('small', $cManager['id'], $pid, $item['MenuItem']['id']); ?>
                <a href="javascript:void(0)" id="<?php echo $item['MenuItem']['id']; ?>">
                    <?php echo $this->Html->image($image_path.'/'.$item['ItemImage'][0]['name']); ?>
                </a>
            <?php endif; ?>
            <p id="<?php echo 'name_'.$item['MenuItem']['id']; ?>"><?php echo $item['MenuItem']['name'] ?></p>
        </li>
    <?php endforeach; ?>
</ul>