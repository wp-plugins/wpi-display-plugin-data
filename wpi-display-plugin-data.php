<?php
/**
 * Plugin Name: WPi Display Plugin Data
 * Plugin URI: http://wooprali.in/plugins/wpi-display-plugin-data
 * Description: This plugin in used for custom logout page redirection to home page
 * Version: 1.0.0
 * Author: wooprali
 * Author URI: http://wooprali.in
 * Text Domain: wooprali
 * Domain Path: /locale/
 * Network: true
 * License: GPL2
 */
defined('ABSPATH') or die("No script kiddies please!");
// [bartag foo="foo-value"]
class WPiDisplayPluginData{

	public function __construct(){
		add_shortcode( 'wpi_display_plugin_data', array($this, 'display_plugin_data_fun') );
	}
	
	public function display_plugin_data_fun( $atts ) {
		$a = shortcode_atts( array(
			'name' => 'wpi-custom-logout',
			'downloaded' => true,
		), $atts );
		$data="";
		$args=array('timeout' => 120, 'httpversion' => '1.1');
		$default_images=array('default.png', 'default2.png');
		$response = wp_remote_post( 'https://api.wordpress.org/plugins/info/1.0/'.$a['name'].'.json', $args );
		if($response && is_array($response)){
			$decoded = json_decode($response['body'] );		
			if($decoded && is_object($decoded)){			
				//echo $decoded->name;			
				$url=getimagesize("https://ps.w.org/{$decoded->slug}/assets/icon-128x128.png");			
				if(!is_array($url)){
					$image_path=plugins_url( $default_images[rand(0, 1)], __FILE__ );
					$image="<img src='{$image_path}' style='width: 126px;height: 126px;'/>";
				}else{
					$image="<img src='https://ps.w.org/{$decoded->slug}/assets/icon-128x128.png' style='width: 126px;height: 126px;'/>";
				}
				$stars_path=plugins_url( 'stars.png', __FILE__ );			
				$stars_holder_style="position: relative;height: 17px;width: 92px;background: url($stars_path) repeat-x bottom left; vertical-align: top; display:inline-block;";
				$stars_rating_style="background: url($stars_path) repeat-x top left; height: 17px;float: left;text-indent: 100%;overflow: hidden;white-space: nowrap; width: {$decoded->rating}%";
				$stars_rating_value=floor($decoded->rating/20);
				$wordpress_page="https://wordpress.org/plugins/{$decoded->slug}";
				$data="<div class='wpi-display-plugin-data' style='padding-bottom:40px; '>
							<div class='wpi-image'><a href='{$decoded->homepage}' style='font-size:20px;'>{$image}</a></div>
							<div class='wpi-name'><a href='{$decoded->homepage}' style='font-size:20px;'>{$decoded->name}</a></div>
							<div class='wpi-short_description' style='font-size:16px;'>{$decoded->short_description}</div>
							<div class='wpi-data' style='font-size:12px;'>
								<div class='wpi-downloaded'>Downloads: {$decoded->downloaded}</div>	
								<div class='wpi-last_updated'>Last Updated: {$decoded->last_updated}</div>
								<div class='wpi-homepage'>Wordpress page: <a href='{$wordpress_page}' target='_blank' style='border: 0px; '>{$wordpress_page}</a></div>
								<div class='wpi-download-link'>Download Link: <a href='{$decoded->download_link}' target='_blank' style='border: 0px; '>Download</a></div>
								<div class='wpi-rating'>Average Rating:
										<div class='wpi-star-holder' style='{$stars_holder_style}'>
											<div class='wpi-star-rating' style='{$stars_rating_style}'>{$stars_rating_value}</div>
										</div>
										<span class='wpi-ratings-count' style='margin-left:4px;'>({$decoded->num_ratings})</span>
								</div>		
							</div>	
					  </div>";
				//print_r($decoded);	
			}else{
				//print_r($response['body']);
				//$data="No Data";	
			}
		}else{
			//$data="Null";
		}
		return $data;    
	}
	
}
$wpi_display_plugin_data=new WPiDisplayPluginData;
?>