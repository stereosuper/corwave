<?php 
/*
Theme Name: Corwave
Description: A beautiful WP theme for Corwave.
Author: Stéréosuper
Author URI: https://stereosuper.fr
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Version: 1.0
Text Domain: corwave
*/
?>
<!DOCTYPE html>

<html <?php language_attributes(); ?> class='no-js'>
	<head>
        <meta charset='utf-8'>
		<meta name='viewport' content='width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no'>
		<meta name='format-detection' content='telephone=no'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<!--[if IE]>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<![endif]-->

		<link rel='alternate' type='application/rss+xml' title='<?php echo get_bloginfo('sitename') ?> Feed' href='<?php echo get_bloginfo('rss2_url') ?>'>

		<?php wp_head(); ?>

		<link rel='apple-touch-icon' sizes='180x180' href='/apple-touch-icon.png'>
		<link rel='icon' type='image/png' sizes='32x32' href='/favicon-32x32.png'>
		<link rel='icon' type='image/png' sizes='16x16' href='/favicon-16x16.png'>
		<link rel='manifest' href='/site.webmanifest'>
		<link rel='mask-icon' href='/safari-pinned-tab.svg' color='#5bbad5'>
		<meta name='msapplication-TileColor' content='#ffffff'>
		<meta name='msapplication-TileImage' content='/mstile-150x150.png'>
		<meta name='theme-color' content='#ffffff'>

		<link href="https://fonts.googleapis.com/css?family=Lato:100i,300,300i,400,400i,700" rel="stylesheet">

		<script>document.getElementsByTagName('html')[0].className = 'js';</script>

        <!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-127215994-1"></script>
		<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-127215994-1');
		</script>

	</head>

	<body <?php body_class(); ?>>

		<header class='header js-header' role='banner'>
			<div class='container'>
				<div class='wrapper-logo'>
					<a href='<?php echo home_url('/'); ?>' title='<?php bloginfo( 'name' ); ?>' class='logo' rel='home'>
					<svg width="89" height="49" fill="none" xmlns="http://www.w3.org/2000/svg"><path class='logo-path left' d="M3.873 29.63c-.743 0-1.362-.067-1.858-.191-.495-.124-.89-.315-1.193-.597a2.263 2.263 0 0 1-.63-1.07C.067 27.334 0 26.794 0 26.153v-3.176c0-.63.068-1.171.191-1.621.124-.45.338-.811.63-1.081.294-.27.699-.473 1.194-.597.496-.124 1.115-.18 1.858-.18H10.9v1.948H3.975c-.35 0-.63.022-.856.079a1.178 1.178 0 0 0-.53.27 1.196 1.196 0 0 0-.27.518 3.756 3.756 0 0 0-.078.777v2.972c0 .315.022.586.078.788a.964.964 0 0 0 .27.507c.125.124.305.214.53.27.225.045.518.079.856.079h6.913v1.937H3.873v-.012zM16.518 29.63c-.743 0-1.363-.067-1.858-.18-.495-.124-.89-.326-1.194-.597a2.263 2.263 0 0 1-.63-1.07c-.124-.438-.191-.979-.191-1.62v-1.081c0-.642.067-1.183.19-1.633.125-.44.339-.8.631-1.07.293-.27.699-.473 1.194-.597.495-.123 1.115-.18 1.858-.18h2.69c.755 0 1.374.068 1.87.18.495.124.89.327 1.193.597.293.282.507.642.63 1.081.125.45.192.991.192 1.633v1.07c0 .641-.067 1.182-.191 1.62-.124.44-.338.8-.63 1.07-.293.27-.699.473-1.194.597-.496.124-1.115.18-1.87.18h-2.69zm4.357-4.492c0-.338-.022-.608-.079-.833a1.026 1.026 0 0 0-.292-.507c-.147-.124-.35-.203-.597-.248a5.877 5.877 0 0 0-.98-.067h-2.094c-.394 0-.72.022-.968.067-.248.045-.45.124-.597.248a.883.883 0 0 0-.293.507c-.056.214-.079.495-.079.833v.946c0 .337.023.608.08.822.056.213.157.382.292.506.146.124.338.214.597.26.247.044.574.078.968.078h2.094c.394 0 .72-.023.98-.079.259-.045.462-.135.597-.259a.883.883 0 0 0 .292-.506c.057-.214.08-.485.08-.822v-.946zM25.064 29.63V24.97c0-.608.067-1.115.202-1.543.135-.428.36-.777.665-1.047.304-.27.687-.473 1.17-.597.474-.123 1.06-.191 1.735-.191h2.927v1.802h-2.871c-.304 0-.563.022-.777.078a1.084 1.084 0 0 0-.495.248 1.068 1.068 0 0 0-.27.484 3.15 3.15 0 0 0-.08.788v4.65h-2.206v-.01z"/><path class='logo-path right' d="M42.46 22.357l-3.108 6.542a1.82 1.82 0 0 1-.563.698c-.236.18-.53.27-.878.27-.372 0-.653-.09-.856-.27a2.008 2.008 0 0 1-.495-.698l-3.941-9.39h2.51l2.973 7.318 3.018-6.643c.146-.338.326-.575.518-.71.202-.135.484-.202.844-.202.372 0 .653.067.867.202.203.135.383.372.518.71l2.894 6.643 3.074-7.319H52.3c-.124.259-.293.63-.507 1.126-.214.495-.462 1.047-.732 1.655-.27.608-.552 1.25-.844 1.914-.293.665-.586 1.306-.856 1.914-.27.608-.518 1.16-.732 1.644l-.484 1.126a1.953 1.953 0 0 1-.518.698c-.214.18-.496.27-.822.27-.36 0-.653-.09-.878-.258a1.773 1.773 0 0 1-.552-.71l-2.916-6.53zM55.014 29.63c-.585 0-1.07-.045-1.441-.146-.383-.101-.676-.236-.9-.428a1.351 1.351 0 0 1-.462-.664 2.686 2.686 0 0 1-.136-.89v-.754c0-.338.045-.642.124-.9.08-.26.237-.485.44-.665.213-.18.495-.315.855-.417.36-.09.822-.146 1.374-.146h5.956v-.18c0-.518-.101-.856-.315-1.036-.214-.17-.563-.26-1.059-.26h-5.967V21.57h5.967c.642 0 1.194.056 1.644.18.45.124.822.304 1.104.54.293.237.495.552.63.923.135.372.203.811.203 1.329v1.96c0 .585-.056 1.069-.169 1.474-.113.394-.315.72-.597.969-.281.247-.664.427-1.126.529-.473.101-1.058.157-1.779.157h-4.346zm5.81-3.636h-5.709c-.258 0-.461.056-.608.168-.146.113-.225.327-.225.642v.552c0 .293.08.484.237.597.157.112.383.157.664.157h4.155c.259 0 .484-.022.664-.056a.977.977 0 0 0 .462-.214c.124-.101.202-.248.27-.44.056-.19.09-.427.09-.73v-.677zM72.117 29.101c-.169.225-.36.406-.585.552-.225.135-.53.203-.923.203-.417 0-.732-.068-.946-.203a2.253 2.253 0 0 1-.586-.552l-5.303-7.521h2.646l4.222 6.17 4.178-6.17h2.612L72.117 29.1zM80.213 26.421v.372c0 .248.023.45.056.597.045.146.113.27.214.349a.88.88 0 0 0 .417.169c.169.033.394.045.642.045h6.069v1.677h-6.058c-1.284 0-2.184-.247-2.736-.732-.54-.484-.822-1.26-.822-2.33V24.71c0-.586.056-1.081.169-1.475.112-.405.315-.72.597-.968.281-.248.653-.417 1.126-.53.461-.112 1.058-.157 1.767-.157h3.896c.574 0 1.058.056 1.43.157.383.113.687.26.912.45.225.192.383.429.473.699.09.27.135.563.135.878v2.646h-8.287v.011zm6.058-2.465c0-.27-.068-.45-.203-.53-.135-.09-.372-.135-.71-.135h-3.681c-.282 0-.507.012-.698.034a.86.86 0 0 0-.45.169.784.784 0 0 0-.248.383 2.673 2.673 0 0 0-.08.687v.427h6.058v-1.035h.012zM50.195 42.5c8.738 0 10.652-8.163 10.674-8.242l.056-.462c-.056.27-3.715 6.587-10.719 6.587-7.104 0-9.356-6.418-9.446-6.688l-2.016.676c.011.067 2.804 8.129 11.451 8.129z"/><path class='logo-path left' d="M20.312 6.188c-8.737 0-10.651 8.163-10.674 8.242l-.056.462c.056-.27 3.715-6.587 10.719-6.587 7.105 0 9.357 6.418 9.447 6.688l2.015-.676c-.011-.067-2.792-8.129-11.45-8.129z"/></svg>
					</a>
				</div>
				<div class='wrapper-nav-lang'>
					<nav role='navigation' class='nav-header'>
						<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'menu-main js-menu-main', 'walker' => new WPSE_78121_Sublevel_Walker ) ); ?>
					</nav>
					<div class='wrapper-lang'>
						<?php 
						mlp_show_linked_elements( array( 'link_text' => 'text', 'echo' => true, 'sort' => 'blogid', 'show_current_blog' => TRUE ) );
						?>
					</div>
				</div>
				<button type='button' aria-label="menu" class='wrapper-burger js-burger'>
					<span class='burger'>
						<span></span>
						<span></span>
						<span></span>
					</span>
				</button>
			</div>
		</header>

		<main role='main'>
