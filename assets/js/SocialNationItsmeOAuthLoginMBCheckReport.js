var SocialNationItsmeUserReport = {
	init: function(){
		this.api = apigClientFactory.newClient();
		this.$reportTable = jQuery("#iml_options_report_table");
		this.gotErrors = false;
		if(this.$reportTable.length){
			//we are in backend admin report section
			if(!this.$divWarning )
				this.$divWarning = this.$reportTable.find("#socialNationItsmeLoginReportLogWarning");
			this.$divWarning.hide();

			if(!this.$divSuccess )
				this.$divSuccess = this.$reportTable.find("#socialNationItsmeLoginReportLogSuccess");
			this.$divSuccess.hide();

			if(!this.$subscriberUpdatedLog ){
				this.$subscriberUpdatedLog = this.$reportTable.find("#iml_updated_subscriber_log");
			}
			this.$subscriberUpdatedLog.html("");
		}
	},
	requestReport: function(){
		var params = {
			Authorization: socialNationItsmeAjax.auth,
		};

		var body = {id:"0"};
		var additionalParams = {};
		var mThis = this;
		mThis.updateUuidToUpdateLog({text:"USER REPORT: start "+(new Date()).toISOString()+".\n"});
		this.api.v2CustomerserviceExecuteUserreportPost(
			params,
			body,
			additionalParams
		).then(function(result){
			if(result.data){
				mThis.updateUuidToUpdateLog({text:"USER REPORT: Dati user report ricevuti.\n"});
				mThis.updateReport(result.data);
			}
			else{
				mThis.updateUuidToUpdateLog({text:"USER REPORT: ERRORE Dati user report NON ricevuti.\n\n", isError: true});
				mThis.finishLoadingSpinner({success: false});
			}	
		}).catch(function(error){
			console.log(error)
			mThis.updateUuidToUpdateLog({text:"USER REPORT: ERRORE errore userreportpost.\n\n", isError: true});
			mThis.finishLoadingSpinner({success: false});
		})
	},
	updateReport: function(data){
		var totalSubscriber = data.totalSubscriber;
		var uuidToUpdate = data.uuidToUpdate;
		var data = {
			action: 'iml_user_report',
			data: data,
		};
		var mThis = this;
		// We can also pass the url value separately from ajax_url for front end AJAX implementations
		jQuery.post(
			socialNationItsmeAjax.ajaxurl, 
			data, 
			function(response) {
				if(response.success){
					//reload
					mThis.finishLoadingSpinner({success: true, data: response.data});
					mThis.updateUuidToUpdateLog({text:"USER REPORT: FINE Dati user report aggiornati.\n\n"});
				}
				else{
					mThis.finishLoadingSpinner({success: false});
					mThis.updateUuidToUpdateLog({text:"USER REPORT: ERRORE Dati user report NON aggiornati.\n\n", isError: true});
				}
				mThis.updatePendingUsers({data: data.data});
			},
			"json"
		).fail(function(error){
			mThis.finishLoadingSpinner({success: false});
			mThis.updateUuidToUpdateLog({text:"USER REPORT: ERRORE errore ajax user_report.\n\n", isError: true});
			mThis.updatePendingUsers({data: data.data});
		});
	},
	updatePendingUsers: function(params){
		params = params || {};
		var data = params.data || {};
		var uuidToUpdate = data.uuidToUpdate;
		var numUpdated = 0;

		if(uuidToUpdate && Array.isArray(uuidToUpdate)){
			this.updateUuidToUpdateLog({text:"UPDATE USERS: Utenti It'sMe in attesa di aggiornamento = "+uuidToUpdate.length+".\n"});	
			this.uuidUpdatedNumber = 0;
			this.numToUpdate = uuidToUpdate.length;
			this.startLoadingSpinnerUuidToUpdate();
			//for(key in uuidToUpdate){
				this.updatePendingUserRecursiveAjaxCall({
					uuidToUpdate: uuidToUpdate,
					uuidToUpdateKey: 0
				});
			//}				
		}
		else{
			this.finishLoadingSpinnerUuidToUpdate();
			mThis.updateUuidToUpdateLog({text:"UPDATE USERS: ERRORE uuidToUpdate not an array.\n\N", isError: true});
		}
	},
	updatePendingUserRecursiveAjaxCall: function(params){
		params = params || {};
		var uuidToUpdate = params.uuidToUpdate || {};
		var uuidToUpdateKey = params.uuidToUpdateKey || 0;
		if(uuidToUpdateKey >= uuidToUpdate.length){
			this.finishLoadingSpinnerUuidToUpdate();
			this.updateUuidToUpdateLog({text:"UPDATE USERS: FINE aggiornamento utenti.\n\n"});
			this.saveLog();
			if(!this.gotErrors){
				if(this.$reportTable.length)
					this.$divSuccess.css('display','inline-block');
			}
			return;
		}
		var uuid = uuidToUpdate[uuidToUpdateKey];
		var data = {
			action: 'iml_user_report_update_pending',
			data: uuid,
		};

		var mCallback = function(){
			mThis.updatePendingUserRecursiveAjaxCall({
				uuidToUpdate: uuidToUpdate,
				uuidToUpdateKey: ++uuidToUpdateKey
			});
		};

		var mThis = this;
		mThis.updateUuidToUpdateLog({text:"\nUPDATE USERS: Inizio aggiornamento utente. (uuid="+JSON.stringify(uuid)+").\n"});
		jQuery.post(
			socialNationItsmeAjax.ajaxurl, 
			data, 
			function(response) {
				if(response.success){
					//reload
					mThis.uuidUpdatedNumber++;
					mThis.updateLoadingSpinnerUuidToUpdate();
					mThis.updateUuidToUpdateLog({text:"UPDATE USERS: Dati utente Wordpress ricevuti. (uuid="+JSON.stringify(uuid)+").\n"});

					var currScope = null;
					//if(response.data && response.data.userMeta){
					if(response.data && response.data.scopeArray){
						for(var scopeArrayIdx in response.data.scopeArray){
							currScope = response.data.scopeArray[scopeArrayIdx];
							var at = null; var rt = null; var exp = null;		
							if(!currScope.fields){
								continue;
							}

							//get at
							if(currScope.fields.at){	
								if(Array.isArray(currScope.fields.at)){
									if(currScope.fields.at.length>0){
										at = currScope.fields.at[0];
									}
								}
								else{
									at = currScope.fields.at;
								}
							}

							//get rt
							if(currScope.fields.rt){	
								if(Array.isArray(currScope.fields.rt)){
									if(currScope.fields.rt.length>0){
										rt = currScope.fields.rt[0];
									}
								}
								else{
									rt = currScope.fields.rt;
								}
							}

							//get exp
							if(currScope.fields.token_expires_in){	
								if(Array.isArray(currScope.fields.token_expires_in)){
									if(currScope.fields.token_expires_in.length>0){
										exp = parseInt(currScope.fields.token_expires_in[0]);
									}
								}
								else{
									exp = parseInt(currScope.fields.token_expires_in);
								}
							}

							var token_last_date = 0;
							//get token_last_date
							if(currScope.fields.token_last_date){	
								if(Array.isArray(currScope.fields.token_last_date)){
									if(currScope.fields.token_last_date.length>0){
										token_last_date = parseInt(currScope.fields.token_last_date[0]);
									}
								}
								else{
									token_last_date = parseInt(currScope.fields.token_last_date);
								}
							}

							//get token_type
							var token_type = "";
							if(currScope.fields.token_type){	
								if(Array.isArray(currScope.fields.token_type)){
									if(currScope.fields.token_type.length>0){
										token_type = currScope.fields.token_type[0];
									}
								}
								else{
									token_type = currScope.fields.token_type;
								}
							}	
							//get token_scope
							var token_scope = "";
							if(currScope.fields.token_scope){	
								if(Array.isArray(currScope.fields.token_scope)){
									if(currScope.fields.token_scope.length>0){
										token_scope = currScope.fields.token_scope[0];
									}
								}
								else{
									token_scope = currScope.fields.token_scope;
								}
							}			

							var getUserDataParams = {
								accessToken: at,
								refreshToken: rt,
								expiresIn: exp,
								tokenType: token_type,
								scope: token_scope,
								onGetUserDataSuccess: function(data){
									mThis.onGetUserDataSuccess({uuid: uuid});
								},
								onGetUserDataError: function(data){
									mThis.onGetUserDataError(data, {uuid: uuid}); 
									mCallback();
								},
								onElaborateLoginSuccess: function(data){
									mThis.onElaborateLoginSuccess({uuid: uuid}); 
									mCallback();
								},
								onElaborateLoginError: function(data){
									mThis.onElaborateLoginError(data, {uuid: uuid}); 
									mCallback();
								},
								onGetUserDataUserNotSubscribed: function(data){
									mThis.onGetUserDataUserNotSubscribed({uuid: uuid});
								},
								autoLoginUser: false,
								deleteUserData: true,
								shouldRefreshToken: false,
								uuid: uuid
							};

							// if token expired, get it from refresh and then call getUserDataWrapper
							var expire_time = token_last_date+exp;
							var now = parseInt(Date.now()*0.001);
							if(expire_time<now){
								mThis.updateUuidToUpdateLog({
									text:"UPDATE USERS: Token scaduto (last_date="+token_last_date+",exp="+exp+",expire_time="+expire_time+"), nuova richiesta token. (uuid="+JSON.stringify(uuid)+").\n"
								});
								SnImlApiClient.refreshToken({
									authorization: response.data.additionalData.authorization,
									refreshToken: rt,
									redirectUri: response.data.additionalData.redirectUri,
									onSuccess: function(data){
										mThis.onRefreshTokenSuccess(
											data, 
											{uuid: uuid, getUserDataParams:getUserDataParams}
										);
									},
									onError: function(data){
										mThis.onRefreshTokenError(data, {uuid: uuid, mCallback: mCallback});
									},
								});
							}
							else{
								var tokenExpireSeconds = expire_time-now;
								var tokenExpireString = "";
								if(tokenExpireSeconds>60){
									tokenExpireMinute = parseInt(tokenExpireSeconds/60);
									tokenExpireSeconds = tokenExpireSeconds%60;
									tokenExpireString = tokenExpireMinute+" minuti e "+tokenExpireSeconds+" secondi";
								}
								else{
									tokenExpireString = tokenExpireSeconds+" secondi";	
								}

								mThis.updateUuidToUpdateLog({text:"UPDATE USERS: token valido per "+tokenExpireString+" "+at+" \n"});
								//else call getUserData
								SnImlApiClient.getUserData(getUserDataParams);
							}
						}
					}
					else{
						mThis.updateUuidToUpdateLog({text:"UPDATE USERS: ERRORE User meta non disponibili (uuid="+JSON.stringify(uuid)+").\n", isError: true});
						mCallback();
					}
				}
				else{
					mThis.updateUuidToUpdateLog({text:"UPDATE USERS: ERRORE Non è stato possibile trovare l'utente (uuid="+JSON.stringify(uuid)+").\n", isError: true});
					mCallback();
				}
				/*
				mThis.updatePendingUserRecursiveAjaxCall({
					uuidToUpdate: uuidToUpdate,
					uuidToUpdateKey: ++uuidToUpdateKey
				});
				*/
			},
			"json"
		).fail(function(error){
			mThis.finishLoadingSpinnerUuidToUpdate();
			mThis.updateUuidToUpdateLog({text:"UPDATE USERS: ERRORE errore ajax user_report_update_pending (uuid="+JSON.stringify(uuid)+").\n\n", isError: true});
			mCallback();
		});
	},

	onRefreshTokenSuccess: function(result, params){
		params = params || {};
		result = result || {};
		result = result.data;
		var uuid = params.uuid || {};
		var getUserDataParams = params.getUserDataParams || {};
		var token = result.accessToken;
		var refreshToken = result.refreshToken;
		var tokenExpiresIn = result.expiresIn;
		getUserDataParams.accessToken = token;
		getUserDataParams.refreshToken = refreshToken;
		getUserDataParams.expiresIn = tokenExpiresIn;
		getUserDataParams.shouldRefreshToken = true;
		SnImlApiClient.getUserData(getUserDataParams);
		this.updateUuidToUpdateLog({text:"UPDATE USERS: Token aggiornato. (uuid="+JSON.stringify(uuid)+").\n"});
	},
	onRefreshTokenError: function(error, params){
		params = params || {};
		var mCallback = params.mCallback || false
		var uuid = params.uuid || {};
		this.updateUuidToUpdateLog({
			text: "UPDATE USERS: ERRORE Token non aggiornato, l'utente deve rieffettuare il login (uuid="+JSON.stringify(uuid)+") (possibili cause: auth fallita o refreshtoken errato).\n", 
			isError: true
		});
		//probably token has expired
		if(mCallback)
			mCallback();
	},

	onGetUserDataSuccess: function(params){
		params = params || {};
		var uuid = params.uuid || {};
		this.updateUuidToUpdateLog({text:"UPDATE USERS: Dati Itsme ricevuti (uuid="+JSON.stringify(uuid)+").\n"});
	},
	onGetUserDataUserNotSubscribed: function(params){
		params = params || {};
		var uuid = params.uuid || {};
		this.updateUuidToUpdateLog({text:"UPDATE USERS: Utente NON sottoscritto a questo customer service, i dati itsme verranno cancellati (uuid="+JSON.stringify(uuid)+").\n"});
	},
	onGetUserDataError: function(error, params){
		params = params || {};
		var uuid = params.uuid || {};
		console.log("onGetUserDataError");
		this.updateUuidToUpdateLog({text:"UPDATE USERS: ERRORE Dati Itsme NON ricevuti (uuid="+JSON.stringify(uuid)+").\n", isError: true});
		//probably token has expired
	},

	onElaborateLoginSuccess: function(params){
		params = params || {};
		var uuid = params.uuid || {};
		console.log("onElaborateLoginSuccess");
		this.updateUuidToUpdateLog({text:"UPDATE USERS: Dati utente wordpress aggiornati. (uuid="+JSON.stringify(uuid)+").\n"});
	},
	onElaborateLoginError: function(error, params){
		params = params || {};
		var uuid = params.uuid || {};
		console.log("onElaborateLoginError");	
		this.updateUuidToUpdateLog({text:"ERRORE Dati utente wordpress NON aggiornati.  (uuid="+JSON.stringify(uuid)+").\n", isError: true});
	},

	startLoadingSpinner: function(){
		if(this.$reportTable.length){
			var srt = "<div style='display:inline-block' class='update-message updating-message'><p></p></div>";
			this.$totalSubscriber = this.$reportTable.find("#iml_total_subscriber");
			this.$totalSubscriberOld = this.$totalSubscriber.html();
			this.$totalSubscriber.html(srt);
			this.$lastReportDate = this.$reportTable.find("#iml_last_report_date");
			this.$lastReportDateOld = this.$lastReportDate.html();
			this.$lastReportDate.html(srt);
		}
	},
	finishLoadingSpinner: function(params){
		params = params || {};
		var data = params.data || {};
		var success = params.success || false;
		if(this.$reportTable.length){
			if(success){
				this.$totalSubscriber.html(data.totalSubscriber);
				this.$lastReportDate.html(data.lastReportDate);					
			}
			else{
				this.$totalSubscriber.html(this.$totalSubscriberOld);
				this.$lastReportDate.html(this.$lastReportDateOld);
			}
		}
	},
	startLoadingSpinnerUuidToUpdate: function(params){
		params = params || {};
		if(this.$reportTable.length){
			var srt = "<div style='display:inline-block' class='update-message updating-message'><p>aggiornamento informazioni in corso...</p></div>";
			this.$subscriberUpdatedNumberSpinner = this.$reportTable.find("#iml_updated_subscriber_number_spinner");
			this.$subscriberUpdatedNumberSpinner.html(srt);
			this.$subscriberUpdatedNumber = this.$reportTable.find("#iml_updated_subscriber_number");
			this.$subscriberUpdatedNumber.html("0/"+this.numToUpdate);
		}
	},
	updateLoadingSpinnerUuidToUpdate: function(params){
		params = params || {};
		if(this.$reportTable.length){
			this.$subscriberUpdatedNumber = this.$reportTable.find("#iml_updated_subscriber_number");
			this.$subscriberUpdatedNumber.html(this.uuidUpdatedNumber+"/"+this.numToUpdate);
		}
	},
	finishLoadingSpinnerUuidToUpdate: function(params){
		params = params || {};
		var srt = "";
		if(this.$reportTable.length){
			this.$subscriberUpdatedNumberSpinner.html("");
		}
	},
	updateUuidToUpdateLog(params){
		params = params || {};
		var text = params.text || "";
		var isError = params.isError || "";
		if(this.$reportTable.length){
			if(isError){
				this.gotErrors = true;
				this.$divWarning.css('display','inline-block');
			}
		}
		else{
			//here is the case that the user report is executed not in social nation report tab admin panel
			//so we store it in a tmp div and save the html() value
			if(!this.$subscriberUpdatedLog )
				this.$subscriberUpdatedLog = jQuery("<div/>");
		}
		this.$subscriberUpdatedLog.html(this.$subscriberUpdatedLog.html()+text);
	},
	saveLog: function(params){
		var data = {
			action: 'iml_user_report_save_log',
			text: this.$subscriberUpdatedLog.text()
		};
		var mThis = this;
		jQuery.post(
			socialNationItsmeAjax.ajaxurl, 
			data, 
			function(response) {
				mThis.updateUuidToUpdateLog({text:"USER REPORT: Log salvato.\n"});
			},
			"json"
		).fail(function(error){
			mThis.updateUuidToUpdateLog({text:"USER REPORT: ERRORE errore ajax user_report_save_log.\n", isError: true});
		});
	}
};
/*
SocialNationItsmeUserReport.init();
SocialNationItsmeUserReport.startLoadingSpinner();
SocialNationItsmeUserReport.requestReport();
*/

