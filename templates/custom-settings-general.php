<?php

$selected = get_option( 'wporg_options_pill', 'red' );

?>

<select
		id="wporg_options_pill"
		name="wporg_options_pill"
		data-custom="custom"
	>
		<option value="red" <?php selected( $selected, 'red' ); ?>>
			<?php esc_html_e( 'red pill', 'wporg' ); ?>
		</option>
		<option value="blue" <?php selected( $selected, 'blue' ); ?>>
			<?php esc_html_e( 'blue pill', 'wporg' ); ?>
		</option>
	</select>

	<p class="description pill-desc pill-red" style="display: <?php echo $selected === 'red' ? 'block' : 'none'; ?>;">
		<?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'wporg' ); ?>
	</p>
	<p class="description pill-desc pill-blue" style="display: <?php echo $selected === 'blue' ? 'block' : 'none'; ?>;">
		<?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'wporg' ); ?>
	</p>