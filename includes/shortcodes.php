<?php
	class SocialNationItsMeOAuthLoginMBShortcodes{
		public function init(){
			add_shortcode( 
				'socialnation_itsme_login', 
				array( 
					$this,
					'socialNationItsmeLogin'
				) 
			);
		}

		public function socialNationItsmeLogin($atts){
			global $social_nation_itsme_oauth_login_mb;
			global $wp; 

			if(get_current_user_id())
				return "";
			$text = $atts["text"];
			$text_after = $atts["text_after"];
						
			$client_id = $social_nation_itsme_oauth_login_mb->client_id;
			$client_secret = $social_nation_itsme_oauth_login_mb->client_secret;
			if(!$client_id || !$client_secret){
				if(!$client_id && !$client_secret){
					echo "Errore shortcode: client_id e client_secret non sono stati impostati nei settaggi del plugin.";
					return;
				}
				elseif(!$client_id){
					echo "Errore shortcode: client_id non è stato impostato nei settaggi del plugin.";
					return;					
				}
				else{
					echo "Errore shortcode: client_secret non è stato impostato nei settaggi del plugin.";
					return;					
				}
			}

			if(!isset($atts["scope"])){
				echo "Errore shortcode: attributo 'scope' non trovato.";
				return;
			}			
			if(!isset($atts["image_uri"])){
				echo "Errore shortcode: attributo 'image_uri' non trovato.";
				return;
			}	
			$scope = isset($atts["scope"])?$atts["scope"]:"";
			$button_file_name = isset($atts["image_uri"])?$atts["image_uri"]:"";

			if(isset($atts["return_uri"]) && $atts["return_uri"]){
				$return_uri = $atts["return_uri"];
			}
			else{
				//if return url is not setted, set it to current page
				$return_uri = home_url(add_query_arg(array(),$wp->request));
			}
			$redirect_uri = urlencode(
				$social_nation_itsme_oauth_login_mb->addParamsToUri(array(
					"uri" => $return_uri,
					"params" =>"action=".WPSN_IM_OAUTH_LM_ACTION_AUTHENTICATE."&param="
				))
			);

			$href = 
				$social_nation_itsme_oauth_login_mb->itsme_login_url.$scope.
				"&client_id=$client_id&redirect_uri=$redirect_uri";
			$social_nation_itsme_oauth_login_mb->printItsmeLoginButton(
				array(
					"text" => $text,
					"href" => $href,
					"text_after" => $text_after,
					"scope" => $scope,
					"button_file_name" => $button_file_name,
				)
			);
		}
	}

	(new SocialNationItsMeOAuthLoginMBShortcodes())->init();
?>