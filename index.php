    <?php

        require_once('ig_basic_display_api.php');

        // $accessToken = 'IGQVJVZA082YVZA1MXNfMlFpUGtfN251Vk1zQ3BWVmtuOXNndkFPWnhZAZAlpILVBzU01xWV9mN2ZAzNFVYWGhzYkNiVnczQW1OU1lMQzZAzeHNIbFN2SmtiSDh3SU9MZAmxid3VpY2IxMnZAR
        // ';

        //check for code in the URL if one exist pass it along if not set to empty
        $params = array(
            'get_code' => isset($_GET['code'])? $_GET['code'] : '',
            'access_token' => $accessToken,
            'user_id' => '5269063709882686' // save userid (req) 4 usr Posts
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

    </hr>
    <?php $usersMedia = $ig->getUserMedia();?>
    <h3> Users Media Page 1 (<?php echo count($usersMedia['data']); ?>)</h3>
    <h4> Raw Data <h4>
        <!-- print response -->
        <textarea style="width:100%;height:400px;"><?php print_r($usersMedia); ?></textarea>
        
        <h4> Posts </h4>
        <!-- create list of all posts -->
        <ul style="list-style: none;margin:0px;padding:0px;">
        <?php foreach( $usersMedia['data'] as $post) : ?>
            <li string="margin-bottom:20px; border:3px solid #333">
                <div>
                    <!-- display image based on type of media -->
                <?php if('IMAGE' == $post['media_type'] || 'CAROUSEL_ALBUM' == $post['media_url']) :  ?>
                    <img style="height:320px" src="<?php echo $post['media_url']; ?>" />
                <?php else: ?>
                    <video height="240" width="320" controls>
                        <source src="<?php echo $post['media_url']; ?>">
                </video>
                 <?php endif; ?>
        </div>
        <!-- display other data recieved for each post -->
        <div>
            <b> Caption: <?php echo $post['caption']; ?> </b>
        </div>
        <div>
            <b> Id: <?php echo $post['id']; ?> </b>
        </div>
        <div>
            <b> Media Type: <?php echo $post['media_type']; ?> </b>
        </div>
        <div>
            <b> Media URL: <?php echo $post['media_url']; ?> </b>
        </div>
        <?php endforeach; ?>
        </ul> 

        <!-- display page 2 -->
        <?php $usersMediaNext = $ig->getPaging( $usersMedia['paging']['next'] );?>
    <h3> Users Media Page 2 (<?php echo count($usersMediaNext['data']); ?>)</h3>
    <h4> Raw Data <h4>
        <!-- print response -->
        <textarea style="width:100%;height:400px;"><?php print_r($usersMediaNext); ?></textarea>
        
        <h4> Posts </h4>
         <!-- create list of all posts -->
         <ul style="list-style: none;margin:0px;padding:0px;">
         <!-- loop over media next data for page 2 content  -->
        <?php foreach( $usersMediaNext['data'] as $post) : ?>
            <li string="margin-bottom:20px; border:3px solid #333">
                <div>
                    <!-- display image based on type of media -->
                <?php if('IMAGE' == $post['media_type'] || 'CAROUSEL_ALBUM' == $post['media_url']) :  ?>
                    <img style="height:320px" src="<?php echo $post['media_url']; ?>" />
                <?php else: ?>
                    <video height="240" width="320" controls>
                        <source src="<?php echo $post['media_url']; ?>">
                </video>
                 <?php endif; ?>
        </div>
        <!-- display other data recieved for each post -->
        <div>
            <b> Caption: <?php echo $post['caption']; ?> </b>
        </div>
        <div>
            <b> Id: <?php echo $post['id']; ?> </b>
        </div>
        <div>
            <b> Media Type: <?php echo $post['media_type']; ?> </b>
        </div>
        <div>
            <b> Media URL: <?php echo $post['media_url']; ?> </b>
        </div>
        <?php endforeach; ?>
        </ul> 

    </hr>


        <h6>Access Token </h6>
        <?php echo $ig->getUserAccessToken(); ?>
        <h6>Expires In:</h6>
        <?php echo ceil($ig->getUserAccessTokenExpires()) ?> days.

    <!-- else show authorization URL -->
     <?php else: ?>
        <a href="<?php echo $ig->authorizationUrl; ?>"> Authorize Here
        </a>
    <?php endif; ?>
    