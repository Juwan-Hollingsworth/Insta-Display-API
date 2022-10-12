    <?php

        require_once('ig_basic_display_api.php');

        //check for code in the URL if one exist pass it along if not set to empty
        $params = array(
            'get_code' => isset($_GET['code'])? $_GET['code'] : ''
        );


        // instantiate ig class with params
        $ig = new ig_basic_display_api( $params);

        ?>

    <h1>Spark: IG Basic Display API</h1>
    <hr/>
    <!-- if user has token - display content + show token -->
    <?php if($ig->hasUserAccessToken): ?>
        <h4>IG Info</h4>
        <h6>Access Token </h6>
        <?php echo $ig->getUserAccessToken(); ?>
        <h6>Expires In:</h6>
        <?php echo ceil($ig->getUserAccessTokenExpires()) ?> days.

    <!-- else show authorization URL -->
     <?php else: ?>
        <a href="<?php echo $ig->authorizationUrl; ?>"> Authorize Here
        </a>
    <?php endif; ?>
    