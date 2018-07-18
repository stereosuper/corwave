<?php
    $is_anchored = get_sub_field('is_anchored', get_the_ID());
    $anchor_id = $is_anchored ? get_sub_field('id', get_the_ID()) : '';

    $modT_id = "id='$anchor_id'";
    $modT_io = $is_anchored ? 'data-io="activeAnchor"' : '';
    $modT_js_selector = $is_anchored ? 'js-custom-anchor' : '';

    $modTI_class = array('mod-slider','slider','js-slider', $modT_js_selector);
    $autoscroll = get_sub_field('auto_scrolling', get_the_ID());
    $modTI_container_class = array('slides');
    $reversedLayout = get_sub_field('module_text-img-layout') === 'textRight' ? true : false ;
    $reversed_mobile = get_sub_field('module_text_img_reversed');
    $nb = count( get_sub_field('module_text-img-items') );

    if( $reversedLayout ):
        array_push($modTI_class, 'mod-layout-reversed');
    endif;

    if( $reversed_mobile ):
        array_push($modTI_class, 'mod-mobile-reversed');
    endif;

    if( have_rows('module_text-img-items') ):
        
        $autoscroll = $autoscroll ? 'data-auto-slide="true"' : '';
        $mod  = "<section $modT_id $modT_io class='" . join(" ", $modTI_class) . "' $autoscroll >";

            // Navigation slider
            if( $nb > 1 ):
                $mod .= '<div class="bullets">';
                    for ($i=0; $i < $nb; $i++) {
                        $bullet_class = $i == 0 ? 'bullet active' : 'bullet'; 
                        $mod .= '<span class="' . $bullet_class . '" data-slide="' . $i . '"></span>';
                    }
                $mod .= '</div>'; 
            endif;

            // Item(s)
            $mod .= '<div class="' . join(' ', $modTI_container_class) . '" data-nb="' . $nb . '">';
                $j = 0;
                while ( have_rows('module_text-img-items') ) : the_row();
                    
                    $item_class = array('slide','half');
                    
                    // Get and set datas
                    $img = get_sub_field('module_ti-item_img');
                    $img['class'] = array('img-side','pt','pb');

                    $txt = get_sub_field('module_ti-item_text');
                    $txt['classOuter'] = array('txt-side');
                    $txt['classInner'] = array('inner-txt-side', 'pt', 'pb');

                    // Update classes
                    if( $j == 0 ):
                        array_push($item_class, 'active');
                    endif;

                    if( $reversedLayout ):
                        array_push($item_class, 'reversed');
                    endif;

                    // COMBAK: Check if client wanna permanently remove text color choice on slider
                    // switch ( $txt['module_ti-item_text-color'] ) {
                    //     case 'black':
                    //         array_push($txt['classOuter'], 'color-black');
                    //         break;
                    //     default:
                    //         # code...
                    //         break;
                    // }

                    // Template
                    $mod .= sprintf(
                                '<div class="%1$s" data-slide="%2$s">',
                                join(' ',$item_class),
                                $j
                            );

                            // COMBAK: Check if client wanna permanently remove background color choice on slider
                            // $styles = $txt['module_ti-item_text-background'] && $txt['module_ti-item_text-background'] !== 'inherit' ? 'style="background-color:'. $txt['module_ti-item_text-background'] .'"' : '';

                        // TXT side
                        $mod .= sprintf('<div class="%1$s">',
                            join(' ', $txt['classOuter'])
                        );
                            $mod .= "<span class='slide-layer-background' ></span>";
                            $mod .= '<div class="' . join(' ', $txt['classInner']) . '">';
                            $mod .= sprintf(
                                '<div class="in-slide">%1$s</div>',
                                $txt['module_ti-item_text-content']
                            );
                            if($txt['links'] && sizeof($txt['links'])):
                                foreach ($txt['links'] as $link) :
                                    $link = $link['link'];
                                
                                    $mod .= sprintf(
                                        '<a href="%1$s" target="%2$s" rel="%3$s" class="cta cta-light link js-cta"><span>%4$s</span><svg class="icon icon-arrow"><use xlink:href="#icon-arrow"></use></svg></a>',
                                        $link['url'],
                                        $link['target'],
                                        $link['target'] == '_blank' ? "noopener noreferrer" : "",
                                        $link['title'] ? $link['title'] : __('See more', 'concord')
                                    );
                                endforeach;
                            endif;
                            $mod .= '</div>';
                        $mod .= '</div>';

                        // IMG side
                        $mod .= sprintf(
                                    '<div class="%1$s" style="background-image:url(%2$s)"></div>',
                                    join(' ', $img['class']),
                                    $img['module_ti-item_img-source']['sizes']['large']
                                );

                    $mod .= '</div>';                

                    $j++;
                endwhile;
            $mod .= '</div>';

        $mod .= '</section>';
        echo $mod;

    endif;