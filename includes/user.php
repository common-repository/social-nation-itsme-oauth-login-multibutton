<?php
/*
	User functions class
*/
class SocialNationItsMeOAuthLoginMBUser{
	/*
		Attempt to login user.
		- If user already exixts update some info.
		- If user don't exists create new user.
		- After create or update login user (wp_set_auth_cookie)
	*/
	public static function login($params=array()){
		$userId = $params["userId"]?$params["userId"]:false;
		$firstName = $params["firstName"]?$params["firstName"]:"";
		$lastName = $params["lastName"]?$params["lastName"]:"";
		$email = $params["email"]?$params["email"]:"";
		$uuid = $params["uuid"]?$params["uuid"]:"";
		$accessToken = $params["accessToken"]?$params["accessToken"]:"";
		$refreshToken = $params["refreshToken"]?$params["refreshToken"]:"";
		$scope = $params["scope"]?$params["scope"]:"";
		$tokenType = $params["tokenType"]?$params["tokenType"]:"";
		$dataArray = $params["dataArray"]?$params["dataArray"]:array();
		$tokenExpiresIn = $params["tokenExpiresIn"]?$params["tokenExpiresIn"]:"";
		$autoLogin = isset($params["autoLogin"])?$params["autoLogin"]:true;	
		//delete only scope data, not wordpress data
		$deleteUserData = isset($params["deleteUserData"])?$params["deleteUserData"]:false;	
		$shouldRefreshToken = isset($params["shouldRefreshToken"])?$params["shouldRefreshToken"]:true;	

		global $social_nation_itsme_oauth_login_mb;
		//validate data before insert or update
		$userId = $social_nation_itsme_oauth_login_mb->validate_integer($userId);
		$firstName = $social_nation_itsme_oauth_login_mb->check_input_text($firstName);
		$lastName = $social_nation_itsme_oauth_login_mb->check_input_text($lastName);
		$email = $social_nation_itsme_oauth_login_mb->check_input_email($email);
		$uuid = $social_nation_itsme_oauth_login_mb->check_input_text($uuid);
		$accessToken = $social_nation_itsme_oauth_login_mb->check_input_text($accessToken);
		$refreshToken = $social_nation_itsme_oauth_login_mb->check_input_text($refreshToken);
		$tokenExpiresIn = $social_nation_itsme_oauth_login_mb->check_input_text($tokenExpiresIn);
		$scope = $social_nation_itsme_oauth_login_mb->check_input_text($scope);
		$tokenType = $social_nation_itsme_oauth_login_mb->check_input_text($tokenType);

		/*
		if($firstName || $lastName)
			$displayName = $firstName." ".$lastName;
		*/
		$userData = array();		

		if($userId){
			/*
			$user_data = get_userdata($userId);
			//if !$deleteUserData keep old value
			if(!$displayName && $deleteUserData)
				$displayName = $user_data->user_login;
			$userData["ID"] = $userId;
			//delete previous
			if($deleteUserData){
				$userData["display_name"] = "";
				$userData["first_name"] = "";
				$userData["last_name"] = "";
				$userData["user_email"] = "";
			}
	
			if($displayName)
				$userData["display_name"] = $displayName;
			if($firstName)
				$userData["first_name"] = $firstName;
			if($lastName)
				$userData["last_name"] = $lastName;
			if($email)
				$userData["user_email"] = $email;
			$newUserId = wp_update_user($userData);
			*/
			$newUserId = $userId;
		}
		else{
			//se nuovo utente controllare nei campi degli scope se ci sono first_name,last_name,nickname,email
			$firstName = ""; $lastName = ""; $email = ""; $displayName=""; $nickname="";
			foreach($dataArray as $item){
				$dataTypeSlug = strtolower($item["dataTypeSlug"]);
				if($dataTypeSlug=="firstname" || $dataTypeSlug=="first_name" || $dataTypeSlug=="name"){
					$firstName = $item["value"];
				}
				else if($dataTypeSlug=="lastname" || $dataTypeSlug=="last_name"){
					$lastName = $item["value"];
				}
				else if($dataTypeSlug=="email" || $dataTypeSlug=="e_mail" 
					|| $dataTypeSlug=="user_mail" || $dataTypeSlug=="usermail"
					|| $dataTypeSlug=="user_email" || $dataTypeSlug=="useremail"){
					$email = $item["value"];
				}
				else if($dataTypeSlug=="nickname" || $dataTypeSlug=="nick_name"){
					$nickname = $item["value"];
				}
			}
			if(!$firstName && !$lastName){
				$firstName = $nickname;
				if(!$firstName){
					$firstName = $email;
				}
			}
			if($firstName || $lastName)
				$displayName = $firstName." ".$lastName;
			reset($dataArray);

			$password = wp_generate_password();
			//check if already exists
			$username = self::generateUsername(array(
				"firstName"=>$firstName,
				"lastName"=>$lastName,
				"uuid"=>$uuid
			));
			
			if(!$displayName)
				$displayName = $username;
			//A string that contains a URL-friendly name for the user.
			//$nicename = $username;
			$userData["display_name"] = $displayName;
			$userData["first_name"] = $firstName;
			$userData["last_name"] = $lastName;
			//insert only when user is created
			$userData["user_pass"] = $password;
			$userData["user_login"] = $username;
			$userData["user_email"] = $email;
			//$userData["user_nicename"] = $nicename;
			//$userData["nickname"] = $nickname;
			if(is_multisite()){
				$newUserId = wpmu_create_user(
					$username, 
					$password, 
					$email
				);
				$userDataMultisite = array();
				$userDataMultisite["ID"] = $newUserId;
				if($displayName)
					$userDataMultisite["display_name"] = $displayName;
				if($firstName)
					$userDataMultisite["first_name"] = $firstName;
				if($lastName)
					$userDataMultisite["last_name"] = $lastName;
				wp_update_user($userDataMultisite);		

				if($social_nation_itsme_oauth_login_mb->enable_log){
					file_put_contents(
						WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
						"SocialNationItsMeOAuthLoginMBUser::login() multisite registration completed\n",
						FILE_APPEND
					);
				}	
			}
			else{
				$newUserId = wp_insert_user($userData);
			}
		}

		if(is_wp_error($newUserId)){
			if($social_nation_itsme_oauth_login_mb->enable_log){
				file_put_contents(
					WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
					"SocialNationItsMeOAuthLoginMBUser::login() errore nella creazione o aggiornamento dell'utente: ".
					$newUserId->get_error_message()."\n",
					FILE_APPEND
				);
			}
			return $newUserId->get_error_message();

		}

		if($social_nation_itsme_oauth_login_mb->enable_log){
			file_put_contents(
				WPSN_IM_OAUTH_LM_LOG_FILE_PATH, 
				"SocialNationItsMeOAuthLoginMBUser::login() deleteUserData=$deleteUserData - ".
				"count(dataArray)=".count($dataArray)." - autoLogin=$autoLogin - ".
				"shouldRefreshToken=".$shouldRefreshToken."\n",
				FILE_APPEND
			);
		}

		$scopeSlug = str_replace(
			WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER, 
			WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER_REPLACER, 
			$scope
		);

		$scopeKey = WPSN_IM_OAUTH_LM_PREFIX.WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER.
			$scopeSlug.WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER;

		if($deleteUserData){
			$userMetaArray = get_user_meta($newUserId);
			foreach($userMetaArray as $key => $val){
				if(	
					$key==WPSN_IM_OAUTH_LM_PREFIX."identifier" || 
					($key==$scopeKey."token_last_date" && !$shouldRefreshToken)
				)continue;

				//delete all entries for this scope
				if(strpos($key, $scopeKey) === 0){
					delete_user_meta($newUserId, $key);
				}
			}
		}

		foreach($dataArray as $item){
			//ensure there aren't WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER character cause we use it as separator
			$itemKey = 
				str_replace(
					WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER, 
					WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER_REPLACER, 
					$item["microscopeSlug"]
				).
				WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER.
				str_replace(
					WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER, 
					WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER_REPLACER, 
					$item["dataTypeSlug"]
				);
			//data is validated/escaped in update_user_meta 
			update_user_meta(
				$newUserId, 
				//WPSN_IM_OAUTH_LM_PREFIX.WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER.$itemKey,
				$scopeKey.$itemKey,
				$item["value"]
			);
		}

		//data is validated/escaped in update_user_meta 
		update_user_meta(
			$newUserId, 
			$scopeKey."at",
			$accessToken
		);
		update_user_meta(
			$newUserId, 
			$scopeKey."rt",
			$refreshToken
		);
		update_user_meta(
			$newUserId, 
			$scopeKey."token_expires_in",
			$tokenExpiresIn
		);
		update_user_meta(
			$newUserId, 
			$scopeKey."token_type",
			$tokenType
		);		
		update_user_meta(
			$newUserId, 
			$scopeKey."token_scope",
			$scope
		);

		if($shouldRefreshToken){
			update_user_meta(
				$newUserId, 
				$scopeKey."token_last_date",
				time()
			);
		}

		/*
		update_user_meta(
			$newUserId, 
			WPSN_IM_OAUTH_LM_PREFIX."token_type",
			$tokenType
		);
		*/
		//if(!$userId){
			//insert only when user is created
			update_user_meta(
				$newUserId, 
				WPSN_IM_OAUTH_LM_PREFIX."identifier",
				$uuid
			);
		//}

		//finally update wp user info, only if user already present
		if($userId){
			$firstName = ""; $lastName = ""; $email = ""; $displayName="";
			$userMetaArray = get_user_meta($newUserId);
			foreach($userMetaArray as $key => $val){
				$real_val = $val;
				if(is_array($val) && count($val)>0){
					$real_val = "".$val[0];
				}
				//if it is an itsme field
				if(strpos($key, WPSN_IM_OAUTH_LM_PREFIX) === 0){
					$fieldKey = explode(WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER, $key);
					$fieldKey = end($fieldKey);
					$lower_key = strtolower($fieldKey);
					if(strpos($lower_key, "firstname")!==false || strpos($lower_key, "first_name")!==false ||
						strpos($lower_key, "name")===0){
						$firstName = $real_val;
					}
					elseif(strpos($lower_key, "lastname")!==false || strpos($lower_key, "last_name")!==false){
						$lastName = $real_val;
					}
					elseif(strpos($lower_key, "email")!==false || strpos($lower_key, "e_mail")!==false ||
						strpos($lower_key, "user_mail")!==false || strpos($lower_key, "usermail")!==false ||
						strpos($lower_key, "user_email")!==false || strpos($lower_key, "useremail")!==false
					){
						$email = $real_val;
					}
				}
			}
			if($firstName || $lastName)
				$displayName = $firstName." ".$lastName;
			$currUserData = array();
			$currUserData["display_name"] = $displayName;
			$currUserData["first_name"] = $firstName;
			$currUserData["last_name"] = $lastName;
			$currUserData["user_email"] = $email;
			$currUserData["ID"] = $newUserId;
			wp_update_user($currUserData);
		}

		if($autoLogin){
			$wpUser = get_userdata( $newUserId );
			// Set WP auth cookie
			wp_set_auth_cookie( $newUserId, true );

			// let keep it std
			do_action( 'wp_login', $wpUser->user_login, $wpUser );
		}
		

		return true;
	}

	public static function generateUsername($params=array()){
		$firstName = $params["firstName"]?$params["firstName"]:false;
		$lastName = $params["lastName"]?$params["lastName"]:false;
		$uuid = $params["uuid"]?$params["uuid"]:false;
		$username="";
		if($firstName || $lastName){
			$username=$firstName.$lastName;
		}
		else{
			$username="ItsMe@".$uuid;
		}
		$username = sanitize_user( $username, true );
		$username = trim( str_replace( array( ' ', '.' ), '_', $username ) );
		$username = trim( str_replace( '__', '_', $username ) );
		// user name should be unique
		if( username_exists( $username ) ){
			$i = 1;
			$user_login_tmp = $username;

			do{
				$user_login_tmp = $username . "_" . ($i++);
			}while( username_exists ($user_login_tmp));

			$username = $user_login_tmp;
		}
		return $username;
	}
}

?>