<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    hummingbird
 * @subpackage hummingbird/admin/partials
 */
?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<form method="POST" action="options.php">


		<?php
		settings_fields( $this->option_group );
		do_settings_sections( $this->plugin_name );
		submit_button();
		?>
	</form>

</div>
