<?php 

function create_sidebar($template_type) {
    $is_page_template = $template_type === 'page_template';
    $is_product_template = $template_type === 'product_template';
    $template_state = array(
        'is_page_template' => $is_page_template,
        'is_product_template' => $is_product_template,
    );

    $post_id = get_the_ID();
	$has_sidebar = false;
	$custom_sidebar_menu = null;

	if (isset(get_nav_menu_locations()['tree_structure'])) {
		$nav_id = get_nav_menu_locations()['tree_structure'];

		class TexasRanger extends Walker_Nav_Menu {
			private $last_depth = 0;
			private $is_in_current_path = false;
			private $post_id;
			private $template_state;
            private $current_menu_item_id;

			public $has_sidebar;

			function __construct ($post_id, $template_state) {
				$this->post_id = intval($post_id);
				$this->template_state = $template_state;
				$this->has_sidebar = false;
				$this->current_menu_item_id = -1;
			}

			// Get the root ancestor of the current element in order to follow down this only branch
			private function ancestorOfCurrent($item, $depth) {
                if ($this->last_depth >= $depth) {
                    $this->is_in_current_path = false;
				}
				if (!$this->is_in_current_path && isset($item->classes)) {
					$current_element_markers = array( 'current-menu-item', 'current-menu-parent', 'current-menu-ancestor' );
					$found_classes = array_intersect( $current_element_markers, $item->classes );
					$ancestor_of_current = !empty($found_classes);
					if ($ancestor_of_current) {
						$this->has_sidebar = true;
						$this->last_depth = $depth;
                        $this->is_in_current_path = $ancestor_of_current;
                    }
				}
			}

			// Set current menu id
			private function set_current_id($id) {
				$this->current_menu_item_id = $id;
			}

			// Don't start the top level
			function start_lvl(&$output, $depth=0, $args=array()) {
				if( 0 == $depth ) {
					return;
				}

				if ($this->template_state['is_product_template']) {
					return;
				}
				parent::start_lvl($output, $depth, $args);
			}

			// Don't end the top level
			function end_lvl(&$output, $depth=0, $args=array()) {
				if( 0 == $depth ) {
					return;
				}

				if ($this->template_state['is_product_template']) {
					return;
				}
				parent::end_lvl($output, $depth, $args);
			}
			/*
			 * Don't print top-level elements
			 * Don't print third level elements if the don't correspond to the current page
			 */
			function start_el(&$output, $item, $depth=0, $args=array(), $id = 0) {
                $this->ancestorOfCurrent($item, $depth);
				if (  0 == $depth || !$this->is_in_current_path )
                return;
                
				if ($this->post_id === intval($item->object_id)) {
                    $this->set_current_id(intval($item->ID));
				}
                
                if ($depth === 2 && $this->current_menu_item_id !== intval($item->menu_item_parent)) {
                    return;
                }
                
                // NOTE: Product template tests
                if ($this->template_state['is_product_template'] &&
                    $this->current_menu_item_id !== intval($item->menu_item_parent)) {
                    return;
                }
				
				if ($is_anchor = strpos($item->url, '#') !== false) {
					$classLink = 'class="scroll-to"';
					$classLi = 'class="js-anchor-link"';
					$output .= "<li $classLi>";
					$output .= '<a';
					$output .= " href='$item->url' ";
					$output .= " title='".htmlspecialchars($item->title, ENT_QUOTES)."' ";
					$output .= " target='$item->target' ";
					$output .= " $classLink ";
					$output .= $item->target === '_blank' ? ' rel="noopener noreferrer" ' : '';
					$output .= '>';
					$output .= $item->title;
					$output .= "</a>";
					$output .= "</li>";
				} else {
					parent::start_el($output, $item, $depth, $args);
				}

			}

			function end_el(&$output, $item, $depth=0, $args=array()) {
				if (  0 == $depth || !$this->is_in_current_path ) {
					return;
				}

				parent::end_el($output, $item, $depth, $args);
			}

			// Only follow down one branch
			function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
				parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
			}
		}


		$texas_ranger_instance = new TexasRanger($post_id, $template_state);
		$custom_sidebar_menu = wp_nav_menu( array( 
			'container'   => 'nav',
			'container_class' => 'anchors-sidebar js-anchors-sidebar',
			'menu' => $nav_id,
			'menu_class' => 'anchors-list',
			'depth' => 0,
			'walker' => $texas_ranger_instance,
			'echo' => false,
		));

		$has_sidebar = $texas_ranger_instance->has_sidebar;
	}

	$has_sidebar_class  = $has_sidebar ? ' has-sidebar js-has-sidebar': '';
    $custom_anchors_sidebar  = $has_sidebar ? ' custom-anchors-sidebar': '';
    
    $output_array = array();
    $output_array['has_sidebar'] = $has_sidebar;
    $output_array['has_sidebar_class'] = $has_sidebar_class;
    $output_array['custom_anchors_sidebar'] = $custom_anchors_sidebar;
    $output_array['custom_sidebar_menu'] = $custom_sidebar_menu;
    
    return $output_array;
};

?>