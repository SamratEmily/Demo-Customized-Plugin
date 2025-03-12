<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

Hello Admin,

The following products were recently updated:


- <?php echo esc_html( $product['title'] ); ?> (Updated at: <?php echo esc_html( $product['time'] ); ?>)
  View Product: <?php echo esc_url( $product['url'] ); ?>


Best regards,  
Your Website Team
