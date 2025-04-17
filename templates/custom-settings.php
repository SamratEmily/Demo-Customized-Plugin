<?php
$selected = $options[ $args['label_for'] ] ?? '';
?>

<select
	id="<?php echo esc_attr( $args['label_for'] ); ?>"
	data-custom="<?php echo esc_attr( $args['wporg_custom_data'] ); ?>"
	name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
>
	<option value="red" <?php selected( $selected, 'red' ); ?>>
		<?php esc_html_e( 'Red Pill', 'demo2' ); ?>
	</option>
	<option value="blue" <?php selected( $selected, 'blue' ); ?>>
		<?php esc_html_e( 'Blue Pill', 'demo2' ); ?>
	</option>
</select>

<p class="description pill-desc pill-red" style="display: <?php echo $selected === 'red' ? 'block' : 'none'; ?>;">
	<?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'demo2' ); ?>
</p>
<p class="description pill-desc pill-blue" style="display: <?php echo $selected === 'blue' ? 'block' : 'none'; ?>;">
	<?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'demo2' ); ?>
</p>
