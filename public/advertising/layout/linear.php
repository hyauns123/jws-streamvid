<?php
header('Content-Type: application/xml; charset=utf-8');

extract( $args );

?>
<VAST version="3.0">
    <?php printf( '<Ad id="%s">', $ads_id ); ?>
        <InLine>
            <AdSystem> <?php echo $ads_system; ?> </AdSystem>
            <AdTitle> <?php echo $ads_title; ?> </AdTitle>

            <?php if( $ads_description ): ?>
            <Description> <?php echo $ads_description; ?> </Description>
            <?php endif?>
            
            <Creatives>
                <Creative>
                    <?php printf(
                        '<Linear%s>',
                        $ads_skippable ? ' skipoffset="'.esc_attr( $ads_skippable ).'"' : ''
                    );?>

                        <?php if( $ads_duration ): ?>

                        <Duration> <?php echo $ads_duration; ?> </Duration>

                        <?php endif;?>

                        <?php if( $ads_target_url ): ?>
                            <VideoClicks>
                                <ClickThrough>
                                    <![CDATA[ <?php echo $ads_target_url; ?> ]]>
                                </ClickThrough>
                            </VideoClicks>
                        <?php endif;?>
                        
                        <?php
                         if( ! function_exists( 'wp_read_video_metadata' ) ){
                            require_once( ABSPATH . 'wp-admin/includes/media.php' );
                        }  
                          $metadata = wp_read_video_metadata( get_attached_file( $ads_video ) );
                   
                         ?>

                        <?php if( $ads_video ): ?>
                            <MediaFiles>
                                            
                                <?php printf(
                                    '<MediaFile id="%s" delivery="progressive" type="%s" width="%s" height="%s" bitrate="%s" scalable="true" maintainAspectRatio="true">',
                                    esc_attr( $ads_video ),
                                    esc_attr( get_post_mime_type( $ads_video ) ),
                                    esc_attr( $metadata['width'] ),
                                    esc_attr( $metadata['height'] ),
                                    esc_attr( '720' )
                                );?>
                                    <![CDATA[ <?php echo wp_get_attachment_url( $ads_video ); ?> ]]>
                                </MediaFile>
                         
                            </MediaFiles>
                        <?php endif;?>
                    </Linear>
                </Creative>
            </Creatives>
        </InLine>
    </Ad>
</VAST>