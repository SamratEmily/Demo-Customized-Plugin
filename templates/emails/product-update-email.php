<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<p><?php _e( 'Hello Admin,', 'demo' ); ?></p>

<p><?php _e( 'The following products were recently updated:', 'demo' ); ?></p>

<ul>
        <li>
            <strong><?php echo esc_html( $product['title'] ); ?></strong> - 
            <a href="<?php echo esc_url( $product['url']); ?>"><?php _e( 'View updated_products', 'demo' ); ?></a>
            <br>
            <em><?php _e( 'Updated at:', 'demo' ); ?> <?php echo esc_html( $updated_products['time'] ); ?></em>
        </li>
</ul>

<p><?php _e( 'Best regards,', 'demo' ); ?></p>
<p><?php _e( 'Your Website Team', 'demo' ); ?></p>
