<?php
/**
 * Plugin Name: Sedoo - Admincolor
 * Description: Permet aux admins de générer une palette de couleur pour tous les sites et l'enregistre dans les options du theme
 * Version: 0.1
 * Author: Nicolas Gruwe - SEDOO DATA CENTER
 * Author URI:      https://www.sedoo.fr 
 * GitHub Plugin URI: sedoo/sedoo-wppl-admincolor
 * GitHub Branch:     master
 */
// create custom plugin settings menu



add_action('admin_menu', 'sedoo_admincolor_create_menu');

function sedoo_admincolor_create_menu() {
	//create new top-level menu
	add_menu_page('Color for Admins', 'Admin colors', 'administrator', __FILE__, 'sedoo_admincolor_page' );
}


function sedoo_admincolor_page() {

$sites = get_sites();

echo '<script type="text/javascript">
var ajaxurl = "' . admin_url('admin-ajax.php') . '";
</script>';

?>

<!-- TEMPORAIIIIIRE LE TEMPS DE TROUVER UNE SOLUTION  -->
<script  src="https://code.jquery.com/jquery-3.5.1.min.js"
			  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
			  crossorigin="anonymous"></script>
<script src='<?php echo plugin_dir_url( __FILE__ ); ?>colorwheel.js'></script>



<?php
foreach($sites as $site) {
	switch_to_blog($site->blog_id);

	
	$current_theme = get_current_theme();

	// only on labs by sedoo them
	if($current_theme == 'Labs by Sedoo') {
		$main_color = get_theme_mod('labs_by_sedoo_color_code');
		if($main_color) { $main_color = substr($main_color, 1); }
		else { $main_color = 'ffffff';	}
		?>
		<script>
		
			// calculate colors from first one
			var scheme = new ColorScheme;
			scheme.from_hex(<?php echo json_encode($main_color) ?>)         
				.scheme('triade')   
				.variation('soft');
				// color list
			var colors = scheme.colors();

			// ordering the colors
			var color1 = colors[0];
			var color2 = colors[1];
			var color3 = colors[2];
			var color4 = colors[3];
			var color5 = colors[4];

			// Passing the colors to ajax for saving them
			jQuery.ajax({
				url: ajaxurl,
				type: "POST",
				data: {
				'action': 'sedoo_colorgeneration_theme_colors', // dans le theme labs
				'color1': color1,
				'color2': color2,
				'color3': color3,
				'color4': color4,
				'color5': color5,
				'siteid' : <?php echo json_encode($site->blog_id) ?>
				}
			}).done(function(response) {
				
			});
		</script>

	<?php 
	}
}
}

?>