jQuery(function($){
	var $reportTable = $("#iml_options_report_table");
	var $executeReportButton = $reportTable.find("#socialNationItsmeLoginReportExecute");
	$executeReportButton.click(
		function(event){
			//SocialNationItsmeUserReport.$subscriberUpdatedLog = false;
			if(SocialNationItsmeUserReport.divWarning)
				SocialNationItsmeUserReport.divWarning.hide();
			SocialNationItsmeUserReport.init();
			SocialNationItsmeUserReport.startLoadingSpinner();
			SocialNationItsmeUserReport.requestReport();
			event.preventDefault(); 
			return false;
		}
	);
});