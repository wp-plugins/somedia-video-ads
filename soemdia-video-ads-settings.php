<?php
class SoMedia_Settings {

	var $options_list=array('somedia_username',
							'somedia_password',
							'somedia_secret');
		
	function save() {
	
		echo "<h2>Somedia Account Settings</h2>";

		if (isset($_POST['submit'])) {
	
				if($_POST['method']=='save'){
				
					$authenticate = file_get_contents("http://partners.somedia.net/wordpress/wordpress-authorize?name=".$_POST['somedia_username']."&pass=".md5($_POST['somedia_password'])."&auth=b1477d3e885d44c03e164f26bf7b14ef");
				
					if($authenticate == 'true'){
					
						foreach ($this->options_list as $op) {
							if (isset($_POST[$op])) {
								update_option($op, stripslashes($_POST[$op]));
							}
						}
					
						$secret = md5(get_option('somedia_username').get_option('somedia_password'));
						update_option('somedia_secret', $secret);
						
						echo "<h2>Success!</h2><p><strong>We are now logging you in...</p>";
						
						$this->redirect('','somedia');
					
					} else { 
					
						echo "<p><strong>Sorry, we couldn't find your account, or your username & password were incorrect.  Please try again.</strong></p>";
						$this->redirect('','somedia-settings');
						
					} 
				
				} else if ($_POST['method']=='create'){
					
					$create = json_decode(file_get_contents("http://partners.somedia.net/wordpress/embedded_create_user?auth=b1477d3e885d44c03e164f26bf7b14ef&email=".$_POST['somedia_username']."&pass=".$_POST['somedia_password']."&app=adv"),true);
					
					if( $create['status'] == 'success'){
					
						foreach ($this->options_list as $op) {
							if (isset($_POST[$op])) {
								update_option($op, stripslashes($_POST[$op]));
							}
						}
				
						$secret = md5(get_option('somedia_username').get_option('somedia_password'));
						update_option('somedia_secret', $secret);
						echo "<h2>Success!</h2><p><strong>We are now logging you in...</p>";
						$this->redirect('','somedia'); // success
						
					} else {
					 
						echo "<p><strong>Sorry your account couldn't be created.</p>
							  <p>".$create['message']."</strong></p>";
						$this->redirect('','somedia-settings');
						
					}
				
				}
				
			}
				
			
	}
	
	function options_page() {
	
		$page = $_GET['page']; 
		
		wp_enqueue_script('__sovalidate__', plugins_url('jquery.validate.1.13.js', __FILE__));
		?>
		
		<script>
			jQuery( document ).ready(function() {
				jQuery("#so_settings").validate();
				jQuery("#so_creation").validate();
			});
		</script>
		<style>
		.full { width: 100%; margin: 0 auto;text-align: center; }
		.full .logo { text-align:  center; }
		.full iframe { margin: 0 auto; text-align: center; padding-top: 10px; }
		#somedia_frame { width: 100%; height: 400px; }
		form input.error { border: 1px solid red; }
		form label.error { color: red; padding-left: 10px; }
		</style>

		<div class="full">
		
		<p class="logo"><img src="<? echo plugins_url( 'logo_somadvertising.png' , __FILE__ ); ?>"></p>
		
		<iframe width="560" height="315" src="//www.youtube.com/embed/M1nTf731Jvo" frameborder="0" allowfullscreen></iframe></div>

		<table width="800" cellpadding="0" cellspacing="0" align="center">
		<tr>
		<td align="left">
		
		<h2>Your SoMedia Account Details:</h2>
		
		<form name='morkovin_base_setup' method='post' id="so_settings" action='<?=$_SERVER['PHP_SELF']?>?page=<?=$page?>&amp;action=save'>
			<table>
				<tr>
					<td style='text-align:right;'>Your username:</td>
					<td><input type='text' class="required" id="so_user" name='somedia_username' value="<?=htmlspecialchars(get_option('somedia_username'))?>"/></td>
				</tr>
				<tr>
					<td style='text-align:right;'>Your password:</td>
					<td><input type='password' class="required" id="so_pass" name='somedia_password' value="<?=htmlspecialchars(get_option('somedia_password'))?>"/></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td style='text-align:center'>
						<input type='hidden' name='method' value='save'>
						<input type='submit' name='submit' value='Save' id="so_save" style='width:140px; height:25px'/>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</form>
		
		</td>
		<td align="center">or</td>
		<td align="right">
		
		<form name='morkovin_base_setup' method='post' id="so_creation" action='<?=$_SERVER['PHP_SELF']?>?page=<?=$page?>&amp;action=save'>
		
		<h2>Create a Free SoMedia Account:</h2>
		
			<table>
				<tr>
					<td style='text-align:right;'>Your email:</td>
					<td><input type='text' class="required" id="so_user" name='somedia_username' /></td>
				</tr>
				<tr>
					<td style='text-align:right;'>Your password:</td>
					<td><input type='password' class="required" id="so_pass" name='somedia_password' /></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td style='text-align:center'>
						<input type='hidden' name='method' value='create'>
						<input type='submit' name='submit' value='Create' id="so_save" style='width:140px; height:25px'/>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</form>
		
		</td>
		</tr>
		</table>
		
		</div>
		
		<?php

	}

	function redirect($message,$page) { ?>

		<h2><?=$message?></h2>
		<SCRIPT language='JavaScript'>setTimeout('location.href="<?=$_SERVER['PHP_SELF']?>?page=<?=$page?>"',2500);</SCRIPT>
		<?php
		die();
	}
	
}
?>