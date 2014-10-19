<?php
function setup_theme_admin_menus() {
	add_menu_page('Theme settings', 'Example theme', 'manage_options', 
		'tut_theme_settings', 'theme_settings_page', 'http://web-hub.net/wiki/skins/largepublisher/config16.png');

	add_submenu_page('tut_theme_settings', 
		'Front Page Elements', 'Front Page', 'manage_options', 
		'tut_theme_settings', 'theme_front_page_settings'); 
}

function theme_front_page_settings() {
	// Check that the user is allowed to update options
	if (!current_user_can('manage_options')) {
		wp_die('Você não tem permissões suficientes para acessar esta página.');
	}

	?>
	<div class="wrap">
		<?php screen_icon('themes'); ?> <h2>Front page elements</h2>

		<form method="POST" action="">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="num_elements">
							Number of elements on a row:
						</label> 
					</th>
					<td>
						<input type="text" name="num_elements" size="25" />
					</td>
				</tr>
			</table>
		</form>
	</div>
	<?php
}

// We also need to add the handler function for the top level menu
function theme_settings_page() {

}

// This tells WordPress to call the function named "setup_theme_admin_menus"
// when it's time to create the menu pages.
add_action("admin_menu", "setup_theme_admin_menus");