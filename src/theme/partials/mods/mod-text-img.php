<?php
    $modTI_class = array('mod-slider','slider','js-slider');
    $modTI_container_class = array('slides');
    $reversedLayout = get_sub_field('module_text-img-layout') === 'textRight' ? true : false ;
    $nb = count( get_sub_field('module_text-img-items') );

    if( $reversedLayout ):
        array_push($modTI_class, 'mod-layout-reversed');
    endif;

    /*if( get_sub_field('module_text-img-reversed') ):
        array_push($modTI_class, 'mod-mobile-reversed');
    endif;*/

    if( have_rows('module_text-img-items') ):
        
        $mod  = '<section class="' . join(' ', $modTI_class) . '">';

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

                    switch ( $txt['module_ti-item_text-color'] ) {
                        case 'black':
                            array_push($txt['classOuter'], 'color-black');
                            break;
                        default:
                            # code...
                            break;
                    }

                    // Template
                    $mod .= sprintf(
                                '<div class="%1$s" data-slide="%2$s">',
                                join(' ',$item_class),
                                $j
                            );

                            $styles = $txt['module_ti-item_text-background'] && $txt['module_ti-item_text-background'] !== 'inherit' ? 'style="background-color:'. $txt['module_ti-item_text-background'] .'"' : '';

                        // TXT side
                        $mod .= sprintf('<div class="%1$s">',
                            join(' ', $txt['classOuter'])
                        );
                            $mod .= "<span class='slide-layer-background' $styles ></span>";
                            $mod .= '<div class="' . join(' ', $txt['classInner']) . '">';
                            $mod .= sprintf(
                                '<div class="in-slide" data-io="ohTitle">%1$s</div>',
                                $txt['module_ti-item_text-content']
                            );
                            if( $txt['module_ti-item_text-link'] ):
                                $mod .= sprintf(
                                    '<a href="%1$s" target="%2$s" rel="%3$s" class="cta link js-cta"><span>%4$s</span>&nbsp;<span class="wrapper-icon"><svg class="icon icon-arrow"><use xlink:href="#icon-arrow"></svg></span></a>',
                                    $txt['module_ti-item_text-link']['url'],
                                    $txt['module_ti-item_text-link']['target'],
                                    $txt['module_ti-item_text-link']['target'] == '_blank' ? "noopener noreferrer" : "",
                                    $txt['module_ti-item_text-link']['title'] ? $txt['module_ti-item_text-link']['title'] : __('See more', 'concord')
                                );
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