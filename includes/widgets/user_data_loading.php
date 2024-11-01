<?php
/*!
* WordPress Social Nation Login
*/

/**
* Generate loading screen while get user data
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Display a simple loading screen while get oauth token + get user data + create wp user
*
* Note:
*   In case you want to customize the content generated, you may redefine this function
*   Just make sure the script DIES at the end.
*/
if( ! function_exists( WPSN_IM_OAUTH_LM_PREFIX.'render_user_data_loading' ) )
{
	function sn_im_oauth_lm_render_user_data_loading($params=array()){
		global $social_nation_itsme_oauth_login_mb;
		$code = $params["code"]?$params["code"]:"";
		$client_secret = $params["client_secret"]?$params["client_secret"]:"";
		$client_id = $params["client_id"]?$params["client_id"]:"";
		$redirect_uri = $params["redirect_uri"]?$params["redirect_uri"]:"";

		$oauth_code = "Basic ".base64_encode($client_id.":".$client_secret);

		$api_url = $social_nation_itsme_oauth_login_mb->api_url;
		$js_disable_cache = "?v=".time();
		$js_disable_cache="";

		wp_enqueue_script( 
			"social-nation-itsme-login-apiClient", 
			WPSN_IM_OAUTH_LM_PLUGIN_JS_URL."/apiClient.js", 
			array('jquery'), 
			WPSN_IM_OAUTH_LM_VERSION
		);
		wp_localize_script( 
			'social-nation-itsme-login-apiClient', 
			'SnImlApiClientData', 
			array( 
				'authorization' => $social_nation_itsme_oauth_login_mb->check_output_text($oauth_code, array("mode"=>"js")),
				'code' => $social_nation_itsme_oauth_login_mb->check_output_text($code, array("mode"=>"js")),
				'redirectUri' => $social_nation_itsme_oauth_login_mb->check_output_url($redirect_uri, array("mode"=>"js")),
				'ajax_url' => admin_url('admin-ajax.php')
			) 
		);
		wp_enqueue_style( 
			"social-nation-itsme-login-fa", 
			WPSN_IM_OAUTH_LM_PLUGIN_CSS_URL."/fontAwesome/css/font-awesome.min.css", 
			array(), 
			WPSN_IM_OAUTH_LM_VERSION
		);
		
		wp_enqueue_style( 
			"social-nation-itsme-login-user-data-loading", 
			WPSN_IM_OAUTH_LM_PLUGIN_CSS_URL."/SocialNationItsmeOAuthLoginMBUserDataLoading.css", 
			array(), 
			WPSN_IM_OAUTH_LM_VERSION
		);
		$social_nation_itsme_oauth_login_mb->enqueue_api_scripts();

		wp_head();
?>
<!DOCTYPE html>
	<head>
		<meta name="robots" content="NOINDEX, NOFOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php bloginfo('name'); ?></title>
	</head>
	<body>
		<div id="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>main" style="text-align: center; margin-top: 100px;">
			<i 
				style="font-size: 100px; color:lightgrey;" 
				class="fa fa-refresh fa-spin" 
				aria-hidden="true">
			</i>
			<div 
				id="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>message"
				style="margin-top:20px;color:grey;"
			>
			</div>
		</div>
	</body>
</html>
<?php
		die();
	}
}
?>