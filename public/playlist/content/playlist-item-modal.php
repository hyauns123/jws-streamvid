<?php 

$args = wp_parse_args( $args, array(
    'post_id'   => 0,  
    'post_type' => 'videos',
    'playlist_type' => 'videos_playlist'    
) );

extract( $args );
$has_term = has_term( $term->term_id ,$playlist_type, $post_id );
?>
<li class="playlist-item">
    <?php 
    
      printf(
			'<button class="set-item-playlist %s" data-term-id="%s" data-post-id="%s" data-post-type="%s" data-playlist-type="%s"></button>',
            $has_term ? 'checked' : '',
		    $term->term_id,
            $post_id,
            $post_type,
            $playlist_type	
		);
    
    ?>
    <div class="playlist-inner">
        <div class="playlist-background">
            <div class="ratio_16x9">
            <?php 
                $image = jws_image_advanced(array('attach_id' => $thumbnail, 'thumb_size' => 'full'));
                echo !empty($image) ? $image : '';
            ?>
            </div>
        </div>
        <div class="playlist-content">
            <?php 
                
                echo "<h6><a href='$url'>$term->name</a></h6>";
                
            ?>
             <div class="playlist-meta">
                <?php 
                    $icon_class = $status == 'private' ? 'jws-icon-lock-key-fill' : 'jws-icon-globe-hemisphere-west-fill';
                    echo '<span><i class="'.$icon_class.'"></i>'.$status.'</span>';
                    printf( _n( '<span>%s video</span>', '<span>%s videos</span>', $count , 'jws_streamvid' ), number_format_i18n( $count ) );
                ?>
            </div>
        </div>
        
    </div>
</li>