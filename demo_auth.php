<?php    
error_reporting(E_ALL);
ini_set('display_errors', '1'); 
    require_once __DIR__ . '/Instagram/Instagram.php';
    require_once __DIR__ . '/Instagram/Exception/InstagramException.php';

    use Instagram\Instagram;


    /* create Instagram class with set tokens
     */ 
    $count = 100;
    $tag = '';
    $type = 'image';
    $source = '292905155';

    $code = (!empty($_GET['code']))? $_GET['code'] : '';
    
    $client_id = 'YOUR CLIENT ID';
    $client_secret = 'YOUR CLIENT SECRET';
    $redirect = 'http://localhost:8081/instagram/demo.php';

    $instagram = new Instagram($client_id, $client_secret, $redirect);
    
?>
<a href="<?= $instagram->getAuthUrl() ?>">Authorize</a>

<? if ($code != '') {
    $instagram->getAccessToken($code);
    $user = $instagram->getUser();

}
?>
<p>User: <?= $user->username ?></p>
<p><img src="<?= $user->profile_picture ?>" /></p>


<?
    $res = $instagram->get('/v1/media/popular');

    var_dump($res);
    ?>
