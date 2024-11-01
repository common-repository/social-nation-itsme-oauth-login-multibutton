jQuery(function($){
	var $itsmeForm = $("form#social_nation_itsme_oauth_login_mb_form");
	
	var $inputRedirectUri = $itsmeForm.find("input#sn_im_oauth_lm_redirect_uri");
	var $textRedirectUri = $itsmeForm.find("div#redirect_uri_text");
	var action = "action="+SocialNationItsmeOAuthLoginMBAdminGeneralSettings.action;
	var addActionParam = function(text){
		var val = $inputRedirectUri.val();
		if(!val.endsWith("?")){
			if(val.includes("?")){
				val+="&";
			}
			else{
				val+="?";	
			}
		}
		$textRedirectUri.html(val+action);
	};
	addActionParam();
	$inputRedirectUri.change(addActionParam);

	var $selectButtonLayout = $itsmeForm.find("select#sn_im_oauth_lm_button_file_name");
	var $loginButtonContainer = $itsmeForm.find("div#sn_im_oauth_lm_login_button_container");
	var $loginButtonImg = $loginButtonContainer.find("img");
	var $loginButtonInputBaseUrl = $loginButtonContainer.find("input#sn_im_oauth_lm_preview_base_url");
	
	var refreshPreview = function(){
		$loginButtonImg.attr(
			"src",
			$loginButtonInputBaseUrl.val()+$selectButtonLayout.val() 
		);
	};
	$selectButtonLayout.change(refreshPreview);
	refreshPreview();

	//refresh shortcode text
	var $inputScope = $itsmeForm.find("input#sn_im_oauth_lm_default_scope");
	var $selectButtonImageUri = $itsmeForm.find("select#sn_im_oauth_lm_button_file_name");
	var $shortcodeSyntaxDiv = $itsmeForm.find("div#sn_im_oauth_lm_shortcode_syntax");
	var $returnUri = $itsmeForm.find("input#sn_im_oauth_lm_return_url");
	var $clientId = $itsmeForm.find("input#sn_im_oauth_lm_client_id");
	var $clientSecret = $itsmeForm.find("input#sn_im_oauth_lm_client_secret");

	var $shortcodeErrorDiv = $itsmeForm.find("div#sn_im_oauth_lm_shortcode_syntax_error");

	var shortCodeText = "Your text before the button...";
	var shortCodeTextAfter = "Your text after the button...";
	
	var refreshShortCodeText = function(){
		var shortCodeScope = $inputScope.val();
		var shortCodeImageUri = $selectButtonImageUri.val();
		var returnUri = $returnUri.val();
		var shortCodeSyntax = '['+
			'socialnation_itsme_login '+
			'text="'+shortCodeText+'" ' + 
			'scope="'+shortCodeScope+'" '+
			'image_uri="'+shortCodeImageUri+'" '+
			'text_after="'+shortCodeTextAfter+'" '+
			'return_uri="'+returnUri+'"'+
		']';
		$shortcodeSyntaxDiv.html(shortCodeSyntax);
		//check if all data are valid
		var clientId = $clientId.val();
		var clientSecret = $clientSecret.val();
		var errorText = "";
		if(!shortCodeScope){
			errorText += "- Il campo 'Scope' non è valido.<br>";
		}
		if(!shortCodeImageUri){
			errorText += "- Il campo 'Layout Bottone Login' non è valido.<br>";
		}
		if(!clientId){
			errorText += "- Il campo 'OAuth Name (Client Id)' non è valido.<br>";
		}
		if(!clientSecret){
			errorText += "- Il campo 'OAuth Key (Client Secret)' non è valido.";
		}
		$shortcodeErrorDiv.html("");
		$shortcodeErrorDiv.hide();
		if(errorText){
			errorText = "I seguenti errori non permetteranno la corretta visualizzazione dello shortcode:<br>"+errorText;		
			$shortcodeErrorDiv.show();
			$shortcodeErrorDiv.html(errorText);
		}
	};

	$inputScope.keyup(refreshShortCodeText);
	$inputScope.change(refreshShortCodeText);
	$returnUri.keyup(refreshShortCodeText);
	$returnUri.change(refreshShortCodeText);
	$selectButtonImageUri.change(refreshShortCodeText);

	$clientId.keyup(refreshShortCodeText);
	$clientId.change(refreshShortCodeText);
	$clientSecret.keyup(refreshShortCodeText);
	$clientSecret.change(refreshShortCodeText);

	refreshShortCodeText();
});