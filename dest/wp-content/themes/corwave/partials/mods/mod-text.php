<?php
    $is_anchored = get_sub_field('is_anchored');
    $anchor_id = $is_anchored ? get_sub_field('id') : '';

    $modT_id = array("id='$anchor_id-will-scroll'");
    $modT_io = $is_anchored ? 'data-io="activeAnchor"' : '';
    $modT_js_selector = $is_anchored ? 'js-custom-anchor' : '';

    $modT_class = array('module-text', 'content-page', 'base-style', 'js-content-page', 'clearfix', 'pb', $modT_js_selector);
    $modT_container_class = array('container');
    $modT_container_small_class = array('container-small');

    $mod  = sprintf(
                '<section %1$s %2$s class="%3$s"><div class="%4$s" data-io="revealUp" data-io-single=""><div class="%5$s">',
                join(' ', $modT_id),
                $modT_io,
                join(' ', $modT_class),
                join(' ', $modT_container_class),
                join(' ', $modT_container_small_class)
            );
        $mod .= get_sub_field('module_text-content');
        
        if( have_rows('module_text-buttons') ):
            $mod .= '<div class="multiple-btn">';
            while ( have_rows('module_text-buttons') ) : the_row();

                $btn = get_sub_field('module_text-button-link');
                
                if($btn && get_sub_field('module_text-button-type')):
                    $btn['class'] = array('cta');
                    $mod .= sprintf(
                        '<a href="%1$s" target="%2$s" class="%3$s" rel="%4$s">
                        <span>
                        <svg class="ellypsis top"><use xlink:href="#icon-ellypsis-top"></use></svg>
                        <svg class="ellypsis bottom"><use xlink:href="#icon-ellypsis-bottom"></use></svg>
                        %5$s
                        </span>
                        <svg class="icon icon-arrow"><use xlink:href="#icon-arrow"></use></svg>
                        </a>',
                        $btn['url'],
                        $btn['target'],
                        join(' ', $btn['class']),
                        $btn['target'] == '_blank' ? "noopener noreferrer" : "",
                        $btn['title']
                    );
                elseif ($btn):
                    $btn['class'] = array('cta', 'cta-light');
                    $mod .= sprintf(
                        '<a href="%1$s" target="%2$s" class="%3$s" rel="%4$s">
                        <span>
                        %5$s
                        </span>
                        <svg class="icon icon-arrow"><use xlink:href="#icon-arrow"></use></svg>
                        </a>',
                        $btn['url'],
                        $btn['target'],
                        join(' ', $btn['class']),
                        $btn['target'] == '_blank' ? "noopener noreferrer" : "",
                        $btn['title']
                    );
                endif;

                            
            endwhile;
            $mod .= '</div></div>';
        endif;
    $mod .= '</section>';
    echo $mod;