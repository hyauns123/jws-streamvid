<div id="share-videos" class="mfp-hide">
<div class="form-head">
        
    <h5 class="title">
        <?php 
          
          echo esc_html__('Share','jws_streamvid'); 
            
        ?>
    </h5>

</div>
<p>
<label><?php echo esc_html__('Link','jws_streamvid'); ?></label>
<input type="text" value="<?php echo wp_get_shortlink(); ?>" />
</p>
<p>
<label><?php echo esc_html__('Embed','jws_streamvid'); ?></label>
<textarea>
<iframe width="560" height="315"  src="<?php echo get_the_permalink().'embed'; ?>" frameborder="0" allowfullscreen></iframe>
</textarea>
</p>
<?php

jws_share_buttons();

?>

</div>        
