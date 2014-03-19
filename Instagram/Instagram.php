<?php 

/**
 * InstagramAPI
 * PHP library to interact with the Instagram API.
 * Allows both authenticated and non-authenticated requests.
 * Credit goes to Facebook PHP SDK and https://github.com/ricardoper/TwitterOAuth for inspiration.
 * 
 * @author Dennis Pierce <github@stubenbaines>
 * @company Gotham Pixel Factory (www.gothampixelfactory.com)
 * @copyright 2014
 */

namespace Instagram;

use Instagram\Exception\InstagramException;

class Instagram {
    const VERSION = '/v1';
    protected $url = 'https://api.instagram.com';
    protected $authUrl = 'https://api.instagram.com/oauth/authorize/';
    protected $accessUrl = 'https://api.instagram.com/oauth/access_token';
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;
    protected $accessToken;
    protected $user;
    protected $call;
    protected $method = 'GET';
    protected $getParams = array();

    function __construct($clientId = null, $clientSecret = null, $redirectUri = null) {
        if (!in_array('curl', get_loaded_extensions())) {
            throw new \Exception('You need to install cURL, see: http://curl.haxx.se/docs/install.html');
        }

        if (!isset($clientId)) {
            throw new \Exception('You need to pass at least a clientId.');
        }

        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
    }

    /**
     * Step one of an authenticated request to get an access token.
     * To do that, you need to go through the authorization url. This 
     * returns the auth url.
     * @return string The Instagram authentication url.
    */
    public function getAuthUrl() {
        return $this->authUrl . '?client_id=' . $this->clientId . '&redirect_uri=' . $this->redirectUri . '&response_type=code';
    }

    /**
     * Step two of the authentication process.
     * Pass in a code from step one and then make a request to Instagram to get an access token.
     * @return string The access token.
     */
    public function getAccessToken($code) {
        $params = array(
            "client_id" => $this->clientId,
            "client_secret" => $this->clientSecret,
            "grant_type" => 'authorization_code',
            "redirect_uri" => $this->redirectUri,
            "code" => $code
        );

        $url = $this->accessUrl;
        $response = $this->sendRequest(array(
            'url' => $url,
            'params' => $params,
            'method' => 'post'
        ));
        
        $this->accessToken = $response->access_token;
        $this->user = $response->user;
        
    }

    public function getUser() {
        return $this->user;
    }

    /**
    * Converting parameters array to a single string with encoded values
    *
    * @param array $params Input parameters
    * @return string Single string with encoded values
    */
    protected function getParams(array $params) {
        $r = '';

        ksort($params);

        foreach ($params as $key => $value) {
            $r .= '&' . $key . '=' . rawurlencode($value);
        }

        unset($params, $key, $value);

        return trim($r, '&');
    }

    /**
     * Api call to Instagram. This is a raw call where you have to pass
     * in the valid endpoint.
     *
     * @param string $endpoint Instagram endpoint.
     * @param array $getParams GET params we are sending.
     */
    public function get($endpoint, array $getParams = null) {
        $this->call = self::VERSION . $endpoint;

        if ($getParams !== null && is_array($getParams)) {
            $this->getParams = $getParams;
        }

        return $this->sendRequest();
    }

    protected function processOutput($response) {
        return json_decode($response);
    }

    /**
     * Builds the url.
     * If access token is available, use that, otherwise try sending the client_id.
     */
    protected function getUrl() {
        $getParams = '';
        $getParams = $this->getParams($this->getParams);

        $request = $this->url . $this->call . '?' . $getParams;

        if (isset($this->accessToken)) {
            $request .= '&access_token=' . $this->accessToken;
        } else {
            $request .= '&client_id=' . $this->clientId;
        }
        return $request;
        
    } 

    /**
     * Send a request to Instagram.
     *
     * @throws Exception\InstagramException
     * @return string 
     */
    protected function sendRequest(array $options = array()) {
        $url = $this->getUrl();
        
        // Defaults
        $options = array_merge(array(
            'method' => 'get',
            'url' => $url,
            'params' => ''
        ), $options);
        

        $cOptions = array(
            CURLOPT_URL => $options['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        );

        // Check if we are doing a post.
        if ($options['method'] == 'post') {
            $cOptions[CURLOPT_POST] = true;
            $cOptions[CURLOPT_POSTFIELDS] = $options['params']; 
        }

        $c = curl_init();
        curl_setopt_array($c, $cOptions);
        $response = curl_exec($c);
        curl_close($c);
        unset($cOptions, $c);
        return $this->processOutput($response);
    }
}
