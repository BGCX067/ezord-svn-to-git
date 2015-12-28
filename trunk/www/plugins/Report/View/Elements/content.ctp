<?php $papers = isset($papers) ? $papers : array (); ?>
<?php foreach ($papers as $paper) : ?>
    <div class="shadow-back" style="width: 810px;">
    <?php echo $this->Html->link('<h2>'.$paper['name'].'</h2>', $paper['link'], array('escape' => false))?>
<?php endforeach; ?>
        <?php  $minheight = (empty($papers)) ? ' min-height: 500px;' : ''; ?>
        <div id="content" class="shadow" style="width: 810px;<?php echo $minheight; ?>">
            <h2><?php echo $page_title; ?></h2>
            <?php echo $this->Session->flash(); ?>
            <?php echo $content_for_layout; ?>
        </div>
<?php foreach ($papers as $paper) : ?>
    </div>
<?php endforeach; ?>