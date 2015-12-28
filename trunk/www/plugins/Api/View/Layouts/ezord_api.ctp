<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0033) -->
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"><head profile="http://gmpg.org/xfn/11"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


<title>EzOrd App</title>

<?php echo $this->Html->css('/css/ezord/style.css'); ?>
<!--[if gt IE 7]>
<?php echo $this->Html->css('/css/ezord/ie7.css'); ?>
<![endif]-->

<?php echo $this->Html->script('jquery.min.js') ?>
<?php echo $this->Html->script('validate/jquery.validate.min.js') ?>

<!-- For debug js -->
<?php echo $this->Html->script('/api/js/cerny/js/cerny.conf.js') ?>
<?php echo $this->Html->script('/api/js/cerny/js/cerny.js') ?>

</head>
<body>
<div id="body">
	<!-- START container -->
	<div id="container">
		<!-- START header -->
		<div id="header">
			<div id="panel-top">
				<div id="logo">&nbsp;</div>
				
				<div id="nav">
                	<ul>
						<li><?php echo $this->Html->link('Documents', '#') ?></li>
                        <li><?php echo $this->Html->link('Help', '#') ?></li>
					</ul>
				</div>
			</div>
			
			<div id="panel-bottom">
				<div id="headline">
					<h3><?php echo $page_title; ?></h3>
				</div>
			</div>
		</div>
		<!-- END header -->

	<div class="clear"></div>

	<!-- START primary -->
	<div id="primary">
		
		<?php echo $content_for_layout; ?>

	<div class="clear"></div>

	<!-- START footer -->
	<div id="footer">
		<div class="footer"></div>
	</div>
	<!-- END footer -->


	</div>
	<!-- END container -->

</div>
</body></html>