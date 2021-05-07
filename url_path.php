<?php
  require_once dirname( __FILE__ ) .'/../../../wp-load.php';
?>
var WP_API_Settings = {
  root       : "<?php echo esc_url_raw(rest_url())?>",
  rest_nonce : "<?php echo wp_create_nonce('wp_rest')?>"
};
