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

</head>
<body>
    <div id="body">
    	<!-- START container -->
    	<div id="container">
    		<!-- START header -->
    		<div id="header">
    			<div id="panel-top">
    				<div id="logo">
                        <?php echo $this->Html->link($this->Html->image('logo.gif'), '/', array('escape' => false, 'tabindex' => 1)); ?>
                    </div>
    				<div id="nav">
                    	<ul>
                            <li><?php echo $this->Html->link('Home', '/') ?></li>
                            <!--li><?php echo $this->Html->link('Take a tour', '/pages/tour/') ?></li-->
                            <li><?php echo $this->Html->link('Help & Guide', '#') ?></li>
                            <li><?php echo $this->Html->link('Pricing', '/pages/price/') ?></li>
                            <li> &nbsp; | &nbsp;<strong><?php echo $this->Html->link('My Places Diary', 'javascript:void(0)') ?></strong></li>
    					</ul>
    				</div>
    				<div id="session">
    					<span>
                            <?php echo $this->Html->link('Register', '/users/register') ?> | 
                            <?php echo $this->Html->link('Login', '/users/login') ?>
                        </span>
    				</div>
    			</div>
    			<div class="clear"></div>
    		</div>
    		<!-- END header -->
            <div class="clear"></div>
        	<div id="content">
                <div id="primary">
                    <?php if (!empty($page_title)) : ?>
                        <h2><?php echo $page_title; ?></h2>
                    <?php endif; ?>
            		<?php echo $content_for_layout; ?>
                    <div class="clear"></div>
                </div>
        	</div>
    	<!-- END container -->
        
        <div id="footer">
            <?php echo $this->Html->link('Help & Guide', 'javascript:void(0)') ?>
            <?php echo $this->Html->link('Security', 'javascript:void(0)') ?>
            <?php echo $this->Html->link('Privacy', 'javascript:void(0)') ?>
            <?php echo $this->Html->link('Terms', 'javascript:void(0)') ?>
            <?php echo $this->Html->link('Contact', 'javascript:void(0)') ?>
            <div class="copyright">&copy;<?php echo date('Y') ?> Ezors Ltd. All right reserved.</div>
        </div>
        </div>
    </div>
</body>
</html>