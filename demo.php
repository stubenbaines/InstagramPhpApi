<?php    
error_reporting(E_ALL);
ini_set('display_errors', '1'); 
    require_once __DIR__ . '/Instagram/Instagram.php';
    require_once __DIR__ . '/Instagram/Exception/InstagramException.php';

    use Instagram\Instagram;


    /* create Instagram class with just client id. These will be unauthed requests. */ 
    $source = '292905155';
    $client_id = 'YOUR CLIENT ID';
    $instagram = new Instagram($client_id);
    
?>
<?
    $res = $instagram->get('/media/popular');
    

    // User endpoints.
    $callParams = array(
        'q' => 'dennis'
    );
    $res = $instagram->get('/users/search', $callParams);
    
    $res = $instagram->get('/users/3');


    // This will fail because it needs an access token.
    $callParams = array(
        'count' => 10
    );
    $res = $instagram->get('/users/self/feed', $callParams);
    
    $callParams = array(
        'count' => 10
    );
    $res = $instagram->get('/users/3/media/recent', $callParams); 
    var_dump($res);
?>
