    <?php

        require_once('ig_basic_display_api.php');

        // $accessToken = 'IGQVJVZA082YVZA1MXNfMlFpUGtfN251Vk1zQ3BWVmtuOXNndkFPWnhZAZAlpILVBzU01xWV9mN2ZAzNFVYWGhzYkNiVnczQW1OU1lMQzZAzeHNIbFN2SmtiSDh3SU9MZAmxid3VpY2IxMnZAR
        // ';

        //check for code in the URL if one exist pass it along if not set to empty
        $params = array(
            'get_code' => isset($_GET['code'])? $_GET['code'] : '',
            'access_token' => $accessToken
        );


        // instantiate ig class with params
        $ig = new ig_basic_display_api( $params);

        ?>

    <h1>Spark: IG Basic Display API</h1>
    <hr/>
    <!-- if user has token - display content + show token -->
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
        <h6>Expires In:</h6>
        <?php echo ceil($ig->getUserAccessTokenExpires()) ?> days.

    <!-- else show authorization URL -->
     <?php else: ?>
        <a href="<?php echo $ig->authorizationUrl; ?>"> Authorize Here
        </a>
    <?php endif; ?>
    