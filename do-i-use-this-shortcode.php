<?php
/*
	Plugin Name: Do I use this shortcode?
	Description: Plugin creates an overview over all registered shortcodes and count the number of occurrences in all published post types (pages, post, etc.). This helps determining if a registered shortcode is still needed.
	Version: 0.1
	Author: Moritz Kuendig
	Author URI: http://moritzkuendig.ch
*/

class do_i_use_this_shortcode {
	public function __construct()	{
		add_action( 'admin_menu', array(&$this, 'diuts_add_menu') );
	}
	
	// Adds a menu to appearance settings
	public function diuts_add_menu(){
		add_submenu_page(
			'themes.php',
			__('Do I use this shortcode?', 'diuts'),
			__('Do I use this shortcode?', 'diuts'),
			'manage_options',
			'diuts_menu',
			array(&$this,'diuts_add_admin_page'));
	}

	// Adds page to backend
	public function diuts_add_admin_page() {		
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"><br></div>
			<h2><?php _e('Do I use this shortcode?', 'diuts') ?></h2>
			<br>
			<div class="section">
				<table class="widefat">
					<tr>
						<th><b><?php _e('Shortcodes', 'diuts') ?></b></th>
						<th><b><?php _e('Occurences in published post types', 'diuts') ?></b></th>
					</tr>
					<?php

				global $shortcode_tags;
				$shortcodes = array();
				foreach($shortcode_tags as $code => $function) {
					$shortcodes[$code] = 0;	
				}
				$shortcodes = $this->diuts_count_occurences($shortcodes);
					foreach ($shortcodes as $i => $num) {
						?>
					<tr>
						<td>[<?php echo $i; ?>]</td>
						<td><?php echo $num; ?></td>
					</tr>
					<?php
							}
				?>
				</table>
			</div>
			<p>
				<i><a href="https://www.paypal.me/itsmoremoritz" target="_blank">Thank me with a coffee :)</a></i>
			</p>
		</div>
		<?php
	}

	// Counts the occurcences for every shortcode
	public function diuts_count_occurences($shortcodes) {
		$args = array(
			'post_type' => 'any',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'suppress_filters' => true
		);
		$qry = new WP_Query($args);
		foreach ($qry->posts as $p) { 
			$content = $p->post_content;
			foreach ($shortcodes as $i => $num) {
				$shortcodes[$i] = $shortcodes[$i] + substr_count($content, '[' . $i);
			} 
		}
		return $shortcodes;
	}
}

if(is_admin()) {
	$plugin = new do_i_use_this_shortcode();
}
?>