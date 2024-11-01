<?php
class SocialNationItsMeOAuthLoginMBAjax{
	public function init(){
		add_action (
			'wp_ajax_'.WPSN_IM_OAUTH_LM_PREFIX.'login_user', 
			array($this, 'loginUser')
		);
		add_action (
			'wp_ajax_nopriv_'.WPSN_IM_OAUTH_LM_PREFIX.'login_user', 
			array($this, 'loginUser')
		);

		add_action (
			'wp_ajax_'.WPSN_IM_OAUTH_LM_PREFIX.'get_token_by_tmp', 
			array($this, 'getTokenByTmpCode')
		);
		add_action (
			'wp_ajax_nopriv_'.WPSN_IM_OAUTH_LM_PREFIX.'get_token_by_tmp', 
			array($this, 'getTokenByTmpCode')
		);

		add_action (
			'wp_ajax_iml_user_report', 
			array($this, 'userReport')
		);
		add_action (
			'wp_ajax_nopriv_iml_user_report', 
			array($this, 'userReport')
		);

		add_action (
			'wp_ajax_iml_user_report_update_pending', 
			array($this, 'userReportUpdatePending')
		);
		add_action (
			'wp_ajax_nopriv_iml_user_report_update_pending', 
			array($this, 'userReportUpdatePending')
		);

		add_action (
			'wp_ajax_iml_user_report_save_log', 
			array($this, 'userReportSaveLog')
		);
		add_action (
			'wp_ajax_nopriv_iml_user_report_save_log', 
			array($this, 'userReportSaveLog')
		);
	}

	public function getTokenByTmpCode($options=array()){
		$code = $_POST['code'];
		$data = get_option(WPSN_IM_OAUTH_LM_PREFIX.$code);
		delete_option(WPSN_IM_OAUTH_LM_PREFIX.$code);
		$out = array("success"=>true);
		if(!$data){
			//option does not exists
			$out["success"] = false;
			$out["data"] = $data;
			$out["message"] = "NO DATA ASSOCIATED TO THIS TEMP CODE";
		}
		else{
			$out["data"] = json_decode($data);
		}
		echo json_encode($out);
		die();
	}

	public function userReportSaveLog($options=array()){
		global $social_nation_itsme_oauth_login_mb;
		$text = $social_nation_itsme_oauth_login_mb->check_input_textarea($_POST['text']);
		update_option(WPSN_IM_OAUTH_LM_PREFIX."user_report_log", $_POST['text']);
		$out = array("success"=>true);

		if($social_nation_itsme_oauth_login_mb->enable_log){
			file_put_contents(
				WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
				"USER REPORT: userReportSaveLog() - log javascript salvato\n",
				FILE_APPEND
			);
		}

		echo json_encode($out);
		die();
	}

	/*
		Procedure to update user pending
	*/
	public function userReportUpdatePending($options=array()){
		global $social_nation_itsme_oauth_login_mb;
		$data = $_POST["data"];
		$uuid = $data["uuid"];

		$users = get_users(array(
			"meta_key" => WPSN_IM_OAUTH_LM_PREFIX."identifier",
			"meta_value" => $uuid,
			"fields" => "all_with_meta"
		));

		$out = array("success"=>true);

		if(!is_array($users) || count($users) != 1){
			$out["success"] = false;
			$out["errorCode"] = "1";
			if($social_nation_itsme_oauth_login_mb->enable_log){
				file_put_contents(
					WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
					"USER REPORT: userReportUpdatePending() - ERROR user NOT found uuid=".$uuid." \n",
					FILE_APPEND
				);
			}
		}
		else{
			reset($users);
			$wpuser = current($users);
			$userMeta = get_user_meta($wpuser->ID, null, true);
			$additionalData = array();
			$additionalData["authorization"] = "Basic ".base64_encode(
				$social_nation_itsme_oauth_login_mb->client_id.":".$social_nation_itsme_oauth_login_mb->client_secret
			);
			$additionalData["redirectUri"] = $social_nation_itsme_oauth_login_mb->getRedirectUri();
			$wpuser = array(
				"userData" => $wpuser,
				"userMeta" => $userMeta,
				"additionalData" => $additionalData
			);

			$scopeArray = array();

			//setup for multiscope
			foreach($userMeta as $k => $v){				
				if(strpos($k, WPSN_IM_OAUTH_LM_PREFIX)===0){
					//sn_im_oauth_lm_..scope2..microScope1..MiddleName
					$tmpText = str_replace(WPSN_IM_OAUTH_LM_PREFIX.WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER, "", $k);
					//scope2..microScope1..MiddleName
					$tmpText = explode(WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER, $tmpText);
					//check for backward compatibility
					if(count($tmpText)<2){						
						continue;
					}
					$scope = $tmpText[0];
					if(count($tmpText)==2){
						$microscope = false;
						$fieldName = $tmpText[1];
					}
					else{
						$microscope = $tmpText[1];
						$fieldName = $tmpText[2];
					}
					if(!isset($scopeArray[$scope])){
						$scopeArray[$scope] = array(
							"microscopes"=>array(),
							"fields"=>array(),
							"scope"=>$scope
						);
					}
					if($microscope !== false){
						if(!isset($scopeArray[$scope]["microscopes"][$microscope])){
							$scopeArray[$scope]["microscopes"][$microscope] = array(
								"fields"=>array(),
								"microscope"=>$scope
							);
						}						
						$scopeArray[$scope]["microscopes"][$microscope]["fields"][$fieldName] = $v;
					}
					else{
						$scopeArray[$scope]["fields"][$fieldName] = $v;
					}
				}
			}

			$wpuser["scopeArray"] = $scopeArray;

			$out["data"] = $wpuser;
			if($social_nation_itsme_oauth_login_mb->enable_log){
				file_put_contents(
					WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
					"USER REPORT: userReportUpdatePending() - user found -> id=".$wpuser["userData"]->ID." \n",
					FILE_APPEND
				);
			}
		}

		//$out["debug"] = $wpuser;
		echo json_encode($out);
		die();
	}

	/*
		Procedure to update user Report
	*/
	public function userReport($options=array()){
		global $social_nation_itsme_oauth_login_mb;
		$data = $_POST["data"];
		
		$total_subscriber = $social_nation_itsme_oauth_login_mb->validate_integer($data["totalSubscriber"]);
		update_option(WPSN_IM_OAUTH_LM_PREFIX.'total_subscriber', $total_subscriber);
		$social_nation_itsme_oauth_login_mb->total_subscriber = $total_subscriber;

		$uuid_to_update = $data["uuidToUpdate"];
		update_option(WPSN_IM_OAUTH_LM_PREFIX.'uuid_to_update', $uuid_to_update);
		$social_nation_itsme_oauth_login_mb->uuid_to_update = $uuid_to_update;

		if($uuid_to_update && is_array($uuid_to_update) && count($uuid_to_update)>0){
		}
		else{
			$uuid_to_update = false;
		}

		//update last report date
		$curr = new DateTime();
		update_option(WPSN_IM_OAUTH_LM_PREFIX.'last_report_date', $curr);
		$social_nation_itsme_oauth_login_mb->last_report_date = get_option(WPSN_IM_OAUTH_LM_PREFIX.'last_report_date');
		$social_nation_itsme_oauth_login_mb->last_report_date_strong_with_timezone = SocialNationItsMeOAuthLoginMBUtils::convertDateToLocale(array(
			"dateTime" => $social_nation_itsme_oauth_login_mb->last_report_date,
			"useLocalTimeZone" => true,
			"printTimezone" => true
		));

		$out = array(
			"success" => true, 
			"message" => "report updated", 
			"data" => array(
					"totalSubscriber" => $total_subscriber, 
					"lastReportDate" => $social_nation_itsme_oauth_login_mb->last_report_date_strong_with_timezone,
					"uuidToUpdate" => $uuid_to_update
			)
		);

		if($social_nation_itsme_oauth_login_mb->enable_log){
			file_put_contents(
				WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
				date("d-m-Y H:i:s")." USER REPORT: START - totalSubscriber=$total_subscriber \n",
				FILE_APPEND
			);
		}
		echo json_encode($out);
		die();
	}

	/*
		Procedure to create/update user
		1 - check if user exits in db
			* check for usermeta sn_im_oauth_lm_identifier
		2 - if user already exists update info then login user
		3 - if user do not exists, create it then login user  
	*/
	public function loginUser(){
		global $social_nation_itsme_oauth_login_mb;
		$user = $_POST["userData"];
		$userUuid = $user["uuid"];
		$userEmail = $user["email"];
		$userFirstName = $user["firstName"];
		$userLastName = $user["lastName"];
		$accessToken = $user["accessToken"];
		$refreshToken = $user["refreshToken"];
		$tokenExpiresIn = $user["tokenExpiresIn"];
		$scope = $user["scope"];
		$tokenType = $user["tokenType"];
		$dataArray = $user["dataArray"];
		$autoLogin = isset($_POST["autoLoginUser"])?$_POST["autoLoginUser"]:true;
		$deleteUserData = isset($_POST["deleteUserData"])?$_POST["deleteUserData"]:false;	
		$shouldRefreshToken = isset($_POST["shouldRefreshToken"])?$_POST["shouldRefreshToken"]:true;	

		$users = get_users(
			array(
				'meta_key' => WPSN_IM_OAUTH_LM_PREFIX.'identifier', 
				'meta_value' => $userUuid
			)
		);
		if(is_multisite() && count($users)==0){
			//user created on multisite doesn't have a blog id
			$users = get_users(
				array(
					'blog_id' => '', 
					'meta_key' => WPSN_IM_OAUTH_LM_PREFIX.'identifier', 
					'meta_value' => $userUuid
				)
			);
		}
		
		$createUserFlag = true;
		$userId = false;
		if(count($users)==0){
			//create user, check if user with same email exists
			if($userEmail){
				$wpUser = get_user_by("email", $userEmail);
				if($wpUser){
					$createUserFlag = false;
					$userId = $wpUser->ID;
					file_put_contents(
						WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
						date("d-m-Y H:i:s").
						" Itsme User not found! But an user with the same email was found: ".
						"email=$userEmail userId=$userId . So update the user...\n",
						FILE_APPEND
					);
				}
				else{
					file_put_contents(
						WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
						date("d-m-Y H:i:s").
						" Itsme User not found! create User.\n",
						FILE_APPEND
					);
				}
			}
		}
		else if(count($users)==1){
			//update user
			$userId = $users[0]->id;
			$createUserFlag = false;
			file_put_contents(
				WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
				date("d-m-Y H:i:s").
				" Itsme User FOUND! Update User. userId=$userId\n",
				FILE_APPEND
			);
		}
		else{
			//lol
			//this is not possible
		}

		$out = array();

		if($createUserFlag){
			$result = SocialNationItsMeOAuthLoginMBUser::login(array(
				"firstName" => $userFirstName,
				"lastName" => $userLastName,
				"email" => $userEmail,
				"uuid" => $userUuid,
				"accessToken" => $accessToken,
				"refreshToken" => $refreshToken,
				"tokenExpiresIn" => $tokenExpiresIn,
				"scope" => $scope,
				"tokenType" => $tokenType,
				"dataArray" => $dataArray,
				"autoLogin" => $autoLogin,
				"deleteUserData" => $deleteUserData,
				"shouldRefreshToken" => $shouldRefreshToken

			));
			if($result===true){
				$out["success"] = true;
				$out["message"] = "Utente creato";
				if($social_nation_itsme_oauth_login_mb->enable_log)
					file_put_contents(
						WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
						date("d-m-Y H:i:s").
						" User created\n",
						FILE_APPEND
					);
			}
			else{
				$out["success"] = false;
				$out["message"] = $result;
				if($social_nation_itsme_oauth_login_mb->enable_log)
					file_put_contents(
						WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
						date("d-m-Y H:i:s").
						" : User not created: $result\n",
						FILE_APPEND
					);
			}
			
		}
		else{
			$result = SocialNationItsMeOAuthLoginMBUser::login(array(
				"firstName" => $userFirstName,
				"lastName" => $userLastName,
				"email" => $userEmail,
				"userId" => $userId,
				"accessToken" => $accessToken,
				"refreshToken" => $refreshToken,
				"tokenExpiresIn" => $tokenExpiresIn,
				"scope" => $scope,
				"tokenType" => $tokenType,
				"dataArray" => $dataArray,
				"autoLogin" => $autoLogin,
				"deleteUserData" => $deleteUserData,
				"shouldRefreshToken" => $shouldRefreshToken,
				"uuid" => $userUuid
			));
			if($result===true){
				$out["success"] = true;
				$out["message"] = "Utente aggiornato (".$userId.")";
				if($social_nation_itsme_oauth_login_mb->enable_log)
					file_put_contents(
						WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
						date("d-m-Y H:i:s")." ".
						$out["message"]."\n",
						FILE_APPEND
					);
			}
			else{
				$out["success"] = false;
				$out["message"] = $result;
				if($social_nation_itsme_oauth_login_mb->enable_log)
					file_put_contents(
						WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
						date("d-m-Y H:i:s").
						" ERROR: User not updated (".$userId."):  $result\n",
						FILE_APPEND
					);
			}
		}
		
		echo json_encode($out);

		wp_die();
	}
}

(new SocialNationItsMeOAuthLoginMBAjax())->init();
?>