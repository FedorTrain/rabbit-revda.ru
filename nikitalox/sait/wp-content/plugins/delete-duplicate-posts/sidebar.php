<?php

// this is an include only WP file
if ( !defined( 'ABSPATH' ) ) {
    die;
}
?>
<div id="sidebar-container" class="ddp_content_cell">
	<?php 
global  $ddp_fs ;
$ddp_deleted_duplicates = get_option( 'ddp_deleted_duplicates' );

if ( $ddp_deleted_duplicates ) {
    ?>
		<div class="sidebarrow">
			<h3>
				<?php 
    printf(
        /* translators: %s: Number of deleted posts */
        esc_html__( '%s duplicates deleted!', 'delete-duplicate-posts' ),
        esc_html( number_format_i18n( $ddp_deleted_duplicates ) )
    );
    ?>
			</h3>
		</div>
		<?php 
}

?>

	<div class="sidebarrow">
		<p class="warning"><?php 
esc_html_e( 'We recommend you always make a backup before running this tool. Changes are permanent!', 'delete-duplicate-posts' );
?></p>
	</div>

	<div class="sidebarrow">
		<h3>Need help?</h3>
		<p><a href="https://wordpress.org/support/plugin/delete-duplicate-posts/" target="_blank" rel="noopener"><?php 
esc_html_e( 'Support Forum on WordPress.org', 'delete-duplicate-posts' );
?></a></p>
	</div>

<?php 

if ( !$ddp_fs->is_registered() && !$ddp_fs->is_pending_activation() ) {
    ?>
		<div class="sidebarrow">
			<h3><span class="dashicons dashicons-warning"></span> <?php 
    esc_html_e( 'Never miss an important update', 'delete-duplicate-posts' );
    ?></h3>
			<p><?php 
    esc_html_e( 'Opt-in to our security and feature updates notifications, and non-sensitive diagnostic tracking.', 'delete-duplicate-posts' );
    ?></p>
			<p><a href="<?php 
    echo  esc_url( $ddp_fs->get_reconnect_url() ) ;
    ?>" class="button button-secondary"><?php 
    esc_html_e( 'Click here to opt in.', 'delete-duplicate-posts' );
    ?></a></p>
		</div>
		<?php 
}

?>


	<?php 
$display_promotion = true;

if ( $display_promotion ) {
    ?>
		<div class="sidebarrow">
			<hr>
			<h3><span class="dashicons dashicons-star-filled"></span> DDP Pro <span class="dashicons dashicons-star-filled"></span></h3>
			<ul class="linklist">
				<li><strong>New compare method</strong> - Compare by meta tag <span>Use with WooCommerce (compare SKU) or other plugins</span></li>
				<li><strong>Choose post status</strong> - Look for duplicates in scheduled posts, drafts or any other available post status on your site.</li>
				<li><strong>Premium Support</strong> - Get help from the developers behind the plugin.</li>
				<?php 
    /*
    <li>301 Redirect deleted posts <span>Make sure traffic is directed to the right place</span></li>
    */
    ?>
				<li><strong>No ads</strong> - Support the developer :-)</li>	
			</ul>

			<a href="<?php 
    echo  esc_url( $ddp_fs->pricing_url() ) ;
    ?>" class="button button-primary" id="ddpprobutton"><?php 
    echo  'Click here' ;
    ?><span>$14.99 /year</span></a>
			<p><center><em>$14.99/year - discount for more sites</em></center></p>
			<div class="moneybackguarantee">
			<p><strong>Money Back Guarantee!</strong></p>
			<p>You are fully protected by our 100% Money Back Guarantee. If during the next 30 days you experience an issue that makes the plugin unusable and we are unable to resolve it, we'll happily consider offering a full refund of your money.</p>
		</div>
		<hr>
		</div><!-- .sidebarrow -->
		<?php 
}

?>

	<div class="sidebarrow">
		<h3><?php 
esc_html_e( 'Our other plugins', 'delete-duplicate-posts' );
?></h3>
		<a href="https://wpsecurityninja.com" target="_blank" style="float: right;" rel="noopener"><img src="<?php 
echo  esc_url( plugin_dir_url( __FILE__ ) . 'images/security-ninja-logo.png' ) ;
?>" alt="Visit wpsecurityninja.com" class="logo"></a>
		<p>Protect your website with <a href="https://wordpress.org/plugins/security-ninja/" target="_blank" rel="noopener">wordpress.org/plugins/security-ninja/</a><br/>
			<p>Read more on <a href="https://wpsecurityninja.com/">wpsecurityninja.com</a></p>
			<br />
			<a href="https://cleverplugins.com" target="_blank" style="float: right;" rel="noopener"><img src="<?php 
echo  esc_url( plugin_dir_url( __FILE__ ) . 'images/seoboosterlogo.png' ) ;
?>" alt="Visit cleverplugins.com" class="logo"></a>
			<p>SEO Booster is a powerful tool for anyone serious about SEO. <a href="https://wordpress.org/plugins/seo-booster/" target="_blank" rel="noopener">wordpress.org/plugins/seo-booster/</a><br/>
				<p>Read more on <a href="https://cleverplugins.com/">cleverplugins.com</a></p>

			</div><!-- .sidebarrow -->


		</div>
