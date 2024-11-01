<?php 

define( 'WPSN_IM_OAUTH_LM_PREFIX', "sn_im_oauth_lm_");
define( 'WPSN_IM_OAUTH_LM_PLUGIN_SLUG', "social-nation-itsme-oauth-login-multibutton");

//scope and microscope keys are separated by WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER
define( 'WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER', "..");
//if scope or microscope contain WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER, 
//it will be replaced with WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER_REPLACER
define( 'WPSN_IM_OAUTH_LM_SCOPE_SLUG_DELIMITER_REPLACER', "--");
?>