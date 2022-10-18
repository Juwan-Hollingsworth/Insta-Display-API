<?php 
    require_once('__defines.php');


    //Class that calls to IG Basic Display API
    Class ig_basic_display_api {

        //declare variables
        private $_appId = INSTAGRAM_APP_ID;
		private $_appSecret = INSTAGRAM_APP_SECRET;
		private $_redirectUrl = INSTAGRAM_APP_REDIRECT_URI;
        private $_getCode = '';
        private $_apiBaseUrl = 'https://api.instagram.com/';
        private $_graphBaseUrl ='https://graph.instagram.com/';
        private $_userAccessToken = '';
        private $_userAccessTokenExpires = '';
       
        public $authorizationUrl = '';
        public $hasUserAccessToken = false;
        public $userId = '';


        //call f(x) when Class is instantiated.
        function __construct( $params ){
            //save instagram code
            //get_code = IG URL Identifier 
            $this->_getCode = $params['get_code'];
            //get a access token 
            $this->_setUserInstagramAccessToken ( $params );
            //if no access token in URL generate authorization url. 
            $this->_setAuthorizationUrl();
        }

        public function getUserAccessToken(){
            return $this->_userAccessToken;

        }

        //return token in secs
        public function getUserAccessTokenExpires(){
            return $this->_userAccessTokenExpires;

        }
        //get auth URL
        // send app_id, redirect URL, scope & response type as varaibles
        // scope =  access  permission 
        //send users to instagram to auth w/ app
        private function _setAuthorizationUrl(){
            $getVars = array( 
				'app_id' => $this->_appId,
				'redirect_uri' => $this->_redirectUrl,
				'scope' => 'user_profile,user_media',
				'response_type' => 'code'
			);

            //create url (build query string)
            $this->authorizationUrl = $this->_apiBaseUrl . 'oauth/authorize?' . http_build_query( $getVars );
        }

        private function _setUserInstagramAccessToken( $params ){
            // check if we are passing an access token
            if ($params['access_token']){
                $this->_userAccessToken = $params['access_token'];
                $this->hasUserAccessToken = true;
                $this->userId = $params['user_id'];
            }
            // if get code exist > get access token
            // short lived access token
            elseif ( $params['get_code'] ) { // try to get a access token
				$userAccessTokenResponse = $this->_getUserAccessToken();
				$this->_userAccessToken = $userAccessTokenResponse['access_token'];
				$this->hasUserAccessToken = true;
                //set userId to user_id found in response
                $this->userId =$userAccessTokenResponse['user_id'];


                //get long lived access token
				$longLivedAccessTokenResponse = $this->_getLongLivedUserAccessToken();
				$this->_userAccessToken = $longLivedAccessTokenResponse['access_token'];
                //get time token expires (secs)
				$this->_userAccessTokenExpires = (int)$longLivedAccessTokenResponse['expires_in'];
            }


        }

        // build access token request params
        private function _getUserAccessToken() {
            $params = array (
                'endpoint_url' => $this->_apiBaseUrl . 'oauth/access_token',
				'type' => 'POST',
				'url_params' => array(
					'app_id' => $this->_appId,
					'app_secret' => $this->_appSecret,
					'grant_type' => 'authorization_code',
					'redirect_uri' => $this->_redirectUrl,
					'code' => $this->_getCode
                )
            );

            //make an API call passing in params
            // return response payload
            $response = $this->_makeApiCall( $params );
			return $response;
        }


        public function getUser(){
            $params = array(
				'endpoint_url' => $this->_graphBaseUrl . 'me',
				'type' => 'GET',
				'url_params' => array(
					'fields' => 'id,username,media_count,account_type',
				)
                );
                
                $response = $this->_makeApiCall( $params );
			return $response;

        }

        //get user posts function
        public function getUserMedia(){
            $params = array(
             'endpoint_url' => $this->_graphBaseUrl . $this->userId . '/media',
                        'type' => 'GET',
                        'url_params' => array(
                            'fields' => 'id,caption,media_type, media_url',
                        )
                        );

                              
                $response = $this->_makeApiCall( $params );
                return $response;
                    }    

 //get pages of content function
 public function getPaging( $pagingEndpoint ){
    $params = array(
     'endpoint_url' => $pagingEndpoint,
                'type' => 'GET',
                'url_params' => array(
                    'paging' => true
                )
                );

                      
        $response = $this->_makeApiCall( $params );
        return $response;
            }   


        //requires different endpoint URL
        //graph base URL
       private function _getLongLivedUserAccessToken(){
        $params = array(
            'endpoint_url' => $this->_graphBaseUrl . 'access_token',
            'type' => 'GET',
            'url_params' => array(
                'client_secret' => $this->_appSecret,
                'grant_type' => 'ig_exchange_token',
            )
        );
        $response = $this->_makeApiCall( $params );
        return $response;

       }

        // make API call via Curl
        private function _makeApiCall( $params){
            //intitialize curl
            $ch = curl_init();
            //set endpoint
            $endpoint = $params['endpoint_url'];

            //check what type of call
            // POST req settings different from GET
            if ( 'POST' == $params['type'] ) { 
                //POST req. build query based on URL params
				curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params['url_params'] ) );
				curl_setopt( $ch, CURLOPT_POST, 1 );
                // if get and paging is false
			} elseif ('GET' == $params['type'] && !$params['url_params']['paging'] ){
                //get request
                $params['url_params']['access_token'] = $this->_userAccessToken;

                //add params to endpoint
                $endpoint .= '?' . http_build_query($params['url_params']);
            }

            //general curl options
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //get response from instagram via curl
            $response = curl_exec( $ch);
            //close curl call
            curl_close( $ch);
            // store response in php arr
            $responseArray = json_decode($response, true);
            //if err show on page & close
            //!if no err return response
            if (isset ($responseArray['error_type'])){
                var_dump( $responseArray);
                die();
            } else {
                return $responseArray;
            }
        }

     //end   
    }