<?php
/*
	Plugin Name: SoMedia Video Ads
	Plugin URI: http://advertising.somedia.net
	Description: Accessible & Affordable Video Ad Production. Everywhere.
	Author: SoMedia Development Team
	Author URI: http://www.somedia.net
	Version: 0.1
*/
/*
	Scalable Video
	Copyright 2014 SoMedia Inc.  (email : support@somedia.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
	along with this program. If not, see <http://www.gnu.org/licenses/>.
	
*/

// define('WP_DEBUG', true);

define( 'AD_VIDEO_PLUGIN_DIR', dirname( __FILE__ ) );

add_action('admin_menu', 'add_somedia_menu');

function add_somedia_menu() {

	if(get_option('somedia_username')){
		add_menu_page( 'SoMedia Video Ads', 'SoMedia Video Ads', 'edit_pages', 'somedia', 'somedia_page',  plugins_url('menu_icon.png', __FILE__));
		add_submenu_page( 'somedia', 'SoMedia Settings', 'Settings', 'edit_pages','somedia-settings', 'somedia_settings');
	} else {
		add_menu_page( 'SoMedia Video Ads', 'SoMedia Video Ads', 'edit_pages', 'somedia-settings', 'somedia_settings', plugins_url('menu_icon.png', __FILE__));
	}
	
}


function somedia_page() {

	if(!get_option('somedia_username')){ 
		somedia_settings();
	} else { ?>

		<script>
		
		jQuery( document ).ready(function() {
		
			jQuery("#SoSubmit a").click(function() {
				var action = jQuery(this).attr("rel");
				jQuery("#SoDestination").val(action);
				jQuery("#user_data").submit();
				return false;
			});
			
		});
		</script>
		
		<style>
			.top-row h1 { text-align: center; }
			.green-grad { font-size: 20px; width: 300px !important; display: block !important; text-transform: none !important; 
			background: #7fc531 !important;
			box-shadow: 0 5px 0 0 #477f08;
			text-shadow: 0 1px 0 #679041;
			border-radius: 8px !important;
			padding: 15px 20px 15px 20px;
			text-align: center;
			color: #fff;
			border: 0px; 
			text-decoration: none;
			margin: 0 auto;
			}
			.container { width: 960px; }
			.green-grad:hover { color: #333; }
			.green-grad span { font-size: 12px; color: #656565; text-shadow: none !important; -webkit-text-shadow: none !important; }
			.hover { color: #fff !important; }
			.grey-span { background: #e2e2e2; }
			.lside { float: left; }
			.rside {  float: right; }
			.w40 { width: 40%; }
			.w60 { width: 60%; }
			.w50 { width: 50%; }
			.page .container { padding: 20px 0 20px 0 !important; }
			
		</style>
		
		
		<style type="text/css">
		
		.table a { text-decoration: none; border: 0; }
		.table div { width:180px;height:390px;margin:10px 10px 50px 0;display:inline-block;text-align:center;font-size:16px;position:relative;font-weight: 600;vertical-align: middle}
		.table p { font-size: 15px; color: #404040; }
		.table h2 {color:#006d99; margin:10px 0 40px 0; font-size: 25px; line-height: 1.2;}
		.table .btn-submit {position: absolute;top:390px;left: 0;font-size: 18px;width: 180px;}
		.table div a img { margin-bottom: 40px; }
		
		</style>
		
		<p class="sobanner"><img src="<? echo plugins_url( 'assets/banner-772x250.png' , __FILE__ ); ?>"></p>
		
		<div class="table" id="SoSubmit">
	
			<div>
			<a href="#" rel="place-order">
			<h2>Order<br/>Video Ads</h2>
			<img src="<? echo plugins_url( 'assets/icon1.png' , __FILE__ ); ?>">
			<p>Choose from a wide variety of video advertising styles optimized for conversion and online viewing.</p>
			</a>
			</div>
			
			<div>
			<a href="#" rel="business-place-order">
			<h2>Order<br/>Business Video</h2>
			<img src="<? echo plugins_url( 'assets/icon2.png' , __FILE__ ); ?>">
			<p>Choose from authentic business video formats designed for businesses of any size.</p>
			</a>
			</div>
			
			<div>
			<a href="#" rel="my-players">
			<h2>Customize<br/>Video Players</h2>
			<img src="<? echo plugins_url( 'assets/icon3.png' , __FILE__ ); ?>">
			<p>Customize your player colors and add calls-to-action to every video.</p>
			</a>
			</div>			
			
			<div style="margin-right:0px">
			<a href="#" rel="my-analytics">
			<h2>View Real-Time<br/>Video Stats</h2>
			<img src="<? echo plugins_url( 'assets/icon3.png' , __FILE__ ); ?>">
			<p>Monitor your video's performance through a real-time video analytics dashboard.</p>
			</a>
			</div>
			
		</div>
		
		<div style="width: 772px; text-align: center">
			<p style="font-size:18px; font-weight: bold">Produced anywhere in North American,<br/>Delivered in 14 days.</p>
		
		</div>
		
		<form method="post" action="http://login.somedia.net/login" target="_blank" id="user_data">
			<input type="hidden" name="edit[name]" value="<? echo get_option('somedia_username'); ?>">
			<input type="hidden" name="edit[pass]" value="<? echo get_option('somedia_password'); ?>">
			<input type="hidden" name="domain" value="advertising">
			<input type="hidden" name="destination" value="" id="SoDestination">
			<input type="submit" style="display: none;">
		</form>
<?	}
}

function somedia_settings() {

	require_once(AD_VIDEO_PLUGIN_DIR.'/soemdia-video-ads-settings.php');
	$somediaSettings = new SoMedia_Settings();

	if ($_GET['action']!=-1)
		$action=$_GET['action'];

	elseif (isset($_GET['action2']))
		$action=$_GET['action2'];
	else
	$action='show_list';

	if (method_exists($somediaSettings,$action)) {
		$somediaSettings->$action();
	}
	else
		$somediaSettings->options_page();
	return true;
	
}


function embedSomediaCode() { ?>

	<script>

	jQuery(function(){
	
		jQuery("#somedia-add-player").click(function() {
		
				jQuery.ajax({
					type: "GET",
					url: '<? echo plugins_url( 'list-players.php' , __FILE__ ); ?>',
					data: { so_username : '<? echo get_option('somedia_username'); ?>', so_secret : '<? echo get_option('somedia_secret'); ?>' },
					success: function(response) { 
						jQuery("#TB_ajaxContent").html(response);
				  }
				});
			
		});	
		
	});	
	</script>
<? }

add_action('admin_head', 'embedSomediaCode');

function wp_somedia_add_player_button() {
 add_thickbox();
 
 /** thickbox only loads content within a child element, and onclose, or send_to_editor, will remove this child element **/
 
 $btn = '<div id="somedia-add-players-container" style="display:none;"></div>
		 <a href="#TB_inline?height=550px&inlineId=somedia-add-players-container" class="thickbox button" title="Click on one of your players to insert it into your page!" id="somedia-add-player"><img src="'.plugins_url( "icon_somedia.png" , __FILE__ ).'"> Add Scalable Video Player</a>'; 
  return sprintf($btn);
}

add_filter('media_buttons_context', 'wp_somedia_add_player_button');

function somedia_shortcode( $atts ) {
    $a = shortcode_atts( array(
        'id' => '',
        'size' => 'medium' // defaults to medium if undefined
    ), $atts );

	if ($a['size'] == 'small'){
		$size = '420';
	} else if ($a['size'] == 'medium') {
		$size = '560';
	} else if ($a['size'] == 'large') {
		$size = '640';
	}
	
	if($a['id'] == ''){
		return '** Somedia player error: no player id defined! **';
	} else {
		return '<script type="text/javascript" id="somedia_player_script_'.$a['id'].'" src="http://videoplayer.somedia.net/videoplayer_js?vid='.$a['id'].'&w='.$size.'"></script>';
	}
 
}

add_shortcode( 'scalable_player', 'somedia_shortcode' );

?>
