    <?php

        require_once('ig_basic_display_api.php');

        $params = array(
            'get_code' => isset($_GET['code'])? $_GET['code'] : '',
            'access_token' => $accessToken
        );


        // failed to load server here 
        $ig = new ig_basic_display_api( $params);

        ?>

    <h1>Spark: IG Basic Display API</h1>
    <hr/>
    <?php if($ig->hasUserAccessToken): ?>
        <h4>IG User Info</h4>
        
        <?php $user = $ig->getUser(); ?>
        
        <pre>
            <?php print_r($user); ?>
        </pre>

    <h1>Username: <?php echo $user['username']; ?><h1>
    <h1>IG ID: <?php echo $user['id']; ?><h1>
    <h1>Media Count: <?php echo $user['media_count']; ?><h1>
    <h1>Follower Count: <?php echo $user['followers_count']; ?><h1>
    <h1>Account Type: <?php echo $user['account_type']; ?><h1>


        <h6>Access Token </h6>
        <?php echo $ig->getUserAccessToken(); ?>
     <?php else: ?>
        <a href="<?php echo $ig->authorizationUrl; ?>"> Authorize Here
        </a>
    <?php endif; ?>
    