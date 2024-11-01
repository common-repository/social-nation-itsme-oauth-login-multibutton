<?php 
class SocialNationItsMeOAuthLoginMBUtils{
	public static function convertDateToLocale($options=array()){
		$dateTime = $options["dateTime"]?$options["dateTime"]:"";
		$useLocalTimeZone = $options["useLocalTimeZone"]?$options["useLocalTimeZone"]:false;
		$printTimezone = $options["printTimezone"]?$options["printTimezone"]:false;

		$wpDate = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime->format('Y-m-d H:i:s'));		

		if($useLocalTimeZone){
			$wp_timezone = get_option('timezone_string');
			$wp_timezone = $wp_timezone?$wp_timezone:"UTC";
			$wpDate->setTimeZone(new DateTimeZone($wp_timezone));
		}
		else{
			$wp_timezone = $wpDate->getTimezone();
			if($wp_timezone)
				$wp_timezone = $wp_timezone->getName();
			else{
				$wp_timezone = "N.D.";
			}
		}

		$out = $wpDate->format("d/m/Y H:i:s");

		if($printTimezone){
			$out .= " (timezone: $wp_timezone)";
		}
		
		return $out;
	}
}
?>