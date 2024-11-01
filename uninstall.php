<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

//options
delete_option( WPSN_IM_OAUTH_LM_PREFIX.'enable_log' );
delete_option( WPSN_IM_OAUTH_LM_PREFIX.'report_interval' );
delete_option( WPSN_IM_OAUTH_LM_PREFIX.'redirect_uri' );
delete_option( WPSN_IM_OAUTH_LM_PREFIX.'client_secret' );
delete_option( WPSN_IM_OAUTH_LM_PREFIX.'client_id' );
delete_option( WPSN_IM_OAUTH_LM_PREFIX.'test_mode' );
//delete_option( WPSN_IM_OAUTH_LM_PREFIX.'button_layout' );
delete_option( WPSN_IM_OAUTH_LM_PREFIX.'button_file_name' );
delete_option( WPSN_IM_OAUTH_LM_PREFIX.'last_report_date' );
delete_option( WPSN_IM_OAUTH_LM_PREFIX.'total_subscriber' );
delete_option( WPSN_IM_OAUTH_LM_PREFIX.'uuid_to_update' );
delete_option( WPSN_IM_OAUTH_LM_PREFIX.'users_to_update' );				
delete_option( WPSN_IM_OAUTH_LM_PREFIX.'default_scope' );		

//user meta
//delete all sn_im_oauth_lm_* usermeta... so risky
//delete sn_im_oauth_lm_identifier, sn_im_oauth_lm_at, sn_im_oauth_lm_rt

?>