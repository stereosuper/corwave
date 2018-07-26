<?php get_header();
	
	if( page_has_thumbnail() ):
		$thumbnailUrl = get_the_post_thumbnail_url();
	endif;

	$post_id = get_the_ID();
	$has_sidebar = false;
	$custom_sidebar_menu = null;

	if (isset(get_nav_menu_locations()['tree_structure'])) {
		$nav_id = get_nav_menu_locations()['tree_structure'];

		class TexasRanger extends Walker_Nav_Menu {
			private $last_depth = 0;
			private $is_in_current_path = false;
			private $post_id;
			private $current_menu_item_id;

			public $has_sidebar;

			function __construct ($post_id) {
				$this->post_id = intval($post_id);
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
				if( 0 == $depth )
					return;
				parent::start_lvl($output, $depth, $args);
			}

			// Don't end the top level
			function end_lvl(&$output, $depth=0, $args=array()) {
				if( 0 == $depth )
					return;
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
				if (  0 == $depth || !$this->is_in_current_path )
					return;
				parent::end_el($output, $item, $depth, $args);
			}

			// Only follow down one branch
			function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
				parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
			}
		}


		$texas_ranger_instance = new TexasRanger($post_id);
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

	$has_sidebar_class  = $has_sidebar ? ' class="has-sidebar js-has-sidebar"': '';
	$custom_anchors_sidebar  = $has_sidebar ? ' custom-anchors-sidebar': '';
?>
<div <?php echo $has_sidebar_class ?>>
	<header class='header-page'>
		<?php if( page_has_thumbnail() ): ?>
			<div class='header-bkg' style='background-image:url("<?php if( page_has_thumbnail() ){ echo $thumbnailUrl; } ?>")'></div>
		<?php endif; ?>
		<div class='header-page-container'>
			<div class='header-content'>
				<h1 class='container-small'><?php the_title(); ?></h1>
			</div>
		</div>
	</header>
    <div class='wrapper-collant <?php echo $custom_anchors_sidebar ?>'>
        <?php if ($has_sidebar) {
			echo $custom_sidebar_menu;
		} ?>
        <div class='container pb'>
            <div class='container-small'>
				<?php if (have_posts()): the_post(); ?>
					<div class='content-page'>
						
						<?php
						if ( function_exists('yoast_breadcrumb') ) {
							yoast_breadcrumb('
							<div class="breadcrumbs">','</div>
							');
						}
						?>
					<?php the_content(); ?>
					</div>
				<?php endif; ?>
            </div>
        </div>

        <?php get_template_part('partials/flexible-content'); ?>
    </div>
</div>

<?php get_footer(); ?>