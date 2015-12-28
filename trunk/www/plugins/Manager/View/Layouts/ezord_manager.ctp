<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0033) -->
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"><head profile="http://gmpg.org/xfn/11"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


<title>EzOrd App</title>

<?php echo $this->Html->css('/manager/css/style.css'); ?>
<!--[if gt IE 7]>
<?php echo $this->Html->css('/manager/css/ie7.css'); ?>
<![endif]-->

<?php echo $this->Html->script('jquery.min.js') ?>
<?php echo $this->Html->script('validate/jquery.validate.min.js') ?>
<?php echo $this->Html->script('/manager/js/effects.js') ?>

<!--[if lte IE 8]>
<style type="text/css">
#logo-menu a, #logoMenu img, body {behavior:url(clickclick/clickMenu.htc)}
#logo-menu > li > a:active {background:#3bf; color:#000;}
#logo-menu > li > a.drop:active {line-height:36px; background:#3bf url(clickclick/arrow2.gif) no-repeat right 17px;}
#logo-menu > li > a:active ~ ul {display:block;}
#logo-menu > li > a:active + img {display:block; width:150px; height:35px;}
</style>
<![endif]-->
</head>
<body>

<!-- START container -->
<div id="container">
	<!-- START header -->
	<div id="header">
		<div id="panel-top">
			<?php echo $this->element('header') ?>
		</div>
		
		<div class="clear"></div>
		
        <div id="panel-bottom">&nbsp;</div>

	</div>
	<!-- END header -->

<div class="clear"></div>

<!-- START primary -->
<div id="primary">
	<div id="content">
      <?php echo $this->Session->flash(); ?>
      <?php echo $content_for_layout; ?>
    </div>

<div class="clear"></div>

<!-- START footer -->
<div id="footer">
	<div class="footer">&nbsp;</div>
</div>
<!-- END footer -->


</div>
<!-- END container -->

</body></html>