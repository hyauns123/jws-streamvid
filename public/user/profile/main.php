<?php get_header();?>
    
   <div id="primary" class="content-area">
    <main id="main" class="site-main">
    <?php 
    
        do_action("streamvid/profile/header");
  
    
     ?>    

    <div class="profile-main">

        <?php do_action("streamvid/profile/main"); ?>

    </div>
     </main>   
    </div> 

<?php get_footer();?>