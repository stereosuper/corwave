<?php

define( 'CORWAVE_VERSION', 1.0 );


/*-----------------------------------------------------------------------------------*/
/* General
/*-----------------------------------------------------------------------------------*/
// Plugins updates
add_filter( 'auto_update_plugin', '__return_true' );

// Theme support
add_theme_support( 'html5', array(
    'comment-list',
    'comment-form',
    'search-form',
    'gallery',
    'caption',
    'widgets'
) );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );

// Admin bar
show_admin_bar(false);

// Disable Tags
function corwave_unregister_tags(){
    unregister_taxonomy_for_object_type('post_tag', 'post');
}
add_action( 'init', 'corwave_unregister_tags' );

/*-----------------------------------------------------------------------------------*/
/* Clean WordPress head and remove some stuff for security
/*-----------------------------------------------------------------------------------*/
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
add_filter( 'emoji_svg_url', '__return_false' );

// remove api rest links
remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

// remove comment author class
function corwave_remove_comment_author_class( $classes ){
	foreach( $classes as $key => $class ){
		if(strstr($class, 'comment-author-')) unset( $classes[$key] );
	}
	return $classes;
}
add_filter( 'comment_class' , 'corwave_remove_comment_author_class' );

// remove login errors
function corwave_login_errors(){
    return 'Something is wrong!';
}
add_filter( 'login_errors', 'corwave_login_errors' );


/*-----------------------------------------------------------------------------------*/
/* Admin
/*-----------------------------------------------------------------------------------*/
// Remove some useless admin stuff
function corwave_remove_submenus() {
  $page = remove_submenu_page( 'themes.php', 'themes.php' );
  remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'corwave_remove_submenus', 999 );
function corwave_remove_top_menus( $wp_admin_bar ){
    $wp_admin_bar->remove_node( 'wp-logo' );
}
add_action( 'admin_bar_menu', 'corwave_remove_top_menus', 999 );

// Enlever le lien par défaut autour des images
function corwave_imagelink_setup(){
	if(get_option( 'image_default_link_type' ) !== 'none') update_option('image_default_link_type', 'none');
}
add_action( 'admin_init', 'corwave_imagelink_setup' );

// Add wrapper around iframe
function corwave_iframe_add_wrapper( $return, $data, $url ){
    return "<div class='wrapper-video'>{$return}</div>";
}
add_filter( 'oembed_dataparse', 'corwave_iframe_add_wrapper', 10, 3 );

// Enlever les <p> autour des images
function corwave_remove_p_around_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
add_filter( 'the_content', 'corwave_remove_p_around_images' );

// Allow svg in media library
function corwave_mime_types($mimes){
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'corwave_mime_types' );

// Custom posts in the dashboard
function corwave_right_now_custom_post() {
    $post_types = get_post_types(array( '_builtin' => false ) , 'objects' , 'and');
    foreach($post_types as $post_type){
        $cpt_name = $post_type->name;
        if($cpt_name !== 'acf-field-group' && $cpt_name !== 'acf-field'){
            $num_posts = wp_count_posts($post_type->name);
            $num = number_format_i18n($num_posts->publish);
            $text = _n($post_type->labels->name, $post_type->labels->name , intval($num_posts->publish));
            echo '<li class="'. $cpt_name .'-count"><tr><a class="'.$cpt_name.'" href="edit.php?post_type='.$cpt_name.'"><td></td>' . $num . ' <td>' . $text . '</td></a></tr></li>';
        }
    }
}
add_action( 'dashboard_glance_items', 'corwave_right_now_custom_post' );

// Add new styles to wysiwyg
function corwave_button( $buttons ){
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}
add_filter( 'mce_buttons_2', 'corwave_button' );
function corwave_init_editor_styles(){
    add_editor_style();
}
add_action( 'after_setup_theme', 'corwave_init_editor_styles' );

function corwave_mce_before_init( $styles ){
    $opts = 'span[*],svg[*],use[*],path[*]';
    
	$styles['valid_elements'] = '*[*]';
	$styles['extended_valid_elements'] = $opts;
    $styles['invalid_elements'] = '';
    
    $style_formats = array(
        array(
            'title' => 'Image full-width',
            'selector' => 'img',
            'classes' => 'full-width'
        ),
    );

    $styles['style_formats'] = json_encode( $style_formats );
    // Remove h1 and code
    $styles['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6';
    // Let only the colors you want
    $styles['textcolor_map'] = '[' . "'000000', 'Noir', '565656', 'Texte'" . ']';
    return $styles;
}

function corwave_custom_tinyMCE_wysiwyg() {
    add_filter( 'acf/fields/wysiwyg/toolbars' , 'corwave_acf_toolbars'  );
    add_filter( 'mce_external_plugins', 'corwave_add_buttons' );
    add_filter( 'mce_buttons', 'corwave_register_buttons' );
    add_filter( 'tiny_mce_before_init', 'corwave_mce_before_init' );
}
add_action('init', 'corwave_custom_tinyMCE_wysiwyg');

function createID($value) {
    return "custom-anchor-$value";
}

function everything_in_tags($string, $tagname) {
    $pattern = "#\b[^>]*>(.*?)</$tagname\b[^>]*>#s";
    preg_match($pattern, $string, $matches);
    return $matches[1];
}

// NOTE: Filtering the content to retrieve custom anchors
function custom_anchor_sidebar($content) {
    $class = 'class="custom-anchors-in-sidebar"';
    $lastPos = 0;

    while (($position = strpos($content, $class, $lastPos)) !== false) {
        $lastPos   = $position + 1;
        $attr = ' class="js-custom-anchor"';
        $content = substr_replace($content, $attr, $position, strlen($class));
    }

    return $content;
}
add_filter('the_content', 'custom_anchor_sidebar');
add_filter('acf/load_value/type=wysiwyg', 'custom_anchor_sidebar');

function corwave_add_buttons( $plugin_array ) {
    $plugin_array['corwave'] = get_template_directory_uri() . '/corwave-editor-buttons/corwave-plugins.js';
    return $plugin_array;
}
function corwave_register_buttons( $buttons ) {
    array_push( $buttons, 'bckq', 'cta' );

    $remove_buttons = array(
        'blockquote'
    );

    foreach ( $buttons as $button_key => $button_value ) {
        if ( in_array( $button_value, $remove_buttons ) ) {
            unset( $buttons[ $button_key ] );
        }
    }

    return $buttons;
}
function corwave_acf_toolbars( $toolbars )
{
    if( $toolbars['Basic' ] ){
        array_push( $toolbars['Basic' ][1], 'formatselect' );
    }
	return $toolbars;
}

// Option page
function corwave_menu_order( $menu_ord ){  
    if( !$menu_ord ) return true;  
    
    $menu = 'acf-options';
    $menu_ord = array_diff($menu_ord, array( $menu ));
    array_splice( $menu_ord, 1, 0, array( $menu ) );
    return $menu_ord;
}  
add_filter( 'custom_menu_order', 'corwave_menu_order' );
add_filter( 'menu_order', 'corwave_menu_order' );

if( function_exists('acf_add_options_page') ) {
	acf_add_options_page();
}

function my_acf_admin_head() {
	?>
	<style type="text/css">
        /* All inputs */
        .acf-field .acf-label label{
            text-transform: uppercase;
        }

        /* Flexible Content */
		.acf-flexible-content .layout{
            border: 2px solid #457B9D;
            border-radius: 5px;
        }
        .acf-flexible-content .layout:nth-child(2n){
            border-color: #1D3557;
        }
        .acf-flexible-content .layout .acf-fc-layout-handle{
            background-color: #457B9D;
            color: #fff;
        }
        .acf-flexible-content .layout:nth-child(2n) .acf-fc-layout-handle{
            background-color: #1D3557;
        }
        .acf-flexible-content .layout .acf-fc-layout-order,
        .acf-flexible-content .layout .acf-fc-layout-controlls .acf-icon.-collapse{
            background-color: #fff;
        }

        /* Repeater */
        .acf-repeater  .acf-row .acf-row-handle.order{
            color: #000;
            background-color: #F1FAEE;
        }
        .acf-repeater .acf-row:nth-child(2n) .acf-row-handle.order{
            background-color: #A8DADC;
        }

	</style>
	<?php
}
add_action('acf/input/admin_head', 'my_acf_admin_head');

add_filter('acf/settings/default_language', 'my_acf_settings_default_language');
function my_acf_settings_default_language( $language ) {
    return 'en';
}

add_filter('acf/settings/current_language', 'my_acf_settings_current_language');
function my_acf_settings_current_language( $language ) {
    $current_language = getCurrentBlogLanguage();
    if ($current_language === 'en') {
        return 'en';
    } elseif ($current_language === 'fr') {
        return 'fr';
    }
}

/*-----------------------------------------------------------------------------------*/
/* Menus
/*-----------------------------------------------------------------------------------*/
register_nav_menus( array(
    'primary' => 'Primary Menu',
    'tree_structure' => __('Tree structure', 'corwave')
));

// Cleanup WP Menu html
function corwave_css_attributes_filter($var){
    return is_array($var) ? array_intersect($var, array('current-menu-item', 'current_page_parent')) : '';
}
add_filter( 'nav_menu_css_class', 'corwave_css_attributes_filter' );

class WPSE_78121_Sublevel_Walker extends Walker_Nav_Menu
{
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $classes = $depth === 0 ? 'sub-menu-wrap is-parent' : 'sub-menu-wrap is-child';
        $classes2 = $depth === 0 ? 'sub-menu is-parent' : 'sub-menu is-child';
        $output .= "\n$indent<div class='$classes'><ul class='$classes2'>\n";
    }

    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul></div>\n";
    }
    
    function start_el(&$output, $item, $depth=0, $args=array(), $id = 0) {
        $object = $item->object;
    	$type = $item->type;
    	$title = $item->title;
    	$description = $item->description;
        $permalink = $item->url;
        $classes = array_intersect($item->classes, array('current-menu-item', 'current_page_parent', 'menu-item-has-children'));

        if($depth === 0) array_push($classes, 'is-parent');


        

        $output .= "<li class='" .  implode(" ", $classes) . "'>";

        if( $permalink && $permalink != '#' ) {
            $output .= '<a href="' . $permalink . '">';
        } else {
            $output .= '<span>';
        }

        $output .= '<span>';
        $output .= $title;
        $output .= '</span>';

        if(in_array('menu-item-has-children', $classes)){
            $output .= '<svg class="icon icon-arrow-down"><use xlink:href="#icon-arrow-down"></use></svg>';
        }

        if( $permalink && $permalink != '#' ) {
            $output .= '</a>';
        } else {
            $output .= '</span>';
        }
    
    }
}

/*-----------------------------------------------------------------------------------*/
/* Body
/*-----------------------------------------------------------------------------------*/
function corwave_body_class( $classes ) {

    if ( !has_post_thumbnail( get_queried_object_id() ) || get_field( 'page_thumbnail-hide', get_queried_object_id() ) ) {
        $classes[] = 'no-thumbnail';
    }

    if ( has_post_thumbnail( get_queried_object_id() ) && !get_field( 'page_thumbnail-hide', get_queried_object_id() ) ) {
        $classes[] = 'has-thumbnail';
    }

    return $classes;
}
add_filter( 'body_class', 'corwave_body_class' );


/*-----------------------------------------------------------------------------------*/
/* Sidebar & Widgets
/*-----------------------------------------------------------------------------------*/
function corwave_register_sidebars(){
	register_sidebar( array(
		'id' => 'sidebar',
		'name' => 'Sidebar',
		'description' => 'Take it on the side...',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
		'empty_title'=> ''
	) );
}
add_action( 'widgets_init', 'corwave_register_sidebars' );

// Deregister default widgets
function corwave_unregister_default_widgets(){
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Nav_Menu_Widget');
}
add_action( 'widgets_init', 'corwave_unregister_default_widgets' );


/*-----------------------------------------------------------------------------------*/
/* Post types
/*-----------------------------------------------------------------------------------*/
function corwave_post_type(){
    register_post_type( 'product', array(
        'label' => 'Products',
        'singular_label' => 'Product',
        'public' => true,
        'menu_icon' => 'dashicons-heart',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
    ));
}
add_action( 'init', 'corwave_post_type' );

// function corwave_taxonomies(){
//     register_taxonomy( 'resource_cat', array('resource'), array(
//         'label' => 'Categories',
//         'singular_label' => 'Category',
//         'hierarchical' => true,
//         'show_admin_column' => true
//     ) );
// }
// add_action( 'init', 'corwave_taxonomies' );


/*-----------------------------------------------------------------------------------*/
/* Enqueue Styles and Scripts
/*-----------------------------------------------------------------------------------*/
function corwave_scripts(){
    // header
	wp_enqueue_style( 'corwave-style', get_template_directory_uri() . '/css/main.css', array(), CORWAVE_VERSION );

	// footer
	wp_deregister_script('jquery');
	wp_enqueue_script( 'corwave-scripts', get_template_directory_uri() . '/js/main.js', array(), CORWAVE_VERSION, true );

    wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_enqueue_scripts', 'corwave_scripts' );


/*-----------------------------------------------------------------------------------*/
/* Medias
/*-----------------------------------------------------------------------------------*/

// NOTE: Allow object-fit fallback
remove_shortcode('gallery', 'gallery_shortcode');
add_shortcode('gallery', 'custom_gallery_shortcode');
function custom_gallery_shortcode( $attr ) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) ) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}

	/**
	 * Filters the default gallery shortcode output.
	 *
	 * If the filtered output isn't empty, it will be used instead of generating
	 * the default gallery template.
	 *
	 * @since 2.5.0
	 * @since 4.2.0 The `$instance` parameter was added.
	 *
	 * @see gallery_shortcode()
	 *
	 * @param string $output   The gallery output. Default empty.
	 * @param array  $attr     Attributes of the gallery shortcode.
	 * @param int    $instance Unique numeric ID of this gallery shortcode instance.
	 */
	$output = apply_filters( 'post_gallery', '', $attr, $instance );
	if ( $output != '' ) {
		return $output;
	}

	$html5 = current_theme_supports( 'html5', 'gallery' );
	$atts = shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'itemtag'    => $html5 ? 'figure'     : 'dl',
		'icontag'    => $html5 ? 'div'        : 'dt',
		'captiontag' => $html5 ? 'figcaption' : 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
		'link'       => ''
	), $attr, 'gallery' );

	$id = intval( $atts['id'] );

	if ( ! empty( $atts['include'] ) ) {
		$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( ! empty( $atts['exclude'] ) ) {
		$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	} else {
		$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	}

	if ( empty( $attachments ) ) {
		return '';
	}

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
		}
		return $output;
	}

	$itemtag = tag_escape( $atts['itemtag'] );
	$captiontag = tag_escape( $atts['captiontag'] );
	$icontag = tag_escape( $atts['icontag'] );
	$valid_tags = wp_kses_allowed_html( 'post' );
	if ( ! isset( $valid_tags[ $itemtag ] ) ) {
		$itemtag = 'dl';
	}
	if ( ! isset( $valid_tags[ $captiontag ] ) ) {
		$captiontag = 'dd';
	}
	if ( ! isset( $valid_tags[ $icontag ] ) ) {
		$icontag = 'dt';
	}

	$columns = intval( $atts['columns'] );
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = '';

	/**
	 * Filters whether to print default gallery styles.
	 *
	 * @since 3.1.0
	 *
	 * @param bool $print Whether to print default gallery styles.
	 *                    Defaults to false if the theme supports HTML5 galleries.
	 *                    Otherwise, defaults to true.
	 */
	if ( apply_filters( 'use_default_gallery_style', ! $html5 ) ) {
		$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
			/* see gallery_shortcode() in wp-includes/media.php */
		</style>\n\t\t";
	}

	$size_class = sanitize_html_class( $atts['size'] );
	$gallery_div = "<div id='$selector' class='gallery js-gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";

	/**
	 * Filters the default gallery shortcode CSS styles.
	 *
	 * @since 2.5.0
	 *
	 * @param string $gallery_style Default CSS styles and opening HTML div container
	 *                              for the gallery shortcode output.
	 */
	$output = apply_filters( 'gallery_style', $gallery_style . $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {

        $attr = ( trim( $attachment->post_excerpt ) ) ?
        array(
            'aria-describedby' => "$selector-$id",
            'class' => "object-fit"
        )
        : array(
            'class' => "object-fit"
        );

        $image_output = '<div class="gallery-image-wrapper">';
        if (isset($attr['aria-describedby'])) {
            $image_output .= lazyLoadImage($atts['size'], ['id' => $id, 'class' => $attr['class'], 'aria-describedby' => $attr['aria-describedby']]);
		} else {
            $image_output .= lazyLoadImage($atts['size'], ['id' => $id, 'class' => $attr['class']]);
        }
        $image_output .= '</div>';

		$image_meta  = wp_get_attachment_metadata( $id );

		$orientation = '';
		if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
			$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
		}
		$output .= "<{$itemtag} class='gallery-item'>";
		$output .= "
			<{$icontag} class='gallery-icon {$orientation} {$atts['size']}'>
				$image_output
			</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption' id='$selector-$id'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
		if ( ! $html5 && $columns > 0 && ++$i % $columns == 0 ) {
			$output .= '<br style="clear: both" />';
		}
	}

	if ( ! $html5 && $columns > 0 && $i % $columns !== 0 ) {
		$output .= "
			<br style='clear: both' />";
	}

	$output .= "
		</div>\n";

	return $output;
}

// NOTE: Overriding gallery's column number
function theme_gallery_defaults( $settings ) {
    $settings['galleryDefaults']['columns'] = 4;
    return $settings;
}
add_filter( 'media_view_settings', 'theme_gallery_defaults' );

// NOTE: LazyLoading function generating img with srcset & size like wp_get_attachment_image
function lazyLoadImage($size, $attr = array('class' => '')) {
    $id = intval(get_post_thumbnail_id());
    if (isset($attr['id'])) {
        $id = intval($attr['id']);
    }

    $image = '<img ';
    
    // Classes
    $image .= 'class="lazy-image ';
    if (isset($attr['class'])) {
        $image .= $attr['class'];
    }
    $image .= '"';

    // Src
    $image .= ' data-src="';
    $image .= wp_get_attachment_image_src($id, $size)[0];
    $image .= '"';

    // Srcset
    $image .= ' data-srcset="';
    $image .= wp_get_attachment_image_srcset($id, $size);
    $image .= '"';
    
    // Sizes
    $image .= ' sizes="';
    $image .= wp_get_attachment_image_sizes($id, $size);
    $image .= '"';

    // Alt
    $image .= ' alt="';
    $image .= get_post_meta($id, '_wp_attachment_image_alt', true);
    $image .= '"';

    // Aria-describedby
    if (isset($attr['aria-describedby'])) {
        $image .= ' aria-describedby="';
        $image .= $attr['aria-describedby'];
        $image .= '"';
    }
    
    $image .= '/>';
    
    return $image;
}

/*-----------------------------------------------------------------------------------*/
/* TGMPA
/*-----------------------------------------------------------------------------------*/
function corwave_register_required_plugins(){
	$plugins = array(
        array(
            'name'        => 'Advanced Custom Fields PRO',
            'slug'        => 'advanced-custom-fields-pro',
            'source'     => get_template_directory_uri() . '/plugins/advanced-custom-fields-pro.zip',
            'required'    => true,
            'force_activation' => false
        ),
        array(
            'name'        => 'SecuPress Free — Sécurité WordPress 1.3.3',
            'slug'        => 'secupress',
            'required'    => false,
            'force_activation' => false
        ),
        array(
            'name'        => 'EWWW Image Optimizer',
            'slug'        => 'ewww-image-optimizer',
            'required'    => false,
            'force_activation' => false
        ),
        array(
            'name'        => 'Clean Image Filenames',
            'slug'        => 'clean-image-filenames',
            'required'    => false,
            'force_activation' => false
        ),
        array(
            'name'        => 'Yoast SEO',
            'slug'        => 'wordpress-seo',
            'required'    => false,
            'force_activation' => false
        ),
        array(
            'name'        => 'MultilingualPress',
            'slug'        => 'multilingual-press',
            'required'    => false,
            'force_activation' => false
        ),
    );
    
	$config = array(
		'id'           => 'corwave',
		'default_path' => '', 
		'menu'         => 'tgmpa-install-plugins',
		'parent_slug'  => 'themes.php',
		'capability'   => 'edit_theme_options', 
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => false,
		'message'      => ''
    );
    
	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'corwave_register_required_plugins' );


/*-----------------------------------------------------------------------------------*/
/* Helpers
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'page_has_thumbnail' ) ) :
    function page_has_thumbnail(){
        $classes = get_body_class();
        if( in_array('no-thumbnail', $classes ) ){
            return false;
        }
        return true;
    }
endif;

function getCurrentBlogLanguage() {
    $current_language = mlp_get_current_blog_language();
    $language = 'en';
    if (strpos($current_language, 'en') !== false) {
        $language = 'en';
    } elseif (strpos($current_language, 'fr') !== false) {
        $language = 'fr';
    }
    return $language;
}


?>
