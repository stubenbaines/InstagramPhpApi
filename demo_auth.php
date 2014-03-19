<?php    
error_reporting(E_ALL);
ini_set('display_errors', '1'); 
    require_once __DIR__ . '/Instagram/Instagram.php';
    require_once __DIR__ . '/Instagram/Exception/InstagramException.php';

    use Instagram\Instagram;


    /* create Instagram class with set tokens
     */ 
    $code = (!empty($_GET['code']))? $_GET['code'] : '';
    
    $client_id = 'YOUR CLIENT ID';
    $client_secret = 'YOUR SECRET';
    $redirect = 'http://localhost:8081/InstagramPhpApi/demo_auth.php';

    $instagram = new Instagram($client_id, $client_secret, $redirect);
    
?>
<a href="<?= $instagram->getAuthUrl() ?>">Authorize</a>

<?php if ($code != '') {
    $instagram->getAccessToken($code);
    $user = $instagram->getUser();
    $res = $instagram->get('/media/popular');
    $res = $instagram->get('/users/self/media/liked');
    var_dump($res);
  }
?>
<?php if ($code != '') : ?>
<p>User: <?= $user->username ?></p>
<p><img src="<?= $user->profile_picture ?>" /></p>
<?php endif; ?>