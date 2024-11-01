<?php
	/* 
		Plugin Name: Social Nation It'sMe OAuth Login Multibutton
		Description: A plugin by Social Nation to login with It'sME account 
		Version: 1.2.6
		Author: Social Nation S.R.L.
		Author URI: http://www.socialnation.it
		License: GPL2

		Social Nation It'sMe OAuth Login Multibutton is free software: you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation, either version 2 of the License, or
		any later version.
		 
		Social Nation It'sMe OAuth Login Multibutton is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
		GNU General Public License for more details.
		 
		You should have received a copy of the GNU General Public License
		along with Social Nation It'sMe OAuth Login Multibutton. If not, see http://www.gnu.org/licenses/gpl.txt.
	*/

	//change version in above comment, readme.txt and define( 'WPSN_IM_OAUTH_LM_VERSION')
	define( 'WPSN_IM_OAUTH_LM_VERSION', '1.2.6' );
	//define( 'WPSN_IM_OAUTH_LM_VERSION', rand() );

	define( 'WPSN_IM_OAUTH_LM_REQUIRED_WP_VERSION', '4.4' );

	define( 'WPSN_IM_OAUTH_LM_PLUGIN', __FILE__ );

	define( 'WPSN_IM_OAUTH_LM_PLUGIN_BASENAME', plugin_basename( WPSN_IM_OAUTH_LM_PLUGIN ) );

	define( 'WPSN_IM_OAUTH_LM_PLUGIN_NAME', trim( dirname( WPSN_IM_OAUTH_LM_PLUGIN_BASENAME ), '/' ) );

	define( 'WPSN_IM_OAUTH_LM_PLUGIN_DIR', untrailingslashit( dirname( WPSN_IM_OAUTH_LM_PLUGIN ) ) );

	//constants
	require_once WPSN_IM_OAUTH_LM_PLUGIN_DIR . '/includes/constants.php';

	//define( 'WPSN_IM_OAUTH_LM_PLUGIN_URL', site_url()."/wp-content/plugins/".WPSN_IM_OAUTH_LM_PLUGIN_SLUG );
	define( 'WPSN_IM_OAUTH_LM_PLUGIN_URL', plugins_url(WPSN_IM_OAUTH_LM_PLUGIN_SLUG) );
	define( 'WPSN_IM_OAUTH_LM_PLUGIN_JS_URL', WPSN_IM_OAUTH_LM_PLUGIN_URL."/assets/js" );
	define( 'WPSN_IM_OAUTH_LM_PLUGIN_CSS_URL', WPSN_IM_OAUTH_LM_PLUGIN_URL."/assets/css" );
	define( 'WPSN_IM_OAUTH_LM_PLUGIN_IMAGES_URL', WPSN_IM_OAUTH_LM_PLUGIN_URL."/assets/images" );

	define( 'WPSN_IM_OAUTH_LM_ITSME_BASE_URL', "https://www.itsme.it");

	define( 'WPSN_IM_OAUTH_LM_ITSME_BASE_URL_TEST', "https://test.itsme.it");
	//define( 'WPSN_IM_OAUTH_LM_ITSME_BASE_URL_TEST', "https://dev.itsme.it");
	//define( 'WPSN_IM_OAUTH_LM_ITSME_BASE_URL_TEST', "local_ip");


	//define( 'WPSN_IM_OAUTH_LM_ITSME_URL', "https://www.itsme.it/app/?mode=oauth2login&response_type=code&redirect_uri=https://www.socialnation.it/&scope=basic");
	define( 'WPSN_IM_OAUTH_LM_ITSME_URL', WPSN_IM_OAUTH_LM_ITSME_BASE_URL."/app");

	define( 'WPSN_IM_OAUTH_LM_ITSME_URL_TEST', WPSN_IM_OAUTH_LM_ITSME_BASE_URL_TEST."/app");

	define( 'WPSN_IM_OAUTH_LM_ITSME_API', "APIGateway/partnerSDK/prod/apiGateway-js-sdk");
	define( 'WPSN_IM_OAUTH_LM_ITSME_API_TEST', "APIGateway/partnerSDK/test/apiGateway-js-sdk");

	//define( 'WPSN_IM_OAUTH_LM_ITSME_REQUEST_CODE_URL', WPSN_IM_OAUTH_LM_ITSME_URL."/?mode=oauth2login&response_type=code&scope=");
	//define( 'WPSN_IM_OAUTH_LM_ITSME_REQUEST_CODE_URL_TEST', WPSN_IM_OAUTH_LM_ITSME_URL_TEST."/?mode=oauth2login&response_type=code&scope=");
	define( 
		'WPSN_IM_OAUTH_LM_ITSME_REQUEST_CODE_URL', 
		WPSN_IM_OAUTH_LM_ITSME_URL."/?mode=authlogin&response_type=code&scope="
	);
	define( 
		'WPSN_IM_OAUTH_LM_ITSME_REQUEST_CODE_URL_TEST', 
		WPSN_IM_OAUTH_LM_ITSME_URL_TEST."/?mode=authlogin&response_type=code&scope="
	);

	define( 'WPSN_IM_OAUTH_LM_ITSME_API_URL', WPSN_IM_OAUTH_LM_ITSME_URL."/".WPSN_IM_OAUTH_LM_ITSME_API);
	define( 'WPSN_IM_OAUTH_LM_ITSME_API_URL_TEST', WPSN_IM_OAUTH_LM_ITSME_URL_TEST."/".WPSN_IM_OAUTH_LM_ITSME_API_TEST);

	define( 'WPSN_IM_OAUTH_LM_LOG_FILE_NAME', WPSN_IM_OAUTH_LM_PLUGIN_SLUG.".log");
	define( 'WPSN_IM_OAUTH_LM_USER_REPORT_LOG_FILE_NAME', WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-user-report.log");
	define( 'WPSN_IM_OAUTH_LM_LOG_FOLDER_NAME', WPSN_IM_OAUTH_LM_PLUGIN_SLUG."");

	//this is to avoid php 5.x compile error (direct access to array function result)
	$WPSN_IM_OAUTH_LM_WP_UPLOAD_DIR_ARRAY = wp_upload_dir();
	define( 'WPSN_IM_OAUTH_LM_LOG_DIR_PATH', $WPSN_IM_OAUTH_LM_WP_UPLOAD_DIR_ARRAY["basedir"]."/".WPSN_IM_OAUTH_LM_LOG_FOLDER_NAME);
	define( 'WPSN_IM_OAUTH_LM_LOG_FILE_PATH', WPSN_IM_OAUTH_LM_LOG_DIR_PATH."/".WPSN_IM_OAUTH_LM_LOG_FILE_NAME);
	define( 'WPSN_IM_OAUTH_LM_USER_REPORT_LOG_FILE_PATH', WPSN_IM_OAUTH_LM_LOG_DIR_PATH."/".WPSN_IM_OAUTH_LM_USER_REPORT_LOG_FILE_NAME);

	define( 'WPSN_IM_OAUTH_LM_ACTION_AUTHENTICATED', "social_nation_oauth_login_mb_authenticated");
	define( 'WPSN_IM_OAUTH_LM_REDIRECT_URI_ACTION_AUTHENTICATED', "action=".WPSN_IM_OAUTH_LM_ACTION_AUTHENTICATED);
	define( 'WPSN_IM_OAUTH_LM_ACTION_AUTHENTICATE', "social_nation_oauth_login_mb_authenticate");
	define( 'WPSN_IM_OAUTH_LM_REDIRECT_URI_ACTION_AUTHENTICATE', "action=".WPSN_IM_OAUTH_LM_ACTION_AUTHENTICATE);

	define( 'WPSN_IM_OAUTH_LM_LOGIN_BUTTONS_IMAGE_URL', WPSN_IM_OAUTH_LM_PLUGIN_IMAGES_URL."/button");
	define( 'WPSN_IM_OAUTH_LM_LOGIN_BUTTONS_IMAGE_DIR', WPSN_IM_OAUTH_LM_PLUGIN_DIR."/assets/images/button");

	//define( 'WPSN_IM_OAUTH_LM_BUTTON_LAYOUT_BUTTON_ICON', "button_icon");
	//define( 'WPSN_IM_OAUTH_LM_BUTTON_LAYOUT_BUTTON_WITH_TEXT', "button_with_text");
	//define( 'WPSN_IM_OAUTH_LM_BUTTON_LAYOUT_BUTTON_ICON_LIGHT', "button_icon_light");
	//define( 'WPSN_IM_OAUTH_LM_BUTTON_LAYOUT_BUTTON_WITH_TEXT_LIGHT', "button_with_text_light");

	//ADMIN UI
	define( 'WPSN_IM_OAUTH_LM_ADMIN_PAGE_TITLE', "Social Nation It'sMe OAuth Login Multibutton");
	define( 'WPSN_IM_OAUTH_LM_ADMIN_MENU_TITLE', "Social Nation It'sMe OAuth Login Multibutton");
	define( 'WPSN_IM_OAUTH_LM_ADMIN_MENU_SLUG', 'socialnation_itsme_oauth_login_mb_options');
	//tab 
	define( 'WPSN_IM_OAUTH_LM_ADMIN_TAB_GENERAL', "general");
	define( 'WPSN_IM_OAUTH_LM_ADMIN_TAB_REPORT', "report");

	define( 'WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_DAILY', "Giornaliero");
	define( 'WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_DAILY_VALUE', 86400);
	define( 'WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_WEEKLY', "Settimanale");
	define( 'WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_WEEKLY_VALUE', 604800);
	define( 'WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_MONTHLY', "Mensile");
	define( 'WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_MONTHLY_VALUE', 2635200);
	//not used - debug only
	define( 'WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_10_SECONDS', "10 secondi");
	define( 'WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_10_SECONDS_VALUE', 10);
	define( 'WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_30_SECONDS', "30 secondi");
	define( 'WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_30_SECONDS_VALUE', 30);
	define( 'WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_60_SECONDS', "60 secondi");
	define( 'WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_60_SECONDS_VALUE', 60);
	define( 'WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_5_MINUTES', "5 minuti");
	define( 'WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_5_MINUTES_VALUE', 300);

	//define( 'WPSN_IM_OAUTH_LM_ITSME_BUTTON_LIST_FILE', "http://staging2.socialnation.it/wp-content/uploads/2017/11/plugin_image_list.txt");
	//define( 'WPSN_IM_OAUTH_LM_ITSME_BUTTON_LIST_FILE_TEST', "http://staging2.socialnation.it/wp-content/uploads/2017/11/plugin_image_list.txt");
	define( 'WPSN_IM_OAUTH_LM_ITSME_BUTTON_LIST_FILE', WPSN_IM_OAUTH_LM_ITSME_BASE_URL."/static/txt/plugin_image_list.txt");
	define( 'WPSN_IM_OAUTH_LM_ITSME_BUTTON_LIST_FILE_TEST', WPSN_IM_OAUTH_LM_ITSME_BASE_URL_TEST."/static/txt/plugin_image_list.txt");

	//shortcodes
	require_once WPSN_IM_OAUTH_LM_PLUGIN_DIR . '/includes/shortcodes.php';
	require_once WPSN_IM_OAUTH_LM_PLUGIN_DIR . '/includes/ajax.php';
	//static classes
	require_once WPSN_IM_OAUTH_LM_PLUGIN_DIR . '/includes/user.php';
	//widgets
	require_once WPSN_IM_OAUTH_LM_PLUGIN_DIR . '/includes/widgets/user_data_loading.php';
	//utils
	require_once WPSN_IM_OAUTH_LM_PLUGIN_DIR . '/includes/utils.php';

	class SocialNationItsMeOAuthLoginMB {
		function __construct() {
			$this->getAllOptions();
		}

		function getAllOptions(){
			$this->plugin_version = get_option(WPSN_IM_OAUTH_LM_PREFIX."plugin_version");
			$this->enable_log = get_option(WPSN_IM_OAUTH_LM_PREFIX."enable_log");
			$this->report_interval = get_option(WPSN_IM_OAUTH_LM_PREFIX."report_interval");
			if(!$this->report_interval){
				$this->report_interval = WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_MONTHLY_VALUE;
			}
			$this->last_report_date = get_option(WPSN_IM_OAUTH_LM_PREFIX."last_report_date");
			if($this->last_report_date){
				$this->last_report_date_strong_with_timezone = SocialNationItsMeOAuthLoginMBUtils::convertDateToLocale(array(
					"dateTime" => $this->last_report_date,
					"useLocalTimeZone" => true,
					"printTimezone" => true
				));
			}
			$this->default_scope = get_option(WPSN_IM_OAUTH_LM_PREFIX."default_scope");
			$this->redirect_uri = get_option(WPSN_IM_OAUTH_LM_PREFIX."redirect_uri");
			$this->client_secret = get_option(WPSN_IM_OAUTH_LM_PREFIX."client_secret");
			$this->client_id = get_option(WPSN_IM_OAUTH_LM_PREFIX."client_id");
			$this->test_mode = get_option(WPSN_IM_OAUTH_LM_PREFIX."test_mode");
			//$this->button_layout = get_option(WPSN_IM_OAUTH_LM_PREFIX."button_layout");
			$this->button_file_name = get_option(WPSN_IM_OAUTH_LM_PREFIX."button_file_name");
			$this->total_subscriber = get_option(WPSN_IM_OAUTH_LM_PREFIX."total_subscriber");
			//last list of uuid returned by userreport 
			$this->uuid_to_update = get_option(WPSN_IM_OAUTH_LM_PREFIX."uuid_to_update");
			//last list of readable wp_users returned by userreport - a sort of cache
			$this->users_to_update = get_option(WPSN_IM_OAUTH_LM_PREFIX."users_to_update");
			if($this->test_mode){
				$this->api_url = WPSN_IM_OAUTH_LM_ITSME_API_URL_TEST;
			}
			else{
				$this->api_url = WPSN_IM_OAUTH_LM_ITSME_API_URL;
			}

			if($this->test_mode){
				$this->itsme_login_url = WPSN_IM_OAUTH_LM_ITSME_REQUEST_CODE_URL_TEST;
			}
			else{
				$this->itsme_login_url = WPSN_IM_OAUTH_LM_ITSME_REQUEST_CODE_URL;
			}
			$this->return_url = get_option(WPSN_IM_OAUTH_LM_PREFIX."return_url");
		}

		function getRedirectUri(){
			return $this->redirect_uri."?".WPSN_IM_OAUTH_LM_REDIRECT_URI_ACTION_AUTHENTICATED;
		}

		/**
			Check technical requirements before activating the plugin 

		*/
		function activate (){
			//here we can check if we have update the version
			//if(WPSN_IM_OAUTH_LM_VERSION>$this->plugin_version)
			update_option (
				WPSN_IM_OAUTH_LM_PREFIX.'plugin_version', 
				WPSN_IM_OAUTH_LM_VERSION
			);
			if($this->default_scope===false){
				update_option (
					WPSN_IM_OAUTH_LM_PREFIX.'default_scope', 
					"basic"
				);
			}
			if($this->redirect_uri===false){
				update_option (
					WPSN_IM_OAUTH_LM_PREFIX.'redirect_uri', 
					site_url()."/"
				);
			}
			if($this->report_interval===false){
				update_option (
					WPSN_IM_OAUTH_LM_PREFIX.'report_interval', 
					WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_MONTHLY_VALUE
				);
			}
			//if($this->button_layout===false){
			//	update_option (
			//		WPSN_IM_OAUTH_LM_PREFIX.'button_layout', 
			//		WPSN_IM_OAUTH_LM_BUTTON_LAYOUT_BUTTON_WITH_TEXT
			//	);
			//}
			if($this->button_file_name===false){
				$files = scandir(WPSN_IM_OAUTH_LM_LOGIN_BUTTONS_IMAGE_DIR);
				$defaultButtonImageFile = "";
				foreach($files as $buttonImageFile){
					if($buttonImageFile=="."||$buttonImageFile=="..")
						continue;
					$defaultButtonImageFile = $buttonImageFile;
					break;
				}
				update_option (
					WPSN_IM_OAUTH_LM_PREFIX.'button_file_name', 
					$defaultButtonImageFile
				);
				//$this->button_file_name = get_option(WPSN_IM_OAUTH_LM_PREFIX."button_file_name");
			}

			if($this->last_report_date===false){
				//if last_report_date not setted we set it to now
				$lastReportDate = new DateTime();
				//$lastReportDate->modify("-2 month");
				update_option (
					WPSN_IM_OAUTH_LM_PREFIX.'last_report_date', 
					$lastReportDate
				);
			}
			if ( ! file_exists( WPSN_IM_OAUTH_LM_LOG_DIR_PATH ) ) {
			    wp_mkdir_p( WPSN_IM_OAUTH_LM_LOG_DIR_PATH );
			}
		}

		/**
		 * Add Setup Link
		 **/
		function add_setup_link ($links){
			$settings_link = 
				'<a href="admin.php?page='.WPSN_IM_OAUTH_LM_ADMIN_MENU_SLUG.'">' . 
					__('Settings') . 
				'</a>';
			array_unshift($links, $settings_link);

			return $links;
		}

		function sn_im_oauth_lm_process_login(){
			//first check for refresh user data
			$this->sn_im_oauth_lm_refresh_user_data();
			//oauth return with code
			$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : null;
			if( ! in_array( 
				$action, 
				array( 
					WPSN_IM_OAUTH_LM_ACTION_AUTHENTICATE,
					WPSN_IM_OAUTH_LM_ACTION_AUTHENTICATED
				) 
			) ){
				return false;
			}

			if($action==WPSN_IM_OAUTH_LM_ACTION_AUTHENTICATE){
				$auth_code = $_GET["param"];
				$client_id = $this->client_id;
				$client_secret = $this->client_secret;
				$redirect_uri = (
					$this->addParamsToUri(array(
						"uri" => $this->redirect_uri,
						"params" =>WPSN_IM_OAUTH_LM_REDIRECT_URI_ACTION_AUTHENTICATED
					))
				);

				if($this->enable_log)
					file_put_contents(
						WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
						"\n".date("d-m-Y H:i:s")." ".
						WPSN_IM_OAUTH_LM_REDIRECT_URI_ACTION_AUTHENTICATE." ".
						"mode=".($this->test_mode?"test":"prod")." ".
						"code=$auth_code name=$client_id "./*pwd=$client_secret ".*/
						"redirect_uri=$redirect_uri\n",
						FILE_APPEND
					);

				sn_im_oauth_lm_render_user_data_loading(array(
					"code" => $auth_code,
					"client_secret" => $client_secret,
					"client_id" => $client_id,
					"redirect_uri" => $redirect_uri,
				));
			}
			elseif($action==WPSN_IM_OAUTH_LM_ACTION_AUTHENTICATED){
				$this->authenticated();
			}

		}

		/*
			Check if user data have to be refreshed
		*/
		function sn_im_oauth_lm_refresh_user_data(){
		}

		/*
			Oauth Customer service notificaion url hook.
			The backend send token, we save it to db with 
			tmp code that is returned to caller
		*/
		function authenticated(){
			$data = file_get_contents('php://input');
			$tmp_code = bin2hex(random_bytes(32));
			$db_key = WPSN_IM_OAUTH_LM_PREFIX.$tmp_code;
			$db_value = $data;
			update_option($db_key, $db_value);
			if($this->enable_log)
				file_put_contents(
					WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
					date("d-m-Y H:i:s").
					" ".WPSN_IM_OAUTH_LM_REDIRECT_URI_ACTION_AUTHENTICATED." Access token received\n"
					."db_key=".$db_key."\n"
					."db_value=".var_export($data,true)."\n"
					,
					FILE_APPEND
				);
			echo $tmp_code;
			exit();
		}

		/*
			Just propagate the oauth response - old, unsecure, deprecated
		*/
		function authenticated_deprecated(){
			/*
			$data = json_decode(
				file_get_contents('php://input'),
				true
			);
			*/
			$data = file_get_contents('php://input');
			if($this->enable_log)
				file_put_contents(
					WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
					date("d-m-Y H:i:s").
					" ".WPSN_IM_OAUTH_LM_REDIRECT_URI_ACTION_AUTHENTICATED." Access token received\n"
					//.var_export($data,true)."\n"
					,
					FILE_APPEND
				);
			echo $data;
			exit();
		}

		/**
		* create_menu function
		* generate the link to the options page under settings
		* @access public
		* @return void
		*/
		function create_menu() {
			add_options_page(
				WPSN_IM_OAUTH_LM_ADMIN_PAGE_TITLE, 
				"<span class='socialNationItsmeLoginItsmeLogoSmall'>M</span>".WPSN_IM_OAUTH_LM_ADMIN_MENU_TITLE, 
				'manage_options', 
				WPSN_IM_OAUTH_LM_ADMIN_MENU_SLUG, 
				array($this,'options_page')
			);
		}

		/*
		* options_page function
		* generate the options page in the wordpress admin
		* @access public
		* @return void
		*/
		function options_page() { 
			$this->options_page_create_form();
		} // end of function options_page

		function options_page_create_form(){
			//save data
			$this->options_page_save();

			$imltab_uri_param="";
			$imltab="";
			if( isset( $_REQUEST["imltab"] ) ){
				$imltab = trim( strtolower( strip_tags( $_REQUEST["imltab"] ) ) );
				$imltab_uri_param = "&imltab=$imltab";
			}
			?>
			<div class="wrap social_nation_itsme_oauth_login_mb">
				<?php 
					if (isset($_GET['savedata'])) {
						if($_GET['savedata']=="true"){
							echo '<div id="message" class="updated"><p>Settings saved</p></div>';
						}
						else {
							echo '<div id="message" class="error"><p>Error on saving</p></div>';
						}
					}
				?>
				<h1><span class="socialNationItsmeLoginItsmeLogo">M</span>Social Nation It'sMe OAuth Login Multibutton</h1>
				<form 
					method="post" 
					id="social_nation_itsme_oauth_login_mb_form" 
					action="options-general.php?page=<?php echo WPSN_IM_OAUTH_LM_ADMIN_MENU_SLUG; ?>&savedata=true<?php echo $imltab_uri_param; ?>"
				>
					<input type="hidden" name="social_nation_itsme_oauth_login_mb_form_hidden" value="1"/>
					<?php 
						wp_nonce_field( 
							'save_socialnationitsmelogin', 
							'_socialnationitsmelogin_nonce' 
						); 
					?>
					<h2 class="nav-tab-wrapper">
						<a 
							class="nav-tab <?php if( !$imltab || $imltab == WPSN_IM_OAUTH_LM_ADMIN_TAB_GENERAL ) echo "nav-tab-active"; ?>" 
							style="" 
							href="options-general.php?page=<?php echo WPSN_IM_OAUTH_LM_ADMIN_MENU_SLUG; ?>&imltab=<?php echo WPSN_IM_OAUTH_LM_ADMIN_TAB_GENERAL; ?>"
						>
							General
						</a>
						<a 
							class="nav-tab <?php if( $imltab == WPSN_IM_OAUTH_LM_ADMIN_TAB_REPORT ) echo "nav-tab-active"; ?>" 
							style="" 
							href="options-general.php?page=<?php echo WPSN_IM_OAUTH_LM_ADMIN_MENU_SLUG; ?>&imltab=<?php echo WPSN_IM_OAUTH_LM_ADMIN_TAB_REPORT; ?>"
						>
							Report
						</a>
					</h2>
					<?php
					if($imltab == WPSN_IM_OAUTH_LM_ADMIN_TAB_REPORT){
						$this->options_page_report_tab();
					}
					else{
						$this->options_page_general_tab();
					}
					?>

					<p class="submit">
						<input 
							type="submit" 
							name="submit_sn_iml" 
							class="button-primary" 
							value="<?php _e('Save Changes') ?>" 
						/>
					</p>
				</form>
			</div>
			<?php
		}

		function options_page_general_tab(){
			wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-admin-general-settings", 
				WPSN_IM_OAUTH_LM_PLUGIN_JS_URL."/SocialNationItsmeOAuthLoginMBAdminGeneralSettings.js", 
				array(), 
				WPSN_IM_OAUTH_LM_VERSION,
				true
			);
			wp_localize_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG.'-admin-general-settings', 
				'SocialNationItsmeOAuthLoginMBAdminGeneralSettings', 
				array( 
					'action' => WPSN_IM_OAUTH_LM_ACTION_AUTHENTICATED,
					'src' => WPSN_IM_OAUTH_LM_LOGIN_BUTTONS_IMAGE_URL."/"
				) 
			);
			?>
			<table class="widefat">
				<thead>
					<tr>
						<th colspan="2">Impostazioni Generali</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="width:20%;">
							<small>
								OAuth Name (Client Id)
							</small>
						</td>
						<td style="width:80%;">
							<input 
								type="text" 
								name="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>client_id" 
								id="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>client_id" 
								value="<?php echo $this->client_id; ?>" 
								style="width:99%;" 
							/>
						</td>
					</tr>
					<tr>
						<td style="width:20%;">
							<small>
								OAuth Key (Client Secret)
							</small>
						</td>
						<td style="width:80%;">
							<input 
								type="text" 
								name="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>client_secret" 
								id="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>client_secret" 
								value="<?php echo $this->client_secret; ?>" 
								style="width:99%;" 
							/>
						</td>
					</tr>
					<tr>
						<td style="padding-bottom:0" style="width:20%;">
							<small>
								Token Url Notification
							</small>
						</td>
						<td style="padding-bottom:0" style="width:80%;">
							<input 
								type="text" 
								name="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>redirect_uri" 
								value="<?php echo $this->check_output_file_name($this->redirect_uri, array("mode"=>"attr")); ?>" 
								style="width:99%;" 
								id="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>redirect_uri"
							/>
						</td>
					</tr>
					<tr>
						<td style="padding-top:0; background-color: #ddffdd" colspan="2" style="width:20%;">
							<small>
								Usa il seguente url come Token Url Notification
								del tuo Customer Service Account:
							</small>
							<div style="display: inline-block;" id="redirect_uri_text" >
							</div>
						</td>
					</tr>
					<tr>
						<td style="width:20%;">
							<small>
								Scope
							</small>
						</td>
						<td style="width:80%;">
							<input 
								type="text" 
								name="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>default_scope" 
								id="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>default_scope" 
								value="<?php echo $this->default_scope; ?>" 
								style="width:99%;" 
							/>
						</td>
					</tr>
					<tr>
						<td style="width:20%;">
							<small>
								Test mode
							</small>
						</td>
						<td style="width:80%;">
							<input 
								type="checkbox" 
								name="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>test_mode" 
								<?php echo $this->test_mode?"checked":""; ?>
							/>
						</td>
					</tr>
					<tr>
						<td style="width:20%;">
							<small>
								Enable Log
							</small>
						</td>
						<td style="width:80%;">
							<input 
								type="checkbox" 
								name="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>enable_log" 
								<?php echo $this->enable_log?"checked":""; ?>
							/>
						</td>
					</tr>
					<?php
					/*
					disabled take it from remote url
					<tr>
						<td style="width:20%;">
							<small>
								Layout Bottone Login
							</small>
						</td>
						<td style="width:80%;">
							<div style="display:inline-block;">
								<select id="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>button_file_name" name="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>button_file_name">
									<?php
									$files = scandir(WPSN_IM_OAUTH_LM_LOGIN_BUTTONS_IMAGE_DIR);
									foreach($files as $buttonImageFile){
										if($buttonImageFile=="."||$buttonImageFile=="..")
											continue;
									?>
										<option 
										<?php 
											echo 
												$this->button_file_name==$buttonImageFile?
												"selected":
												"" 
										?> 
											value="<?php echo $this->check_output_file_name($buttonImageFile, array("mode"=>"attr")); ?>"
										>
											<?php echo $this->check_output_file_name($buttonImageFile); ?>
										</option>
									<?php
									}
									?>
								</select>
							</div>
							<div style="margin-left:10px;display:inline-block;">
								Anteprima:
							</div>
							<div style="display:inline-block;">
								<div id="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>login_button_container">
									<?php 
										$this->printItsmeLoginButton(array("button_layout"=>WPSN_IM_OAUTH_LM_BUTTON_LAYOUT_BUTTON_ICON));
									?>
								</div>
							</div>
						</td>
					</tr>
					*/
					$baseUrl = "";
					if($this->test_mode){
						$baseUrl = WPSN_IM_OAUTH_LM_ITSME_BASE_URL_TEST;
						$buttons = file_get_contents(WPSN_IM_OAUTH_LM_ITSME_BUTTON_LIST_FILE_TEST);
					}
					else{
						$baseUrl = WPSN_IM_OAUTH_LM_ITSME_BASE_URL;
						$buttons = file_get_contents(WPSN_IM_OAUTH_LM_ITSME_BUTTON_LIST_FILE);
					}

					$buttons = explode(PHP_EOL, $buttons);
					?>
					<tr>
						<td style="width:20%;">
							<small>
								Layout Bottone Login
							</small>
						</td>
						<td style="width:80%;">
							<div style="display:inline-block;">
								<select id="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>button_file_name" name="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>button_file_name">
									<?php
									foreach($buttons as $button){
										$buttonText =  explode(";", $button);
										$buttonUrl = $buttonText[0];
										$buttonName = $buttonText[1];
										$buttonName = str_replace('"', "", $buttonName);
									?>
										<option 
										<?php 
											echo 
												$this->button_file_name==$buttonUrl?
												"selected":
												"" 
										?> 
											value="<?php echo $this->check_output_file_name($buttonUrl, array("mode"=>"attr")); ?>"
										>
											<?php echo $buttonName; ?>
										</option>
									<?php
									}
									?>
								</select>
							</div>
							<div style="margin-left:10px;display:inline-block;">
								Anteprima:
							</div>
							<div style="display:inline-block;">
								<div id="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>login_button_container">
									<input 
										type="hidden" 
										name="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>preview_base_url" 
										value="<?php echo $this->check_input_url($baseUrl); ?>" 
										id="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>preview_base_url"
									/>
									<?php 
										$this->printItsmeLoginButton(array("button_file_name"=>$this->button_file_name));
									?>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td style="width:20%;">
							<small>
								Url di ritorno (return url)
							</small>
						</td>
						<td style="width:80%;">
							<input 
								type="text" 
								name="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>return_url" 
								value="<?php echo $this->return_url; ?>"
								id="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>return_url"
								style="width:99%;" 
							/>
							<p class="description">
								Questo è l'url di ritorno dal login con It'sMe. 
								Una volta terminato il login l'utente verrà reindirizzato a questo url.<br>
								(es. <?php echo home_url(); ?> - Lasciare vuoto per far ritornare l'utente alla pagina in cui è stato premuto il bottone di login con It'sMe)
							</p>
						</td>
					</tr>
					<tr>
						<td style="width:20%;">
							<small>
								Codice Shortcode
							</small>
						</td>
						<td style="width:80%;">
							<div class="socialNationItsmeLoginCodeBox" id="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>shortcode_syntax"></div>
							<div 
								class="socialNationItsmeLoginCodeBoxError" 
								id="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>shortcode_syntax_error" 
								style="display:none;"
							>  
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
		}

		function options_page_report_tab(){
			$this->addReportUserScript();
			wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-admin-report-settings", 
				WPSN_IM_OAUTH_LM_PLUGIN_JS_URL."/SocialNationItsmeOAuthLoginMBAdminReportSettings.js", 
				array(), 
				WPSN_IM_OAUTH_LM_VERSION,
				true
			);
			?>
			<table class="widefat" id="iml_options_report_table">
				<thead>
					<tr>
						<th colspan="2">Impostazioni Report</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="width:20%;">
							<small>
								Report Interval
							</small>
						</td>
						<td style="width:80%;">
						<!--
							<input 
								type="text" 
								name="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>report_interval" 
								value="<?php echo $this->report_interval; ?>" 
								style="width:99%;" 
							/>
						-->
							<select id="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>report_interval" name="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>report_interval">
								<option 
									<?php echo $this->report_interval==WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_MONTHLY_VALUE?"selected":"" ?> 
									value="<?php echo WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_MONTHLY_VALUE; ?>"
								>
									<?php echo WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_MONTHLY; ?>
								</option>
								<option 
									<?php echo $this->report_interval==WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_WEEKLY_VALUE?"selected":"" ?> 
									value="<?php echo WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_WEEKLY_VALUE; ?>"
								>
									<?php echo WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_WEEKLY; ?>
								</option>
								<option 
									<?php echo $this->report_interval==WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_DAILY_VALUE?"selected":"" ?> 
									value="<?php echo WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_DAILY_VALUE; ?>"
								>
									<?php echo WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_DAILY; ?>
								</option>
								<!--
								<option 
									<?php echo $this->report_interval==WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_10_SECONDS_VALUE?"selected":"" ?> 
									value="<?php echo WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_10_SECONDS_VALUE; ?>"
								>
									<?php echo WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_10_SECONDS; ?>
								</option>
								<option 
									<?php echo $this->report_interval==WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_30_SECONDS_VALUE?"selected":"" ?> 
									value="<?php echo WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_30_SECONDS_VALUE; ?>"
								>
									<?php echo WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_30_SECONDS; ?>
								</option>
								<option 
									<?php echo $this->report_interval==WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_60_SECONDS_VALUE?"selected":"" ?> 
									value="<?php echo WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_60_SECONDS_VALUE; ?>"
								>
									<?php echo WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_60_SECONDS; ?>
								</option>
								<option 
									<?php echo $this->report_interval==WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_5_MINUTES_VALUE?"selected":"" ?> 
									value="<?php echo WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_5_MINUTES_VALUE; ?>"
								>
									<?php echo WPSN_IM_OAUTH_LM_REPORT_CRON_INTERVAL_5_MINUTES; ?>
								</option>
								-->
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="width:20%;">
							<small>
								Data ultimo report:
							</small>
							<small id="iml_last_report_date">
								<?php
									/*
									echo SocialNationItsMeOAuthLoginMBUtils::convertDateToLocale(array(
										"dateTime"=>$this->last_report_date
									));
									*/
									echo $this->last_report_date_strong_with_timezone;
								?>
							</small>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="width:20%;">
							<small>
								Sottoscrittori Totali:
							</small> 
							<small id="iml_total_subscriber">
								<?php
									$total_subscriber = $this->total_subscriber;
									echo $total_subscriber;
								?>
							</small>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="width:20%;">
							<small>
								Sottoscrittori aggiornati nell'ultimo report: 
							</small>
							<small id="iml_updated_subscriber_number_spinner">
							</small>
							<small id="iml_updated_subscriber_number">
								<?php
									$last_uuid_updated = $this->uuid_to_update;
									$num_updated = 0;
									if($last_uuid_updated && is_array($last_uuid_updated)){
										$num_updated = count($last_uuid_updated);
									}
									echo $num_updated;
								?>
							</small>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="checkbox" id="socialNationItsmeLoginReportLogCheckbox"/>
							<div 
								class="socialNationItsmeLoginReportShowHideLogWrapper"
							>
								<label 
									class="socialNationItsmeLoginReportShowHideLog socialNationItsmeLoginReportShowLog"
									for="socialNationItsmeLoginReportLogCheckbox"
								>
									<small>Mostra log ultimo report</small>
								</label >
								<label 
									class="socialNationItsmeLoginReportShowHideLog socialNationItsmeLoginReportHideLog"
									for="socialNationItsmeLoginReportLogCheckbox"
								>
									<small>Nascondi log ultimo report</small>
								</label >
								<div 
									id="socialNationItsmeLoginReportLogWarning"
									class="socialNationItsmeLoginReportLogWarning"
								>
									<div >
										<small>
										Si sono verificati degli errori, controlla il Log</small>
									</div>
									<div alt="f534" class="dashicons dashicons-warning"></div>
								</div>
								<div 
									id="socialNationItsmeLoginReportLogSuccess"
									class="socialNationItsmeLoginReportLogSuccess"
								>
									<div >
										<small>User report eseguito correttamente</small>
									</div>
									<span class="dashicons dashicons-yes"></span>
								</div>
								<textarea 
									class="socialNationItsmeLoginReportBox" 
									id="iml_updated_subscriber_log" 
									class="debug-log-textarea" 
									autocomplete="off" 
									readonly=""
								><?php echo $this->check_output_textarea(get_option(WPSN_IM_OAUTH_LM_PREFIX."user_report_log")); ?></textarea>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="width:20%;">
							<button type="button" id="socialNationItsmeLoginReportExecute">
								Esegui report manualmente
							</button> 
						</td>
					</tr>
				</tbody>
			</table>
			<?php
		}

		function options_page_save(){
			// if submitted, process the data
			if (
				!isset($_POST['social_nation_itsme_oauth_login_mb_form_hidden']) ||
				!isset($_POST['_socialnationitsmelogin_nonce'])
			) {
				return;
			}

			if ( !current_user_can('manage_options') ){ 
				wp_die( 'You do not have sufficient permissions to access this page.' ); 
			}

			//check nonce
			$verify_nonce = wp_verify_nonce( 
				$_POST['_socialnationitsmelogin_nonce'],
				'save_socialnationitsmelogin'
			);
			if(!$verify_nonce){
				 die( 'Security check' ); 
			} 

			/*
			check_admin_referer( 
				'save_socialnationitsmelogin', 
				'_socialnationitsmelogin_nonce'  
			);
			*/

			if ( ! file_exists( WPSN_IM_OAUTH_LM_LOG_DIR_PATH ) ) {
			    wp_mkdir_p( WPSN_IM_OAUTH_LM_LOG_DIR_PATH );
			}

			if(isset($_POST[WPSN_IM_OAUTH_LM_PREFIX.'client_secret'])){
				$sn_im_oauth_lm_client_secret = $this->check_input_text($_POST[WPSN_IM_OAUTH_LM_PREFIX.'client_secret']);
				update_option(WPSN_IM_OAUTH_LM_PREFIX.'client_secret', $sn_im_oauth_lm_client_secret);
			}
			if(isset($_POST[WPSN_IM_OAUTH_LM_PREFIX.'client_id'])){
				$sn_im_oauth_lm_client_id = $this->check_input_text($_POST[WPSN_IM_OAUTH_LM_PREFIX.'client_id']);
				update_option(WPSN_IM_OAUTH_LM_PREFIX.'client_id', $sn_im_oauth_lm_client_id);
			}
			if(isset($_POST[WPSN_IM_OAUTH_LM_PREFIX.'default_scope'])){
				$sn_im_oauth_lm_default_scope = $this->check_input_text($_POST[WPSN_IM_OAUTH_LM_PREFIX.'default_scope']);
				if($sn_im_oauth_lm_default_scope=="")
					$sn_im_oauth_lm_default_scope = "basic";
				update_option(WPSN_IM_OAUTH_LM_PREFIX.'default_scope', $sn_im_oauth_lm_default_scope);
			}
			if(isset($_POST[WPSN_IM_OAUTH_LM_PREFIX.'redirect_uri'])){
				$sn_im_oauth_lm_redirect_uri = $this->check_input_url($_POST[WPSN_IM_OAUTH_LM_PREFIX.'redirect_uri']);
				update_option(WPSN_IM_OAUTH_LM_PREFIX.'redirect_uri', $sn_im_oauth_lm_redirect_uri);
			}
			if(isset($_POST[WPSN_IM_OAUTH_LM_PREFIX.'report_interval'])){
				$sn_im_oauth_lm_report_interval = $this->check_input_text($_POST[WPSN_IM_OAUTH_LM_PREFIX.'report_interval']);
				update_option(WPSN_IM_OAUTH_LM_PREFIX.'report_interval', $sn_im_oauth_lm_report_interval);
			}
			//if checkbox $_POST[WPSN_IM_OAUTH_LM_PREFIX.'enable_log'] is setted then is checked
			//if not setted it is not checked
			if(isset($_POST[WPSN_IM_OAUTH_LM_PREFIX.'enable_log'])){
				$sn_im_oauth_lm_enable_log = $this->check_input_boolean($_POST[WPSN_IM_OAUTH_LM_PREFIX.'enable_log']);
				update_option(WPSN_IM_OAUTH_LM_PREFIX.'enable_log', $sn_im_oauth_lm_enable_log);
			}
			else{
				update_option(WPSN_IM_OAUTH_LM_PREFIX.'enable_log', 0);
			}
			if(isset($_POST[WPSN_IM_OAUTH_LM_PREFIX.'test_mode'])){
				$sn_im_oauth_lm_test_mode = $this->check_input_boolean($_POST[WPSN_IM_OAUTH_LM_PREFIX.'test_mode']);
				update_option(WPSN_IM_OAUTH_LM_PREFIX.'test_mode', $sn_im_oauth_lm_test_mode);
			}
			else{
				update_option(WPSN_IM_OAUTH_LM_PREFIX.'test_mode', 0);
			}
			//if(isset($_POST[WPSN_IM_OAUTH_LM_PREFIX.'button_layout'])){
			//	$sn_im_oauth_lm_button_layout = $_POST[WPSN_IM_OAUTH_LM_PREFIX.'button_layout'];
			//	update_option(WPSN_IM_OAUTH_LM_PREFIX.'button_layout', $sn_im_oauth_lm_button_layout);
			//}
			if(isset($_POST[WPSN_IM_OAUTH_LM_PREFIX.'button_file_name'])){
				$sn_im_oauth_lm_button_file_name = $this->check_input_url($_POST[WPSN_IM_OAUTH_LM_PREFIX.'button_file_name']);
				update_option(WPSN_IM_OAUTH_LM_PREFIX.'button_file_name', $sn_im_oauth_lm_button_file_name);
			}

			if(isset($_POST[WPSN_IM_OAUTH_LM_PREFIX.'return_url'])){
				$sn_im_oauth_lm_return_url = $this->check_input_text($_POST[WPSN_IM_OAUTH_LM_PREFIX.'return_url']);
				update_option(WPSN_IM_OAUTH_LM_PREFIX.'return_url', $sn_im_oauth_lm_return_url);
			}

			$this->getAllOptions();

			//delete_option(WPSN_IM_OAUTH_LM_PREFIX.'client_secret');
		}

		function addParamsToUri($options=array()){
			$uri = $options["uri"]?$options["uri"]:"";
			$params = $options["params"]?$options["params"]:"";
			$uri .= 
				( strpos($uri, '?')===false ? "?" : "&" ) . 
				$params;
			return $uri;
		}

		function printItsmeLoginButton($options=array()){
			$text = $options["text"]?$options["text"]:"";
			$href = $options["href"]?$options["href"]:"";
			//$img_url = isset($options["img_url"])?$options["img_url"]:false;
			$text_after = $options["text_after"]?$options["text_after"]:"";
			//$button_layout = isset($options["button_layout"])?$options["button_layout"]:$this->button_layout;
			$button_file_name = isset($options["button_file_name"])?$options["button_file_name"]:$this->button_file_name;
			//$scope = isset($options["scope"])?$options["scope"]:$this->default_scope;

			$baseUrl = "";
			if($this->test_mode){
				$baseUrl = WPSN_IM_OAUTH_LM_ITSME_BASE_URL_TEST;
			}
			else{
				$baseUrl = WPSN_IM_OAUTH_LM_ITSME_BASE_URL;
			}

			$button_file_name = $baseUrl.$button_file_name;

			?>
			<div class="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>login_button">
				<div class="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>login_button_text">
					<?php echo $this->check_output_text($text); ?>
				</div>
				<div class="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>login_button_img">
					<a 
						href="<?php echo $this->check_output_file_name($href, array("mode"=>"attr")); ?>" 
						class="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>login_button"
					>
						<img 
							src="<?php echo $this->check_input_url($button_file_name); ?>"
							class="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>login_button"
						/>
					</a>
				</div>
				<div class="<?php echo WPSN_IM_OAUTH_LM_PREFIX; ?>login_button_text_after">
					<?php echo $this->check_output_text($text_after); ?>
				</div>
			</div>
			<?php
		}

		function load_scripts(){
			$version=WPSN_IM_OAUTH_LM_VERSION;
			wp_enqueue_style( WPSN_IM_OAUTH_LM_PLUGIN_SLUG."", WPSN_IM_OAUTH_LM_PLUGIN_CSS_URL."/".WPSN_IM_OAUTH_LM_PLUGIN_SLUG.".css", array(), $version);
		}
		function load_admin_scripts(){
			$version=WPSN_IM_OAUTH_LM_VERSION;
			wp_enqueue_style( WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-admin", WPSN_IM_OAUTH_LM_PLUGIN_CSS_URL."/".WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-admin.css", array(), $version);
		}

		function addReportUserScript(){
			$version=WPSN_IM_OAUTH_LM_VERSION;
			$api_url = $this->api_url;
			$this->enqueue_api_scripts();
			wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-apiClient", 
				WPSN_IM_OAUTH_LM_PLUGIN_JS_URL."/apiClient.js", 
				array('jquery'), 
				$version
			);
			wp_localize_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG.'-apiClient', 
				'SnImlApiClientData', 
				array( 
					'ajax_url' => admin_url('admin-ajax.php')
				) 
			);
			/*
			wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-axios-dist-axiosstandalone", 
				$api_url."/lib/axios/dist/axios.standalone.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-CryptoJS-rollups-hmacsha256", 
				$api_url."/lib/CryptoJS/rollups/hmac-sha256.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-CryptoJS-rollups-sha256", 
				$api_url."/lib/CryptoJS/rollups/sha256.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-CryptoJS-components-hmac", 
				$api_url."/lib/CryptoJS/components/hmac.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-CryptoJS-components-encbase64", 
				$api_url."/lib/CryptoJS/components/enc-base64.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-url-template-url-template", 
				$api_url."/lib/url-template/url-template.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-apiGatewayCore-sigV4Client", 
				$api_url."/lib/apiGatewayCore/sigV4Client.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-apiGatewayCore-apiGatewayClient", 
				$api_url."/lib/apiGatewayCore/apiGatewayClient.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-apiGatewayCore-simpleHttpClient", 
				$api_url."/lib/apiGatewayCore/simpleHttpClient.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-apiGatewayCore-utils", 
				$api_url."/lib/apiGatewayCore/utils.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-apigClient", 
				$api_url."/apigClient.js", 
				array(), 
				$version
			);
			*/

			wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-user-report", 
				WPSN_IM_OAUTH_LM_PLUGIN_JS_URL."/SocialNationItsmeOAuthLoginMBCheckReport.js", 
				array("jquery"), 
				$version.time()
			);
			wp_localize_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG.'-user-report', 
				'socialNationItsmeAjax', 
				array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'auth' => "Basic ".base64_encode($this->client_id.":".$this->client_secret)
				) 
			);
		}

		function enqueue_api_scripts(){
			$version=WPSN_IM_OAUTH_LM_VERSION;
			$api_url = $this->api_url;

			wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-axios-dist-axiosstandalone", 
				$api_url."/lib/axios/dist/axios.standalone.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-CryptoJS-rollups-hmacsha256", 
				$api_url."/lib/CryptoJS/rollups/hmac-sha256.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-CryptoJS-rollups-sha256", 
				$api_url."/lib/CryptoJS/rollups/sha256.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-CryptoJS-components-hmac", 
				$api_url."/lib/CryptoJS/components/hmac.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-CryptoJS-components-encbase64", 
				$api_url."/lib/CryptoJS/components/enc-base64.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-url-template-url-template", 
				$api_url."/lib/url-template/url-template.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-apiGatewayCore-sigV4Client", 
				$api_url."/lib/apiGatewayCore/sigV4Client.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-apiGatewayCore-apiGatewayClient", 
				$api_url."/lib/apiGatewayCore/apiGatewayClient.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-apiGatewayCore-simpleHttpClient", 
				$api_url."/lib/apiGatewayCore/simpleHttpClient.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-apiGatewayCore-utils", 
				$api_url."/lib/apiGatewayCore/utils.js", 
				array(), 
				$version
			);
		    wp_enqueue_script( 
				WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-sdk-apigClient", 
				$api_url."/apigClient.js", 
				array(), 
				$version
			);
		}

		/*
			Manually manage cron job with two variables: interval, last_report_date (GMT timezone)
			Problem: 	Wordpress execute cron job asynchronously from the user http request, 
						so we cannot execute js client-side
		*/
		function checkCronJob(){
			//user report
			$interval = $this->report_interval;
			//$interval = 30;
			$last_report_date = $this->last_report_date;
			if($last_report_date===false)
				return;
			$last_report_date_time = $this->last_report_date->getTimestamp();
			$curr = new DateTime();
			$curr_time = $curr->getTimestamp();

			/*
			if($this->enable_log) file_put_contents(
				WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
				"\n".date("d-m-Y H:i:s")." CRONJOB USER REPORT: ".
				"curr_time=".$curr_time." curr_time=".$curr->format("d-m-Y H:i:s")." ".
				"last_report_date_time=".$last_report_date_time." last_report_date=".$last_report_date->format("d-m-Y H:i:s")." ".
				"interval=$interval ".
				"$curr_time >= ".($last_report_date_time+$interval)." = ".!!($curr_time>=$last_report_date_time+$interval).
				" defined(DOING_AJAX)=".(!!defined( 'DOING_AJAX' )).
				" DOING_AJAX=".(!!DOING_AJAX).
				" non sono dentro ajax flag=".(!defined( 'DOING_AJAX' ) || !DOING_AJAX).
				" REQUEST_URI=".$_SERVER["REQUEST_URI"].
				"\n",
				FILE_APPEND
			);
			*/

			if($curr_time>=$last_report_date_time+$interval /*&& false*/){
				//do
				$this->addReportUserScript();
				wp_enqueue_script( 
					WPSN_IM_OAUTH_LM_PLUGIN_SLUG."-execute-user-report", 
					WPSN_IM_OAUTH_LM_PLUGIN_JS_URL."/SocialNationItsmeOAuthLoginMBExecuteReport.js", 
					array("jquery"), 
					WPSN_IM_OAUTH_LM_VERSION.time()
				);
				if($this->enable_log) file_put_contents(
					WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
					"\n".date("d-m-Y H:i:s")." CRONJOB USER REPORT script added: ".
					"interval=$interval sec. - last_report=".$last_report_date->format("d-m-Y H:i:s").
					" - current time=".$curr->format("d-m-Y H:i:s").".\n",
					FILE_APPEND
				);
				/* moved in ajax.php userReport
				update_option(WPSN_IM_OAUTH_LM_PREFIX.'last_report_date', $curr);
				$this->last_report_date = get_option(WPSN_IM_OAUTH_LM_PREFIX.'last_report_date');
				$this->last_report_date_strong_with_timezone = SocialNationItsMeOAuthLoginMBUtils::convertDateToLocale(array(
					"dateTime" => $this->last_report_date,
					"useLocalTimeZone" => true,
					"printTimezone" => true
				));
				*/
			}

			//echo "";
		}

		//validate/sanitize/escape data functions, generally used before inserts into db
		function validate_text($value){
			return $value;
		}
		function validate_email($value){
			if(!is_email($value)){
				return "";
			}
			return $value;
		}
		function validate_url($value){
			return esc_url($value);
		}
		function validate_boolean($value){
			return filter_var($value, FILTER_VALIDATE_BOOLEAN);
		}
		function validate_file_name($value){
			return sanitize_file_name($value);
		}
		function validate_integer($value){
			return intval($value);
		}

		//sanitize data
		function sanitize_text($value){
			return sanitize_text_field($value);
		}
		function sanitize_email($value){
			return sanitize_email($value);
		}
		function sanitize_file_name($value){
			return sanitize_file_name($value);
		}

		//check input data generally used before insert into db
		function check_input_text($value){
			return $this->sanitize_text($value);
		}
		function check_input_email($value){
			return $this->validate_email($value);
		}
		function check_input_url($value){
			return $this->validate_url($value);
		}
		function check_input_boolean($value){
			return $this->validate_boolean($value);
		}
		function check_input_file_name($value){
			return $this->sanitize_file_name($value);
		}
		function check_input_integer($value){
			return $this->validate_integer($value);
		}
		function check_input_textarea($value){
			return esc_textarea($value);
		}

		//check output data generally used before display it
		function check_output_text($value, $options=array()){
			$mode = $options["mode"]?$options["mode"]:"html";

			if($mode=="js")
				return esc_js($value);
			else if($mode=="attr")
				return esc_attr($value);

			return esc_html($value);
		}
		function check_output_text_js($value){
			return esc_html($value, array("mode"=>"js"));
		}
		function check_output_text_attr($value){
			return esc_html($value, array("mode"=>"attr"));
		}
		function check_output_email($value){
			return $this->check_output_text($value);
		}
		function check_output_url($value){
			return esc_url($value);
		}
		function check_output_boolean($value){
			return $this->check_output_text($value);
		}
		function check_output_file_name($value){
			return $this->check_output_text($value);
		}
		function check_output_integer($value){
			return $this->check_output_text($value);
		}
		function check_output_textarea($value){
			return esc_textarea($value);
		}
	}

	// instantiate
	$social_nation_itsme_oauth_login_mb = new SocialNationItsMeOAuthLoginMB();

	if (isset($social_nation_itsme_oauth_login_mb)) {

		register_activation_hook (
			__FILE__, 
			array($social_nation_itsme_oauth_login_mb, 'activate')
		);

		//add setup link in plugin list
		add_filter (
			'plugin_action_links_'.plugin_basename(__FILE__), 
			array($social_nation_itsme_oauth_login_mb, 'add_setup_link'), 
			10, 
			2
		);

		// create the menu
		add_action(
			'admin_menu', 
			array($social_nation_itsme_oauth_login_mb,'create_menu')
		);

		add_action( 
			'init', 
			array($social_nation_itsme_oauth_login_mb,WPSN_IM_OAUTH_LM_PREFIX.'process_login' )
		);

		/*
		add_action(
			'admin_init', 
			array($social_nation_itsme_oauth_login_mb,'options_page_save')
		);
		*/

		//set report hook
		/*
		add_action(
			'admin_init', 
			array($social_nation_itsme_oauth_login_mb,'checkCronJob')
		);
		*/
		//if(is_admin() && (!defined( 'DOING_AJAX' ) || !DOING_AJAX)){
			add_action(
				'init', 
				array($social_nation_itsme_oauth_login_mb,'checkCronJob')
			);
		//}

		//Enqueue styles
		add_action( 'wp_enqueue_scripts', array( $social_nation_itsme_oauth_login_mb, 'load_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $social_nation_itsme_oauth_login_mb, 'load_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $social_nation_itsme_oauth_login_mb, 'load_admin_scripts' ) );
	}
?>