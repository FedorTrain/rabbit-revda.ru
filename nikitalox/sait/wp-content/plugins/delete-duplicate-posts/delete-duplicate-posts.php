<?php

/*
Plugin Name: Delete Duplicate Posts
Plugin Script: delete-duplicate-posts.php
Plugin URI: https://cleverplugins.com
Description: Remove duplicate blogposts on your blog! Searches and removes duplicate posts and their post meta tags. You can delete posts, pages and other Custom Post Types enabled on your website.
Version: 4.4.8
Author: cleverplugins.com
Author URI: https://cleverplugins.com
Min WP Version: 4.7
Max WP Version: 5.5.3
Text Domain: delete-duplicate-posts
Domain Path: /languages
*/
/*
TODO - Lav så man kan vælge imellem trash og delete (check site opsætning?)
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'ddp_fs' ) ) {
    ddp_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    // Create a helper function for easy SDK access.
    
    if ( !function_exists( 'ddp_fs' ) ) {
        // Create a helper function for easy SDK access.
        function ddp_fs()
        {
            global  $ddp_fs ;
            
            if ( !isset( $ddp_fs ) ) {
                // Activate multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_925_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_925_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                define( 'CP_DDP_FREEMIUS_STATE', 'cp_ddp_freemius_state' );
                // Check anonymous mode.
                $cp_ddp_freemius_state = get_site_option( CP_DDP_FREEMIUS_STATE, 'anonymous' );
                $is_anonymous = 'anonymous' === $cp_ddp_freemius_state || 'skipped' === $cp_ddp_freemius_state;
                $is_premium = false;
                $is_anonymous = ( $is_premium ? false : $is_anonymous );
                $ddp_fs = fs_dynamic_init( array(
                    'id'             => '925',
                    'slug'           => 'delete-duplicate-posts',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_0af9f9e83f00e23728a55430a57dd',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'anonymous_mode' => $is_anonymous,
                    'menu'           => array(
                    'slug'        => 'delete-duplicate-posts.php',
                    'first-path'  => 'admin.php?page=delete-duplicate-posts.php',
                    'support'     => false,
                    'affiliation' => false,
                    'parent'      => array(
                    'slug' => 'tools.php',
                ),
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $ddp_fs;
        }
        
        // Init Freemius.
        ddp_fs();
        // Signal that SDK was initiated.
        do_action( 'ddp_fs_loaded' );
    }
    
    ddp_fs()->add_action( 'after_uninstall', 'ddp_fs_uninstall_cleanup' );
    /**
     * Cleans up when uninstalling
     */
    function ddp_fs_uninstall_cleanup()
    {
        global  $wpdb ;
        $wpdb->query( $wpdb->prepare( 'DROP TABLE IF EXISTS %s', $wpdb->prefix . 'ddp_log' ) );
        delete_option( 'ddp_deleted_duplicates' );
        delete_option( 'delete_duplicate_posts_options_v4' );
        delete_option( 'cp_ddp_freemius_state' );
    }
    
    require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
}

add_action( 'admin_init', array( 'PAnD', 'init' ) );

if ( !class_exists( 'Delete_Duplicate_Posts' ) ) {
    class Delete_Duplicate_Posts
    {
        public static  $options_name = 'delete_duplicate_posts_options_v4' ;
        public  $localization_domain = 'delete-duplicate-posts' ;
        public static  $options = array() ;
        public function __construct()
        {
            // Adds extra permissions to Freemius
            if ( function_exists( 'ddp_fs' ) ) {
                ddp_fs()->add_filter( 'permission_list', array( __CLASS__, 'add_freemius_extra_permission' ) );
            }
            $locale = get_locale();
            $mo = plugin_dir_path( __FILE__ ) . '/languages/delete-duplicate-posts-' . $locale . '.mo';
            load_plugin_textdomain( 'delete-duplicate-posts', false, dirname( __FILE__ ) . '/languages/' );
            add_action(
                'admin_head',
                array( $this, 'set_custom_help_content' ),
                1,
                2
            );
            $this->get_options();
            add_action( 'wp_ajax_ddp_get_loglines', array( $this, 'return_loglines' ) );
            add_action( 'wp_ajax_ddp_get_duplicates', array( $this, 'return_duplicates_ajax' ) );
            add_action( 'wp_ajax_ddp_delete_duplicates', array( $this, 'delete_duplicates' ) );
            add_action( 'wp_ajax_cp_ddp_freemius_opt_in', array( __CLASS__, 'cp_ddp_fs_opt_in' ) );
            // loads persistent admin notices
            add_action( 'admin_init', array( 'PAnD', 'init' ) );
            add_action( 'admin_menu', array( $this, 'admin_menu_link' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
            add_action(
                'wp_insert_site',
                array( $this, 'on_create_blog' ),
                10,
                6
            );
            add_filter( 'wpmu_drop_tables', array( $this, 'on_delete_blog' ) );
            register_activation_hook( __FILE__, array( $this, 'install' ) );
            add_action( 'ddp_cron', array( $this, 'cleandupes' ) );
            add_action( 'cron_schedules', array( $this, 'add_cron_intervals' ) );
            add_action( 'admin_notices', array( $this, 'ddp_action_admin_notices' ) );
        }
        
        /**
         * Ajax callback to handle freemius opt in/out.
         */
        public static function cp_ddp_fs_opt_in()
        {
            // Get post array through filter.
            $nonce = filter_input( INPUT_POST, 'opt_nonce', FILTER_SANITIZE_STRING );
            // Nonce.
            $choice = filter_input( INPUT_POST, 'choice', FILTER_SANITIZE_STRING );
            // Choice selected by user.
            // Verify nonce.
            
            if ( empty($nonce) || !wp_verify_nonce( $nonce, 'cp-ddp-freemius-opt' ) ) {
                // Nonce verification failed.
                echo  wp_json_encode( array(
                    'success' => false,
                    'message' => esc_html__( 'Nonce verification failed.', 'delete-duplicate-posts' ),
                ) ) ;
                exit;
            }
            
            // Check if choice is not empty.
            
            if ( !empty($choice) ) {
                
                if ( 'yes' === $choice ) {
                    
                    if ( !is_multisite() ) {
                        ddp_fs()->opt_in();
                        // Opt in.
                    } else {
                        // Get sites.
                        $sites = Freemius::get_sites();
                        $sites_data = array();
                        if ( !empty($sites) ) {
                            foreach ( $sites as $site ) {
                                $sites_data[] = ddp_fs()->get_site_info( $site );
                            }
                        }
                        ddp_fs()->opt_in(
                            false,
                            false,
                            false,
                            false,
                            false,
                            false,
                            false,
                            false,
                            $sites_data
                        );
                    }
                    
                    // Update freemius state.
                    update_site_option( CP_DDP_FREEMIUS_STATE, 'in' );
                } elseif ( 'no' === $choice ) {
                    
                    if ( !is_multisite() ) {
                        ddp_fs()->skip_connection();
                        // Opt out.
                    } else {
                        ddp_fs()->skip_connection( null, true );
                        // Opt out for all websites.
                    }
                    
                    // Update freemius state.
                    update_site_option( CP_DDP_FREEMIUS_STATE, 'skipped' );
                }
                
                echo  wp_json_encode( array(
                    'success' => true,
                    'message' => esc_html__( 'Freemius opt choice selected.', 'delete-duplicate-posts' ),
                ) ) ;
            } else {
                echo  wp_json_encode( array(
                    'success' => false,
                    'message' => esc_html__( 'Freemius opt choice not found.', 'delete-duplicate-posts' ),
                ) ) ;
            }
            
            exit;
        }
        
        public static function ddp_action_admin_notices()
        {
            $screen = get_current_screen();
            if ( 'tools_page_delete-duplicate-posts' !== $screen->id ) {
                return;
            }
            // Check anonymous mode.
            if ( 'anonymous' === get_site_option( CP_DDP_FREEMIUS_STATE, 'anonymous' ) ) {
                // If user manually opt-out then don't show the notice.
                if ( ddp_fs()->is_anonymous() && ddp_fs()->is_not_paying() && ddp_fs()->has_api_connectivity() ) {
                    if ( !is_multisite() || is_multisite() && is_network_admin() ) {
                        
                        if ( PAnD::is_admin_notice_active( 'cp-ddp-improve-notice-30' ) ) {
                            ?>
						<div id="cp-ddp-freemius" data-dismissible="cp-ddp-improve-notice-30" class="notice notice-success is-dismissible">
							<h3><?php 
                            esc_html_e( 'Help Delete Duplicate Posts improve!', 'delete-duplicate-posts' );
                            ?></h3>

							<p><?php 
                            echo  esc_html__( 'Gathering non-sensitive diagnostic data about the plugin install helps us improve the plugin.', 'delete-duplicate-posts' ) . ' <a href="' . esc_url( 'https://cleverplugins.com/docs/install/non-sensitive-diagnostic-data/' ) . '" target="_blank" rel="noopener">' . esc_html__( 'Read more about what we collect.', 'delete-duplicate-posts' ) . '</a>' ;
                            ?></p>

							<p>
								<?php 
                            // translators:
                            printf( esc_html__( 'If you opt-in, some data about your usage of %1$s will be sent to Freemius.com. If you skip this, that\'s okay! %1$s will still work just fine.', 'delete-duplicate-posts' ), '<b>Delete Duplicate Posts</b>' );
                            ?>
							</p>
							<p>
								<a href="javascript:;" class="button button-primary" onclick="cp_ddp_freemius_opt_in(this)" data-opt="yes"><?php 
                            esc_html_e( 'Sure, opt-in', 'delete-duplicate-posts' );
                            ?></a>

								<a href="javascript:;" class="button dismiss-this"><?php 
                            esc_html_e( 'No, thank you', 'delete-duplicate-posts' );
                            ?></a>
							</p>
							<input type="hidden" id="cp-ddp-freemius-opt-nonce" value="<?php 
                            echo  esc_attr( wp_create_nonce( 'cp-ddp-freemius-opt' ) ) ;
                            ?>" />

						</div>
							<?php 
                        }
                    
                    }
                }
            }
            if ( !PAnD::is_admin_notice_active( 'ddp-leavereview-14' ) ) {
                return;
            }
            $totaldeleted = get_option( 'ddp_deleted_duplicates' );
            
            if ( false !== $totaldeleted && 0 < $totaldeleted ) {
                $totaldeleted = number_format_i18n( $totaldeleted );
                ?>
			<div id="cp-ddp-reviewlink" data-dismissible="ddp-leavereview-14" class="updated notice notice-success is-dismissible">
				<h3>
					<?php 
                // translators: Total number of deleted duplicates
                printf( esc_html__( '%s duplicates deleted!', 'delete-duplicate-posts' ), esc_html( $totaldeleted ) );
                ?>
				</h3>					
				<p>
					<?php 
                // translators: Asking for a review text
                printf( esc_html__( "Hey, I noticed this plugin has deleted %s duplicate posts for you - that's awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.", 'delete-duplicate-posts' ), esc_html( $totaldeleted ) );
                ?>
				</p>

				<p>	
					<a href="https://wordpress.org/support/plugin/delete-duplicate-posts/reviews/?filter=5#new-post" class="cp-ddp-dismiss-review-notice cp-ddp-reviewlink button-primary dismiss-this" target="_blank" rel="noopener">Ok, you deserve it</a>
					<span class="dashicons dashicons-calendar"></span><a href="#" class="cp-ddp-dismiss-review-notice dismiss-this" target="_blank" rel="noopener">Nope, maybe later</a>
					<span class="dashicons dashicons-smiley"></span><a href="#" class="cp-ddp-dismiss-review-notice dismiss-this" target="_blank" rel="noopener">I already did</a>
				</p>
			</div>
				<?php 
            }
        
        }
        
        public static function delete_duplicates( $return = false )
        {
            if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
                check_ajax_referer( 'cp_ddp_delete_loglines' );
            }
            // @todo
            self::log( __( 'Cleaning duplicates', 'delete-duplicate-posts' ) );
            self::cleandupes( true );
            wp_send_json_success();
        }
        
        public static function return_loglines( $return = false )
        {
            if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
                check_ajax_referer( 'cp_ddp_return_loglines' );
            }
            $options = self::get_options();
            global  $ddp_fs ;
            $currstep = ( isset( $_POST['step'] ) ? absint( $_POST['step'] ) : 0 );
            
            if ( !$currstep ) {
                $currstep = 0;
            } else {
                $currstep++;
            }
            
            $json_response = array();
            if ( $currstep ) {
                $json_response['step'] = $currstep;
            }
            global  $wpdb ;
            $dupescount = 0;
            $loglines = $wpdb->get_results( $wpdb->prepare( "\n    SELECT datime, note\n    FROM {$wpdb->prefix}ddp_log\n\t\tORDER BY datime DESC\n    LIMIT 100\n\t\t" ), ARRAY_A );
            
            if ( $loglines ) {
                $json_response['results'] = $loglines;
                wp_send_json_success( $json_response );
            } else {
                $json_response['msg'] = __( 'Error: Log is empty.. do something :-)', 'delete-duplicate' );
                if ( $return ) {
                    return $json_response;
                }
                wp_send_json_error( $json_response );
            }
            
            wp_send_json_success( $json_response );
        }
        
        public static function return_duplicates_ajax( $return = false )
        {
            if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
                check_ajax_referer( 'cp_ddp_return_duplicates' );
            }
            self::return_duplicates( $return );
        }
        
        /**
         * Returns duplicates based on current settings - internal, not used via AJAX
         */
        public static function return_duplicates( $return = false )
        {
            self::timerstart( 'return_duplicates' );
            $options = self::get_options();
            $comparemethod = 'titlecompare';
            $return_duplicates_time = false;
            global  $ddp_fs ;
            
            if ( isset( $currstep ) ) {
                $currstep++;
            } else {
                $currstep = 0;
            }
            
            $json_response = array();
            if ( isset( $currstep ) ) {
                $json_response['step'] = $currstep;
            }
            // @ check compare method - maybe change lookup routine?
            global  $wpdb ;
            $table_name = $wpdb->prefix . 'posts';
            $viewlimit = 25;
            // How many dupes to return @todo - make it editable?
            $ddp_pts_arr = $options['ddp_pts'];
            
            if ( isset( $ddp_pts_arr ) && is_array( $ddp_pts_arr ) ) {
                $ddp_pts = '';
                foreach ( $ddp_pts_arr as $key => $dpa ) {
                    $ddp_pts .= '"' . $dpa . '",';
                }
            } else {
                $ddp_pts = '';
            }
            
            $ddp_pts = rtrim( $ddp_pts, ',' );
            $post_stati = '"publish"';
            $order = $options['ddp_keep'];
            // verify default value has been set
            
            if ( 'oldest' !== $order ) {
                // two choices, if its not the first its the second...
                $options['ddp_keep'] = 'latest';
                $order = 'latest';
            }
            
            if ( 'oldest' === $order ) {
                $minmax = 'MIN(id)';
            }
            if ( 'latest' === $order ) {
                $minmax = 'MAX(id)';
            }
            $dupescount = 0;
            
            if ( '' !== $ddp_pts ) {
                $thisquery = false;
                
                if ( 'titlecompare' === $comparemethod ) {
                    // @todo - prepare - not urgent, there is no way to exploit this query
                    $thisquery = "SELECT a.ID, a.post_title, a.post_type, a.post_status, save_this_post_id\n\t\t\t\t\tFROM {$table_name} AS a\n\t\t\t\t\tINNER JOIN (\n\t\t\t\t\tSELECT post_title, " . $minmax . " AS save_this_post_id\n\t\t\t\t\tFROM {$table_name}\n\t\t\t\t\tWHERE post_type IN(" . $ddp_pts . ')
					AND post_status IN(' . $post_stati . ')
					GROUP BY post_title
					HAVING COUNT( * ) > 1
					) AS b ON b.post_title = a.post_title
					AND b.save_this_post_id <> a.id
					AND a.post_type IN(' . $ddp_pts . ')
					AND a.post_status IN(' . $post_stati . ')';
                    $dupes = $wpdb->get_results( $thisquery, ARRAY_A );
                    
                    if ( $dupes ) {
                        $dupescount = count( $dupes );
                        $json_response['dupescount'] = $dupescount;
                        $stepcount = 0;
                        foreach ( $dupes as $dupe ) {
                            $dupedetails = array(
                                'ID'           => $dupe['ID'],
                                'permalink'    => get_permalink( $dupe['ID'] ),
                                'title'        => get_the_title( $dupe['ID'] ),
                                'type'         => $dupe['post_type'],
                                'orgID'        => $dupe['save_this_post_id'],
                                'orgtitle'     => get_the_title( $dupe['save_this_post_id'] ),
                                'orgpermalink' => get_permalink( $dupe['save_this_post_id'] ),
                                'status'       => $dupe['post_status'],
                                'why'          => '',
                            );
                            $json_response['dupes'][] = $dupedetails;
                            $stepcount++;
                        }
                    }
                
                }
                
                if ( !isset( $json_response['dupescount'] ) ) {
                    $json_response['dupescount'] = 0;
                }
                $return_duplicates_time = self::timerstop( 'return_duplicates' );
                self::log( $json_response['dupescount'] . ' duplicates found in ' . $return_duplicates_time . ' sec.' );
            } else {
                $json_response['msg'] = __( 'Error: Choose post types to check.', 'delete-duplicate-posts' );
                $return_duplicates_time = self::timerstop( 'return_duplicates' );
                $json_response['time'] = $return_duplicates_time . ' sec';
                if ( $return ) {
                    return $json_response;
                }
                wp_send_json_error( $json_response );
            }
            
            if ( !$return_duplicates_time ) {
                $return_duplicates_time = self::timerstop( 'return_duplicates' );
            }
            $json_response['msg'] = $json_response['dupescount'] . ' duplicates found. Showing up to ' . $viewlimit . ' Time: ' . $return_duplicates_time . ' sec.';
            // @todo i8n
            if ( $return ) {
                // todo - limit amount of values to return
                return $json_response;
            }
            // Since we are returning as an ajax response, we are going to limit the amount shown.
            if ( $viewlimit < $json_response['dupescount'] ) {
                $json_response['dupes'] = array_slice( $json_response['dupes'], 0, 10 );
            }
            wp_send_json_success( $json_response );
        }
        
        public static function create_redirect( $inurl, $targeturl, $code = 301 )
        {
            global  $wpdb ;
            // @todo - future
        }
        
        // return default options
        public static function default_options()
        {
            $defaults = array(
                'ddp_running'              => 'false',
                'ddp_keep'                 => 'oldest',
                'ddp_limit'                => 50,
                'ddp_pts'                  => array( 'post', 'page' ),
                'ddp_statusmail_recipient' => '',
                'ddp_statusmail'           => 0,
                'ddp_enabled'              => 0,
                'ddp_pstati'               => array( 'publish' ),
            );
            return $defaults;
        }
        
        // get plugin's options
        public static function get_options()
        {
            $options = get_option( self::$options_name, array() );
            if ( !is_array( $options ) ) {
                $options = array();
            }
            $options = array_merge( self::default_options(), $options );
            return $options;
        }
        
        public static function add_freemius_extra_permission( $permissions )
        {
            $permissions['helpscout'] = array(
                'icon-class' => 'dashicons dashicons-sos',
                'label'      => 'Help Scout',
                'desc'       => __( 'Rendering Help Scouts beacon for easy help and support', 'delete-duplicate-posts' ),
                'priority'   => 16,
            );
            $permissions['newsletter'] = array(
                'icon-class' => 'dashicons dashicons-email-alt2',
                'label'      => 'Newsletter',
                'desc'       => __( 'Your email is added to cleverplugins.com newsletter. Unsubscribe any time.', 'delete-duplicate-posts' ),
                'priority'   => 18,
            );
            return $permissions;
        }
        
        /**
         * Fetch plugin version from plugin PHP header
         *
         * @return string Plugin version
         */
        public static function get_plugin_version()
        {
            $plugin_data = get_file_data( __FILE__, array(
                'version' => 'Version',
            ), 'plugin' );
            return $plugin_data['version'];
        }
        
        public static function timerstart( $watchname )
        {
            set_transient( 'ddp_' . $watchname, microtime( true ), 60 * 60 * 1 );
        }
        
        public static function timerstop( $watchname, $digits = 3 )
        {
            $return = round( microtime( true ) - get_transient( 'ddp_' . $watchname ), $digits );
            delete_transient( 'ddp_' . $watchname );
            return $return;
        }
        
        /**
         * Clean duplicates - not AJAX version
         */
        public static function cleandupes( $manualrun = false )
        {
            self::timerstart( 'ddp_totaltime' );
            // start total timer
            $options = self::get_options();
            $options['ddp_running'] = true;
            self::save_options( $options );
            
            if ( !$manualrun ) {
                self::log( __( 'Cron job running.', 'delete-duplicate-posts' ) );
            } else {
                self::log( __( 'Manually cleaning.', 'delete-duplicate-posts' ) );
            }
            
            $dupes = self::return_duplicates( true );
            $dupescount = count( $dupes );
            $resultnote = '';
            $dispcount = 0;
            if ( isset( $dupes['dupes'] ) ) {
                foreach ( $dupes['dupes'] as $dupe ) {
                    $postid = $dupe['ID'];
                    $title = substr( $dupe['title'], 0, 35 );
                    
                    if ( $postid ) {
                        self::timerstart( 'deletepost_' . $postid );
                        $result = wp_delete_post( $postid, true );
                        $timespent = self::timerstop( 'deletepost_' . $postid );
                        $dispcount++;
                        $totaldeleted = get_option( 'ddp_deleted_duplicates' );
                        
                        if ( false !== $totaldeleted ) {
                            $totaldeleted++;
                            update_option( 'ddp_deleted_duplicates', $totaldeleted );
                        } else {
                            update_option( 'ddp_deleted_duplicates', 1 );
                        }
                        
                        self::log( sprintf(
                            __( "Deleted %1\$s '%2\$s' (id: %3\$s) in %4\$s sec.", 'delete-duplicate-posts' ),
                            $dupe['type'],
                            $title,
                            $postid,
                            $timespent
                        ) );
                    }
                
                }
            }
            
            if ( $dispcount > 0 ) {
                $totaltimespent = self::timerstop( 'ddp_totaltime', 0 );
                // translators: How many posts were deleted and how long it took in seconds
                self::log( sprintf( __( 'A total of %1$s duplicate posts were deleted in %2$s sec.', 'delete-duplicate-posts' ), $dispcount, $totaltimespent ) );
                
                if ( $manualrun > 0 ) {
                    ?>
					<div class="notice notice-success"> 
						<p>
							<?php 
                    // translators:
                    printf( esc_html__( 'A total of %s duplicate posts were deleted.', 'delete-duplicate-posts' ), intval( $dispcount ) );
                    ?>
						</p>
					</div>
					<?php 
                }
                
                // manualrun
            }
            
            // Mail logic...
            
            if ( 0 < $dispcount && $options['ddp_statusmail'] ) {
                $blogurl = site_url();
                $recipient = $options['ddp_statusmail_recipient'];
                // translators:
                $messagebody = sprintf( __( 'Hi Admin, I have deleted <strong>%1$d</strong> posts on your blog, %2$s.', 'delete-duplicate-posts' ), $dispcount, $blogurl );
                $messagebody .= '<br><br>' . __( 'You are receiving this e-mail because you have turned on e-mail notifications by the plugin, Delete Duplicate Posts.' );
                $messagebody .= "<br><br>Made by <a href='https://cleverplugins.com' target='_blank' rel='noopener'>cleverplugins.com</a>";
                $mailstatus = false;
                
                if ( is_email( $recipient ) ) {
                    $mailstatus = wp_mail( $recipient, __( 'Deleted Duplicate Posts Status', 'delete-duplicate-posts' ), $messagebody );
                    if ( $mailstatus ) {
                        // translators:
                        self::log( sprintf( __( 'Status email sent to %s.', 'delete-duplicate-posts' ), $recipient ) );
                    }
                } else {
                    // translators:
                    self::log( sprintf( __( 'Not a vaild email %s.', 'delete-duplicate-posts' ), $recipient ) );
                }
            
            }
            
            $options['ddp_running'] = false;
            self::save_options( $options );
        }
        
        public static function add_cron_intervals( $schedules )
        {
            $schedules['5min'] = array(
                'interval' => 300,
                'display'  => __( 'Every 5 minutes', 'delete-duplicate-posts' ),
            );
            $schedules['10min'] = array(
                'interval' => 600,
                'display'  => __( 'Every 10 minutes', 'delete-duplicate-posts' ),
            );
            $schedules['15min'] = array(
                'interval' => 900,
                'display'  => __( 'Every 15 minutes', 'delete-duplicate-posts' ),
            );
            $schedules['30min'] = array(
                'interval' => 1800,
                'display'  => __( 'Every 30 minutes', 'delete-duplicate-posts' ),
            );
            return $schedules;
        }
        
        public static function log( $text )
        {
            global  $wpdb ;
            $ddp_logtable = $wpdb->prefix . 'ddp_log';
            $wpdb->insert( $ddp_logtable, array(
                'datime' => current_time( 'mysql' ),
                'note'   => $text,
            ), array( '%s', '%s' ) );
            // When over 1000 entries, strip down to 500.
            $total = (int) $wpdb->get_var( "SELECT COUNT(*) FROM `{$ddp_logtable}`;" );
            // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
            
            if ( $total > 10 ) {
                $targettime = $wpdb->get_var( "SELECT `datime` from `{$ddp_logtable}` order by `datime` DESC limit 500,1;" );
                // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                $query = "DELETE from `{$ddp_logtable}`  where `datime` < '{$targettime}';";
                $success = $wpdb->query( $wpdb->prepare( "DELETE from `{$ddp_logtable}`  where `datime` < %s", $targettime ) );
            }
        
        }
        
        /**
         * Enqueues scripts and styles
         */
        public static function admin_enqueue_scripts()
        {
            $screen = get_current_screen();
            
            if ( is_object( $screen ) && 'tools_page_delete-duplicate-posts' === $screen->id ) {
                $pluginver = self::get_plugin_version();
                wp_enqueue_style(
                    'delete-duplicate-posts',
                    plugins_url( '/css/delete-duplicate-posts-min.css', __FILE__ ),
                    array(),
                    $pluginver
                );
                wp_register_script(
                    'delete-duplicate-posts',
                    plugins_url( '/js/delete-duplicate-posts.js', __FILE__ ),
                    array( 'jquery' ),
                    $pluginver,
                    true
                );
                $js_vars = array(
                    'nonce'             => wp_create_nonce( 'cp_ddp_return_duplicates' ),
                    'loglines_nonce'    => wp_create_nonce( 'cp_ddp_return_loglines' ),
                    'deletedupes_nonce' => wp_create_nonce( 'cp_ddp_delete_loglines' ),
                    'text_areyousure'   => __( 'Are you sure you want to delete duplicates? There is no undo feature.', 'delete-duplicate-posts' ),
                );
                wp_localize_script( 'delete-duplicate-posts', 'cp_ddp', $js_vars );
                wp_enqueue_script( 'delete-duplicate-posts' );
            }
        
        }
        
        // Create plugin tables
        public static function create_table()
        {
            global  $wpdb ;
            $table_name = $wpdb->prefix . 'ddp_log';
            
            if ( $table_name !== $wpdb->get_var( "show tables like '{$table_name}'" ) ) {
                $sql = "CREATE TABLE {$table_name} (\n\t\t\t\tid bigint(20) NOT NULL AUTO_INCREMENT,\n\t\t\t\tdatime timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,\n\t\t\t\tnote tinytext NOT NULL,\n\t\t\t\tPRIMARY KEY (id)\n\t\t\t);";
                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
                dbDelta( $sql );
            }
            
            $options = self::get_options();
            self::save_options( $options );
            wp_clear_scheduled_hook( 'ddp_cron' );
            self::log( __( 'Plugin activated.', 'delete-duplicate-posts' ) );
        }
        
        /**
         * Install routines - create database and default options
         */
        public static function install( $network_wide )
        {
            global  $wpdb ;
            require_once ABSPATH . '/wp-admin/includes/upgrade.php';
            
            if ( is_multisite() && $network_wide ) {
                // Get all blogs in the network and activate plugin on each one
                $blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );
                foreach ( $blog_ids as $blog_id ) {
                    switch_to_blog( $blog_id );
                    self::create_table();
                    restore_current_blog();
                }
            } else {
                self::create_table();
            }
        
        }
        
        // Creating table when a new blog is created
        // https://sudarmuthu.com/blog/how-to-properly-create-tables-in-wordpress-multisite-plugins/
        public static function on_create_blog(
            $blog_id,
            $user_id,
            $domain,
            $path,
            $site_id,
            $meta
        )
        {
            
            if ( is_plugin_active_for_network( 'delete-duplicate-posts/delete-duplicate-posts.php' ) ) {
                switch_to_blog( $blog_id );
                self::create_table();
                restore_current_blog();
            }
        
        }
        
        // Deleting the table whenever a blog is deleted
        public static function on_delete_blog( $tables )
        {
            global  $wpdb ;
            $tables[] = $wpdb->prefix . 'ddp_log';
            return $tables;
        }
        
        /**
         * Saves options
         */
        public static function save_options( $newoptions )
        {
            return update_option( 'delete_duplicate_posts_options_v4', $newoptions );
        }
        
        /**
         * Adds link to menu under Tools
         */
        public static function admin_menu_link()
        {
            add_management_page(
                'Delete Duplicate Posts',
                'Delete Duplicate Posts',
                'manage_options',
                basename( __FILE__ ),
                array( 'Delete_Duplicate_Posts', 'admin_options_page' )
            );
            add_filter(
                'plugin_action_links_' . plugin_basename( __FILE__ ),
                array( 'Delete_Duplicate_Posts', 'filter_plugin_actions' ),
                10,
                2
            );
        }
        
        public static function filter_plugin_actions( $links, $file )
        {
            $settings_link = '<a href="tools.php?page=delete-duplicate-posts.php">' . __( 'Settings', 'delete-duplicate-posts' ) . '</a>';
            array_unshift( $links, $settings_link );
            // before other links
            return $links;
        }
        
        /**
         * Adds help content to plugin page
         */
        public static function set_custom_help_content()
        {
            $screen = get_current_screen();
            if ( 'tools_page_delete-duplicate-posts' === $screen->id ) {
                $screen->add_help_tab( array(
                    'id'      => 'ddp_help',
                    'title'   => __( 'Usage and FAQ', 'delete-duplicate-posts' ),
                    'content' => '
						<h4>' . __( 'What does this plugin do?', 'delete-duplicate-posts' ) . '</h4>
						<p>' . __( 'Helps you clean duplicate posts from your blog. The plugin checks for blogposts on your blog with the same title.', 'delete-duplicate-posts' ) . '</p>
						<p>' . __( "It can run automatically via WordPress's own internal CRON-system, or you can run it automatically.", 'delete-duplicate-posts' ) . '</p>
						<p>' . __( 'It also has a nice feature that can send you an e-mail when Delete Duplicate Posts finds and deletes something (if you have turned on the CRON feature).', 'delete-duplicate-posts' ) . '</p>
						<h4>' . __( 'Help! Something was deleted that was not supposed to be deleted!', 'delete-duplicate-posts' ) . '</h4>
						<p>' . __( 'I am sorry for that, I can only recommend you restore the database you took just before you ran this plugin.', 'delete-duplicate-posts' ) . '</p>
						<p>' . __( 'If you run this plugin, manually or automatically, it is at your OWN risk!', 'delete-duplicate-posts' ) . '</p>
						<p>' . __( 'I have done my best to avoid deleting something that should not be deleted, but if it happens, there is nothing I can do to help you.', 'delete-duplicate-posts' ) . "</p>\n\t\t\t\t\t\t<p><a href='https://cleverplugins.com' target='_blank'>cleverplugins.com</a>.</p>",
                ) );
            }
        }
        
        public static function admin_options_page()
        {
            global  $ddp_fs ;
            // DELETE NOW
            if ( isset( $_POST['deleteduplicateposts_delete'] ) && isset( $_POST['_wpnonce'] ) ) {
                
                if ( wp_verify_nonce( $_POST['_wpnonce'], 'ddp-clean-now' ) ) {
                    self::cleandupes( 1 );
                    // use the value 1 to indicate it is being run manually.
                }
            
            }
            // RUN NOW!!
            if ( isset( $_POST['ddp_runnow'] ) ) {
                if ( !wp_verify_nonce( $_POST['_wpnonce'], 'ddp-update-options' ) ) {
                    die( esc_html( __( 'Whoops! Some error occured, try again, please!', 'delete-duplicate-posts' ) ) );
                }
            }
            // SAVING OPTIONS
            
            if ( isset( $_POST['delete_duplicate_posts_save'] ) ) {
                if ( !wp_verify_nonce( $_POST['_wpnonce'], 'ddp-update-options' ) ) {
                    die( esc_html( __( 'Whoops! There was a problem with the data you posted. Please go back and try again.', 'delete-duplicate-posts' ) ) );
                }
                $posttypes = array();
                
                if ( isset( $_POST['ddp_pts'] ) ) {
                    $option_array = $_POST['ddp_pts'];
                    $option_count = count( $option_array );
                    for ( $i = 0 ;  $i < $option_count ;  $i++ ) {
                        $posttypes[] = sanitize_text_field( $option_array[$i] );
                    }
                }
                
                
                if ( isset( $_POST['ddp_enabled'] ) ) {
                    $options['ddp_enabled'] = ( 'on' === $_POST['ddp_enabled'] ? true : false );
                } else {
                    $options['ddp_enabled'] = false;
                }
                
                $options['ddp_statusmail'] = ( isset( $_POST['ddp_statusmail'] ) && 'on' === $_POST['ddp_statusmail'] ? true : false );
                if ( isset( $_POST['ddp_statusmail_recipient'] ) ) {
                    $options['ddp_statusmail_recipient'] = sanitize_text_field( $_POST['ddp_statusmail_recipient'] );
                }
                if ( isset( $_POST['ddp_schedule'] ) ) {
                    $options['ddp_schedule'] = sanitize_text_field( $_POST['ddp_schedule'] );
                }
                if ( isset( $_POST['ddp_keep'] ) ) {
                    $options['ddp_keep'] = sanitize_text_field( $_POST['ddp_keep'] );
                }
                if ( isset( $_POST['ddp_method'] ) ) {
                    $options['ddp_method'] = sanitize_text_field( $_POST['ddp_method'] );
                }
                $options['ddp_pts'] = $posttypes;
                // Previously sanitized
                if ( isset( $_POST['ddp_limit'] ) ) {
                    $options['ddp_limit'] = sanitize_text_field( $_POST['ddp_limit'] );
                }
                self::save_options( $options );
                
                if ( isset( self::$options['ddp_enabled'] ) ) {
                    wp_clear_scheduled_hook( 'ddp_cron' );
                    // @todo - no need to reset every time?
                    $interval = self::$options['ddp_schedule'];
                    if ( !$interval ) {
                        $interval = 'hourly';
                    }
                    wp_schedule_event( time(), $interval, 'ddp_cron' );
                    $nextscheduled = wp_next_scheduled( 'ddp_cron' );
                }
                
                echo  '<div class="notice notice-success is-dismissible"><p>' . esc_html( __( 'Settings saved.', 'delete-duplicate-posts' ) ) . '</p></div>' ;
            }
            
            // CLEARING THE LOG
            
            if ( isset( $_POST['ddp_clearlog'] ) ) {
                if ( !wp_verify_nonce( $_POST['_wpnonce'], 'ddp_clearlog_nonce' ) ) {
                    die( esc_html( __( 'Whoops! Some error occured, try again, please!', 'delete-duplicate-posts' ) ) );
                }
                global  $wpdb ;
                $table_name_log = $wpdb->prefix . 'ddp_log';
                $wpdb->query( "TRUNCATE {$table_name_log};" );
                echo  '<div class="updated"><p>' . esc_html( __( 'The log was cleared.', 'delete-duplicate-posts' ) ) . '</p></div>' ;
                unset( $wpdb );
            }
            
            global  $wpdb ;
            $table_name = $wpdb->prefix . 'posts';
            $pluginfo = get_plugin_data( __FILE__ );
            $version = $pluginfo['Version'];
            $name = $pluginfo['Name'];
            $options = self::get_options();
            ?>

			<div class="wrap">

				<h2>Delete Duplicate Posts <span>v. <?php 
            echo  esc_html( self::get_plugin_version() ) ;
            ?></span></h2>	

				<div class="ddp_content_wrapper">
					<div class="ddp_content_cell">
						<div id="ddp_container">
							<div id="dashboard">
								<?php 
            
            if ( $options['ddp_enabled'] ) {
                $nextscheduled = wp_next_scheduled( 'ddp_cron' );
                
                if ( '' !== $nextscheduled ) {
                    // plugin active, but the cron needs to be activated also..
                    $interval = $options['ddp_schedule'];
                    if ( !$interval ) {
                        $interval = 'hourly';
                    }
                    wp_schedule_event( time(), $interval, 'ddp_cron' );
                    $nextscheduled = wp_next_scheduled( 'ddp_cron' );
                }
            
            } else {
                wp_clear_scheduled_hook( 'ddp_cron' );
            }
            
            $totaldeleted = get_option( 'ddp_deleted_duplicates' );
            ?>

								<div class="statusdiv">
									<div class="spinner is-active"></div>	
									<div class="statusmessage"></div>
									<div class="dupelist">
										<table class="wp-list-table widefat fixed striped posts duplicatetable" cellspacing="0">
											<thead>
												<tr>
													<th><?php 
            esc_html_e( 'Duplicate', 'delete-duplicate-posts' );
            ?></th>
													<th><?php 
            esc_html_e( 'Original', 'delete-duplicate-posts' );
            ?></th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>

									</div>
									<form>
										<?php 
            wp_nonce_field( 'ddp-buttons' );
            ?>
										<table id="ddp_buttons">
											<tr>
												<td><input type="submit" name="deleteduplicateposts_resetview" id="deleteduplicateposts_resetview"class="button button-small button-secondary" value="<?php 
            esc_html_e( 'Refresh list', 'delete-duplicate-posts' );
            ?>" disabled/></td>
												<td><input type="submit" name="deleteduplicateposts_deleteall" id="deleteduplicateposts_deleteall" class="button button-small button-primary" value="<?php 
            esc_html_e( 'Delete duplicates', 'delete-duplicate-posts' );
            ?>" disabled/></td>
											</tr>
										</table>
									</form>
								</div>

							</div><!-- #dashboard -->

							<div id="configuration">
								<h3><?php 
            esc_html_e( 'Settings', 'delete-duplicate-posts' );
            ?></h3>
								<p>
									<?php 
            $nextscheduled = wp_next_scheduled( 'ddp_cron' );
            
            if ( $nextscheduled ) {
                ?>
										<div class="notice notice-info is-dismissible">
											<?php 
                echo  '<p class="cronstatus center">' . esc_html__( 'You have enabled automatic deletion, so I am running on automatic. I will take care of everything...', 'delete-duplicate-posts' ) . '</p>' ;
                echo  '<p class="center">' ;
                echo  sprintf(
                    // translators: Showing when the next check happens and what the current time is
                    esc_html( __( 'Next automated check %1$s. Current time %2$s', 'delete-duplicate-posts' ) ),
                    '<strong>' . esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $nextscheduled ) ) . '</strong>',
                    '<strong>' . esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), time() ) ) . '</strong>'
                ) ;
                echo  '</p>' ;
                ?>
										</div>
										<?php 
            }
            
            ?>
								</p>
								<form method="post" id="delete_duplicate_posts_options">
									<?php 
            wp_nonce_field( 'ddp-update-options' );
            ?>
									<table width="100%" cellspacing="2" cellpadding="5" class="form-table">

										<tr valign="top">
											<th><label for="ddp_enabled"><?php 
            esc_html_e( 'Which post types?:', 'delete-duplicate-posts' );
            ?></label></th>
											<td>
												<?php 
            $builtin = array( 'post', 'page', 'attachment' );
            $args = array(
                'public'   => true,
                '_builtin' => false,
            );
            $output = 'names';
            $operator = 'and';
            $post_types = get_post_types( $args, $output, $operator );
            $post_types = array_merge( $builtin, $post_types );
            $checked_post_types = $options['ddp_pts'];
            
            if ( $post_types ) {
                ?>
													<ul class="radio">
														<?php 
                $step = 0;
                if ( !is_array( $checked_post_types ) ) {
                    $checked_post_types = array();
                }
                foreach ( $post_types as $pt ) {
                    $checked = array_search( $pt, $checked_post_types, true );
                    ?>
															<li>
																<input type="checkbox" name="ddp_pts[]" id="ddp_pt-<?php 
                    echo  esc_attr( $step ) ;
                    ?>" value="<?php 
                    echo  esc_html( $pt ) ;
                    ?>" 
																<?php 
                    if ( false !== $checked ) {
                        echo  ' checked' ;
                    }
                    ?>
																	/>
																	<label for="ddp_pt-<?php 
                    echo  esc_attr( $step ) ;
                    ?>"><?php 
                    echo  esc_html( $pt ) ;
                    ?></label>
																</li>
																<?php 
                    $step++;
                }
                ?>
														</ul>
														<?php 
            }
            
            ?>
													<p class="description"><?php 
            esc_html_e( 'Choose which post types to scan for duplicates.', 'delete-duplicate-posts' );
            ?></p>
												</td>
											</tr>

											<tr>
												<th><label for="ddp_enabled"><?php 
            esc_html_e( 'Post status', 'delete-duplicate-posts' );
            ?></label></th>
												<td>
													<?php 
            $stati = array(
                'publish' => (object) array(
                'label'                     => 'Published',
                'show_in_admin_status_list' => true,
            ),
            );
            $checked_post_stati = $options['ddp_pstati'];
            
            if ( $stati ) {
                ?>
														<ul class="radio">
															<?php 
                foreach ( $stati as $key => $st ) {
                    
                    if ( $st->show_in_admin_status_list ) {
                        $checked = array_search( $key, $checked_post_stati, true );
                        ?>
																	<li>
																		<input type="checkbox" name="ddp_pstati[]" id="ddp_pstatus-<?php 
                        echo  esc_attr( $key ) ;
                        ?>" value="<?php 
                        echo  esc_attr( $key ) ;
                        ?>" 
																		<?php 
                        if ( false !== $checked ) {
                            echo  ' checked' ;
                        }
                        ?>
																			/>
																			<label for="ddp_pstatus-<?php 
                        echo  esc_attr( $key ) ;
                        ?>"><?php 
                        echo  esc_html( $key . ' (' . $st->label . ')' ) ;
                        ?></label>
																		</li>
																		<?php 
                        $step++;
                    }
                
                }
                ?>
															</ul>
															<?php 
            }
            
            ?>
													</td>


												</tr>
												<?php 
            $comparemethod = 'titlecompare';
            global  $ddp_fs ;
            ?>
												<tr valign="top">
													<th><?php 
            esc_html_e( 'Comparison Method', 'delete-duplicate-posts' );
            ?></th>
													<td>
														<ul class="ddpcomparemethod">

															<li>
																<label>
																	<input type="radio" name="ddp_method" value="titlecompare" <?php 
            checked( 'titlecompare', $comparemethod );
            ?> /> <?php 
            esc_html_e( 'Compare by title (default)', 'delete-duplicate-posts' );
            ?>
																	<span class="optiondesc"><?php 
            esc_html_e( 'Looks at the title of the post itself.', 'delete-duplicate-posts' );
            ?></span>
																</label>

															</li>

															<?php 
            global  $ddp_fs ;
            ?>
														</ul>
													</td>
												</tr>
												<tr>
													<th><label for="ddp_keep"><?php 
            esc_html_e( 'Delete which posts?:', 'delete-duplicate-posts' );
            ?></label></th><td>

														<select name="ddp_keep" id="ddp_keep">
															<option value="oldest" 
															<?php 
            if ( 'oldest' === $options['ddp_keep'] ) {
                echo  'selected="selected"' ;
            }
            ?>
																><?php 
            esc_html_e( 'Keep oldest', 'delete-duplicate-posts' );
            ?></option>
																<option value="latest" 
																<?php 
            if ( 'latest' === $options['ddp_keep'] ) {
                echo  'selected="selected"' ;
            }
            ?>
																	><?php 
            esc_html_e( 'Keep latest', 'delete-duplicate-posts' );
            ?></option>
																</select>
																<p class="description"><?php 
            esc_html_e( 'Keep the oldest or the latest version of duplicates? Default is keeping the oldest, and deleting any subsequent duplicate posts', 'delete-duplicate-posts' );
            ?></p>
															</td>
														</tr>


														<?php 
            /*
            
            <tr valign="top">
            <th><label for="ddp_limit"><?php esc_html_e( 'Delete at maximum :', 'delete-duplicate-posts' ); ?></label></th><td><select name="ddp_limit">
            	<?php
            	for ( $x = 1; $x <= 10; $x++ ) {
            		$val = ( $x * 50 );
            		echo "<option value='" . esc_attr( $val ) . "' ";
            		if ( $options['ddp_limit'] === $val ) {
            			echo 'selected';
            		}
            		echo '>' . esc_attr( $val ) . '</option>';
            	}
            	?>
            </select>
            <?php esc_html_e( 'duplicates.', 'delete-duplicate-posts' ); ?>
            <p class="description"><?php esc_html_e( 'Setting a limit is a good idea, especially if your site is on a busy server.', 'delete-duplicate-posts' ); ?></p>
            </td>
            </tr>
            */
            ?>

														<tr>
															<td colspan="2"><hr></td>
														</tr>													



														<tr valign="top">
															<th><?php 
            esc_html_e( 'Enable automatic deletion?:', 'delete-duplicate-posts' );
            ?>

														</th>
														<td><label for="ddp_enabled">
															<input type="checkbox" id="ddp_enabled" name="ddp_enabled" 
															<?php 
            if ( true === $options['ddp_enabled'] ) {
                echo  'checked="checked"' ;
            }
            ?>
																>
																<p class="description"><?php 
            esc_html_e( 'Clean duplicates automatically.', 'delete-duplicate-posts' );
            ?></p>
															</label>
														</td>
													</tr>


													<tr>
														<th><label for="ddp_schedule"><?php 
            esc_html_e( 'How often?:', 'delete-duplicate-posts' );
            ?></label></th><td>

															<select name="ddp_schedule" id="ddp_schedule">
																<?php 
            $schedules = wp_get_schedules();
            if ( $schedules ) {
                foreach ( $schedules as $key => $sch ) {
                    ?>
																		<option value="<?php 
                    echo  esc_attr( $key ) ;
                    ?>" 
																			<?php 
                    if ( isset( $options['ddp_schedule'] ) && esc_attr( $key ) === $options['ddp_schedule'] ) {
                        echo  esc_html( 'selected="selected"' ) ;
                    }
                    ?>
																			><?php 
                    echo  esc_html( $sch['display'] ) ;
                    ?></option>
																			<?php 
                }
            }
            ?>
																</select>
																<p class="description"><?php 
            esc_html_e( 'How often should the cron job run?', 'delete-duplicate-posts' );
            ?></p>
															</td>
														</tr>



														<tr>
															<td colspan="2"><hr></td>
														</tr>		

														<tr>
															<th><?php 
            esc_html_e( 'Send status mail?:', 'delete-duplicate-posts' );
            ?></th>
															<td>
																<label for="ddp_statusmail">
																	<input type="checkbox" id="ddp_statusmail" name="ddp_statusmail" 
																	<?php 
            if ( isset( $options['ddp_statusmail'] ) && true === $options['ddp_statusmail'] ) {
                echo  'checked="checked"' ;
            }
            ?>
																		<p class="description"><?php 
            esc_html_e( 'Sends a status email if duplicates have been found.', 'delete-duplicate-posts' );
            ?></p>
																	</label>
																</td>
															</tr>

															<tr>
																<th><?php 
            esc_html_e( 'Email recipient:', 'delete-duplicate-posts' );
            ?></th>
																<td>
																	<label for="ddp_statusmail_recipient">

																		<input type="text" class="regular-text" id="ddp_statusmail_recipient" name="ddp_statusmail_recipient" value="<?php 
            echo  esc_html( $options['ddp_statusmail_recipient'] ) ;
            ?>">
																		<p class="description"><?php 
            esc_html_e( 'Who should get the notification email.', 'delete-duplicate-posts' );
            ?></p>
																	</label>
																</td>
															</tr>




															<th colspan=2><input type="submit" class="button-primary" name="delete_duplicate_posts_save" value="<?php 
            esc_html_e( 'Save Settings', 'delete-duplicate-posts' );
            ?>" /></th>
														</tr>

													</table>

												</form>
											</div><!-- #configuration -->


											<div id="log">

												<h3><?php 
            esc_html_e( 'The Log', 'delete-duplicate-posts' );
            ?></h3>
												<div class="spinner is-active"></div>
												<ul class="large-text" name="ddp_log" id="ddp_log">
												</ul>

											</div>
											<p>
												<form method="post" id="ddp_clearlog">
													<?php 
            wp_nonce_field( 'ddp_clearlog_nonce' );
            ?>

													<input class="button-secondary" type="submit" name="ddp_clearlog" value="<?php 
            esc_html_e( 'Reset log', 'delete-duplicate-posts' );
            ?>" />
												</form>
											</p>

										</div><!-- #ddp_container -->

										<?php 
            include_once 'sidebar.php';
            ?>

										<?php 
            if ( function_exists( 'ddp_fs' ) ) {
                global  $ddp_fs ;
            }
            ?>

									</div>
								</div>

							</div>
							<?php 
        }
    
    }
    //End Class
}

if ( class_exists( 'Delete_Duplicate_Posts' ) ) {
    $delete_duplicate_posts_var = new Delete_Duplicate_Posts();
}