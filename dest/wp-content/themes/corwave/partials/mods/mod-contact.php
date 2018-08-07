<?php
    $is_anchored = get_sub_field('is_anchored');
    $anchor_id = $is_anchored ? 'id="' . get_sub_field('id') . '-will-scroll"' : '';

    $modT_id = array($anchor_id);
    $modT_io = $is_anchored ? 'data-io="activeAnchor"' : '';
    $modT_js_selector = $is_anchored ? 'js-custom-anchor' : '';

    $modT_class = array('module-contact','clearfix','pb', $modT_js_selector);
    $modT_container_class = array('container');
    $modT_container_small_class = array('container-small');

    $mod  = sprintf(
                '<section %1$s %2$s class="%3$s" data-io="revealUp" data-io-single=""><div class="%4$s"><div class="%5$s">',
                join(' ', $modT_id),
                $modT_io,
                join(' ', $modT_class),
                join(' ', $modT_container_class),
                join(' ', $modT_container_small_class)
            );
            $mod .= get_field('contact_text', 'option');
        
        if($btn = get_field('contact_button', 'option')):
                $class = array('cta');

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
                            join(' ', $class),
                            $btn['target'] == '_blank' ? "noopener noreferrer" : "",
                            $btn['title']
                        );
                            
        endif;
    $mod .= '</div></div></section>';
        
    echo $mod;