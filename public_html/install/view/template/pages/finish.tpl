<?php echo $header; ?>
<div class="row">
	<div class="col-md-9">
	<div class="panel panel-default">

	<ul class="nav nav-tabs" role="tablist">
	  <li class="disabled"><a href="#" onclick="return false;">1: License</a></li>
	  <li class="disabled"><a href="#" onclick="return false;">2: Compatibility Validation</a></li>
	  <li class="disabled"><a href="#" onclick="return false;">3: Configuration</a></li>
	  <li class="disabled"><a href="#" onclick="return false;">4: Data Load</a></li>
	  <li class="active"><a href="#" onclick="return false;">5: Finished</a></li>
	</ul>

	<div class="panel-heading">
	<h2><i class="fa fa-flag-checkered fa-fw"></i> Installation Completed!</h2>
	</div>
	
	<div class="panel-body panel-body-nopadding">
	
		<div class="warning alert alert-error alert-danger"><?php echo $message; ?></div>

		<?php if($salt){?>
			<div class="warning alert alert-error alert-danger">
				Keep this secure key in save place.<br>This key is used to encrypt customers's secure data.<br>You will need this key if you move data to different shopping cart installation.<br>
				<h3><?php echo $salt; ?></h3></div>
		<?php }  ?>

		<p>Congratulations! You have successfully installed AbanteCart eCommerce application. See access to your store front and control panel sections below. Bookmark or remember your control panel link and keep it save.</p>
		<p>Thank you for this choice, and enjoy many features AbanteCart has to offer.</p> 
		<p>Your comments and contributions are very welcome.</p>
		<p class="text-center">
			<i class="fa fa-home fa-fw"></i> <a href="http://www.abantecart.com" target="_abante">Project Homepage</a>&nbsp;&nbsp;
			<i class="fa fa-graduation-cap fa-fw"></i> <a href="http://www.abantecart.com/ecommerce-documentation" target="_blank">Documentation</a>&nbsp;&nbsp;
			<i class="fa fa-puzzle-piece fa-fw"></i> <a href="http://marketplace.abantecart.com" target="_blank">Marketplace</a>&nbsp;&nbsp;
			<i class="fa fa-comments fa-fw"></i> <a href="http://forum.abantecart.com" target="_abante">Support Forums</a></p>

		<div class="container-fluid text-center">
		    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		    <input type="hidden" name="cmd" value="_s-xclick">
		    <input type="hidden" name="hosted_button_id" value="YUDUXTP8GED58">
		    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="Donate to AbanteCart Project and Help build better eCommerce application">
		    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		    </form>
		</div>

		<div class="container-fluid">
	    <div class="snapshots col-md-6">
		    <a href="../"><img src="<?php echo $template_dir; ?>image/storefront.png" alt="" width="250" style="border: none;" /></a><br />
		    <a href="../">Your Online Shop</a>
		</div>
	    <div class="snapshots col-md-6">
		    <a href="../<?php echo $admin_path ?>"><img src="<?php echo $template_dir; ?>image/admin.png" alt="" width="250" style="border: none;" /></a><br />
		    <a href="../<?php echo $admin_path ?>">Login to your Control Panel</a>
		</div>
		</div>

		</br>
	</div>
		
	</div>
	
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-body text-center">
				<div class="social_icon">
				<a href="https://www.facebook.com/AbanteCart" target="_new"><i class="fa fa-thumbs-o-up fa-4x"></i></a>
				</div>
				<h4><a href="https://www.facebook.com/AbanteCart" target="_new">Like AbanteCart</a></h4>

				<div class="social_icon">
				<a href="https://twitter.com/abantecart" target="_new"><i class="fa fa-twitter fa-4x"></i></a>
				</div>
				<h4><a href="https://twitter.com/abantecart" target="_new">Follow us on Twitter</a></h4>

				<div class="social_icon">
				<a href="https://marketplace.abantecart.com/index.php?rt=account%2Fsubscriber" target="_new"><i class="fa fa-newspaper-o fa-4x"></i></a>
				</div>
				<h4><a href="https://twitter.com/abantecart" target="_new">News & Updates</a></h4>

				<div class="social_icon">
				<a href="http://forum.abantecart.com" target="_new"><i class="fa fa-comments fa-4x"></i></a>
				</div>
				<h4><a href="http://forum.abantecart.com" target="_new">Community forum</a></h4>

				<div class="social_icon">
				<a href="http://www.abantecart.com/partners" target="_new"><i class="fa fa-group fa-4x"></i></a>
				</div>
				<h4><a href="http://www.abantecart.com/partners" target="_new">Commercial Support</a></h4>

				<div class="social_icon">
				<a href="https://github.com/abantecart/abantecart-src" target="_new"><i class="fa fa-github fa-4x"></i></a>
				</div>
				<h4><a href="https://github.com/abantecart/abantecart-src" target="_new">Code with us</a></h4>
				
			</div>
		</div>
	</div>
	
</div>
<?php echo $footer; ?>