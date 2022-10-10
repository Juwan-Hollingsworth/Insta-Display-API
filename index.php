    <?php

        require_once('ig_basic_display_api.php');

        $params = array(
            'get_code' => isset($_GET['code'])? $_GET['code'] : ''
        );


        // failed to load server here 
        $ig = new ig_basic_display_api( $params);

        ?>

    <h1>Spark: IG Basic Display API</h1>
    <hr/>
    <?php if($ig->hasUserAccessToken): ?>
        <h4>IG Info</h4>
        <?php echo $ig->getUserAccessToken(); ?>
     <?php else: ?>
        <a href="<?php echo $ig->authorizationUrl; ?>"> Authorize Here
        </a>
    <?php endif; ?>
    