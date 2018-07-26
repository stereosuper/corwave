<?php
/*
Template Name: Contact
*/

require_once('include/recaptchalib.php');
$publickey = "Lcey2UUAAAAALlhYkUUiRNJVh-uOyeVtoq7Ab1G";
$privatekey = "6Lcey2UUAAAAAL7PAO6QVQik3bogkUouenh9nNEm";

$error = false;
$success = false;

$errorFirstName = false;
$errorLastName = false;
$errorMail = false;
$errorMailTxt = false;
$errorSubject = false;
$errorMsg = false;
$errorFileUpload = false;
$errorAcceptTerms = false;
$errorEmpty = false;
$errorSend = false;
$errorCaptcha = false;

// COMBAK: remove error tests
// $errorFirstName = true;
// $errorLastName = true;
// $errorMail = true;
// $errorMailTxt = true;
// $errorSubject = true;
// $errorMsg = true;
// $errorFileUpload = true;
// $errorAcceptTerms = true;

$firstName = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
$lastName = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
$company = isset($_POST['company']) ? sanitize_text_field($_POST['company']) : '';
$mail = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
$subject = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : '';
$msg = isset($_POST['message']) ? strip_tags(stripslashes($_POST['message'])) : '';
if (isset($_FILES['file_upload']) && $fileUploaded = is_uploaded_file($_FILES['file_upload']['tmp_name'])) {
	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	}
	$joinedFile = $_FILES['file_upload'];
	$upload_overrides = array( 'test_form' => false );

	$moveFile = wp_handle_upload($joinedFile, $upload_overrides);
	if (isset($moveFile['error'])) {
		$errorFileUpload = true;
		$error = true;
	} elseif ($moveFile) {
		$attachments = $moveFile['file'];
	} else {
		$attachments = false;
	}
} else {
	$joinedFile = false;
}
$acceptTerms = isset($_POST['accept_terms']);
$spamUrl = isset($_POST['url']) ? strip_tags(stripslashes($_POST['url'])) : '';

// TODO: Change mail to
// $mailto = get_field('emailsContact', 'options');
$mailto = 'alban@stereosuper.fr';

if( isset($_POST['submit']) ){

	$captchaResponse = $_POST['g-recaptcha-response'];
	$remoteIp = $_SERVER['REMOTE_ADDR'];
	$apiUrl = "https://www.google.com/recaptcha/api/siteverify?secret=" 
	    . $privatekey
	    . "&response=" . $captchaResponse
	    . "&remoteip=" . $remoteIp ;

	$decode = json_decode(file_get_contents($apiUrl), true);
	
	if ($decode['success'] == true) {
		// C'est un humain
		
	} else {
		// C'est un robot ou le code de vérification est incorrecte
		$errorCaptcha = true;
		$error = true;
	}

	if( !isset($_POST['corwave_contact_nonce']) || !wp_verify_nonce($_POST['corwave_contact_nonce'], 'corwave_contact') ){
		$error = true;
		$errorSend = true;
	} else {

		if( empty($firstName) ){
			$errorFirstName = true;
			$errorEmpty = true;
			$error = true;
        }
        
		if( empty($lastName) ){
			$errorLastName = true;
			$errorEmpty = true;
			$error = true;
		}


		if( empty($mail) ){
			$errorMail = true;
			$errorEmpty = true;
			$error = true;
		} else {
			if( !filter_var($mail, FILTER_VALIDATE_EMAIL) ){
				$errorMail = true;
				$error = true;
			}
		}

		if( empty($subject) ){
			$errorSubject = true;
			$errorEmpty = true;
			$error = true;
		}

		if( empty($msg) ){
			$errorMsg = true;
			$errorEmpty = true;
			$error = true;
		}

		if( !$acceptTerms ){
			$errorAcceptTerms = true;
			$error = true;
		}

		if( !$error ){
			if( empty($spamUrl) ){
				$headers = 
						"MIME-Version: 1.0" . "\r\n" .
						"Content-type: text/plain; charset=utf-8" . "\r\n" .
						"From: " . $firstName . " " . $lastName . " <" . $mail . ">" . "\r\n" .
						"Reply-To: " . $firstName . " " . $lastName . " <" . $mail . ">" ."\r\n" .
						"X-Mailer: PHP/" . phpversion();  
                
				$subjectMail = 'New message from corwave.fr';
				$content = 
					__("Contact form message:\r\n" .
					"From: " . $firstName . " " . $lastName .
					" working at " . $company . ".\r\n" .
					'Email: ' . $mail . "\r\n" .
					'Subject: ' . $subject . "\r\n\r\n" .
					'Message: ' . $msg, 'corwave');
				
				if ($fileUploaded && $attachments) {
					$sent = wp_mail($mailto, $subjectMail, $content, $headers, $attachments);
					unlink($attachments);
				} else {
					$sent = wp_mail($mailto, $subjectMail, $content, $headers);
				}
				
				if ($sent) {
					$success = true;
				} else {
					$error = true;
					$errorSend = true;
				}
			} else {
				$success = true;
			}
		}
	}
}

get_header(); ?>
<div class="has-sidebar">
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
    <div class='container pb custom-sidebar contact-sidebar'>
		<div class='container-small'>
			<?php if ( have_posts() ) : the_post(); ?>
				<div class='content-page'>
					<?php
						if ( function_exists('yoast_breadcrumb') ) {
							yoast_breadcrumb('
							<div class="breadcrumbs">','</div>
							');
						}
					?>
                    <div class='form-contact'>

                        <?php if( $success ) { ?>
                            <p class='form-success'>
                                <?php _e('Thank you, your message has been sent! We will get back at you as soon as possible.', 'corwave') ?>
                            </p>
                        <?php } else if( $error ) { ?>
                            <p class='form-error'>
                                <?php if( $errorSend ) { ?>
                                    <?php _e('We are sorry, an error has occured! Please try again later.', 'corwave') ?>
                                <?php } else { ?>
									<?php if ($errorEmpty) : ?>
									<span><?php _e('A required field might be empty or invalid, please correct it.', 'corwave') ?></span>
									<?php else: ?>
									<span><?php _e('Please correct the following mistakes.', 'corwave') ?></span>
									<?php endif; ?>
									<?php if ($errorMail) : ?>
                                    	<span><?php _e('Your email address is invalid.', 'corwave') ?></span>
									<?php endif; ?>
									<?php if ($errorCaptcha) : ?>
                                    	<span><?php _e('Please don\'t forget to prove that you are not a robot.', 'corwave') ?></span>
									<?php endif; ?>
									<?php if ($errorAcceptTerms) : ?>
                                    	<span><?php _e('Please accept the form\'s terms & conditions.', 'corwave') ?></span>
									<?php endif; ?>
									<?php if ($errorFileUpload) : ?>
                                    	<span><?php _e('Please insert an uncorrupted media file.', 'corwave') ?></span>
									<?php endif; ?>
                                <?php } ?>
                            </p>
                        <?php } ?>

						<?php if (!$success) : ?>
							<form method='post' action='<?php the_permalink(); ?>#form' class='<?php if( $success ) echo "success"; ?>' id='form-contact' enctype="multipart/form-data">
								<div class='field required <?php echo $errorFirstName ? 'error' : '' ?>'>
									<label for='first-name'><?php _e('First Name', 'corwave') ?></label>
									<input type='text' name='first_name' id='first-name' value='<?php echo esc_attr( $firstName ); ?>' placeholder='<?php _e('Your first name', 'corwave') ?>' required>
								</div>

								<div class='field required <?php if($errorLastName) echo 'error'; ?>'>
									<label for='last-name'><?php _e('Last Name', 'corwave') ?></label>
									<input type='text' name='last_name' id='last-name' value='<?php echo esc_attr( $lastName ); ?>' placeholder='<?php _e('Your last name', 'corwave') ?>' required>
								</div>

								<div class='field required <?php if($errorMail) echo 'error'; ?>'>
									<label for='email'><?php _e('Email', 'corwave') ?></label>
									<input type='email' name='email' id='email' value='<?php echo esc_attr( $mail ); ?>' placeholder='<?php _e('contact@email.com', 'corwave') ?>' required>
								</div>

								<div class='field'>
									<label for='company'><?php _e('Company', 'corwave') ?></label>
									<input type='text' name='company' id='company' value='<?php echo esc_attr( $company ); ?>' placeholder='<?php _e('Your company name...', 'corwave') ?>' required>
								</div>

								<div class='field required <?php if($errorSubject) echo 'error'; ?>'>
									<label for='subject'><?php _e('Subject', 'corwave') ?></label>
									<input type='text' name='subject' id='subject' class='subject' value='<?php echo esc_attr( $subject ); ?>' placeholder='<?php _e('Describe your request', 'corwave') ?>' required>
								</div>

								<div class='field field-textarea required <?php if($errorMsg) echo 'error'; ?>'>
									<label for='message'><?php _e('Message', 'corwave') ?></label>
									<textarea name='message' id='message' placeholder="<?php _e('Enter your text here', 'corwave') ?>" required><?php echo esc_textarea( $msg ); ?></textarea>
								</div>

								<div class='field checkbox <?php if($errorAcceptTerms) echo 'error'; ?>'>
									<input type="checkbox" id="accept-terms" name="accept_terms" <?php echo $acceptTerms ? 'checked' : ''; ?>/>
									<label for="accept-terms"><span><?php _e('By submitting this form, I consent to be recontacted within the framework of this commercial relationship.', 'corwave') ?></span></label>
								</div>

								<div class='field file'>
									<label for="file-upload"><span></span><?php _e('Join a file:', 'corwave') ?>
									<div class='cta cta-light'>
										<span>
											<?php _e('Browse', 'corwave') ?>
										</span>
										<svg class='icon icon-arrow'><use xlink:href='#icon-arrow'></use></svg>
									</div>
									</label>
									<input id="file-upload" type="file" name="file_upload" accept="media_type">
								</div>

								<div class="g-recaptcha" data-sitekey="6Lcey2UUAAAAALlhYkUUiRNJVh-uOyeVtoq7Ab1G"></div>

								<div class='hidden'>
									<input type='url' name='url' id='url' value='<?php echo esc_url( $spamUrl ); ?>'>
									<label for='url'><?php _e('Please leave this field empty.', 'corwave') ?></label>
								</div>

								<?php wp_nonce_field( 'corwave_contact', 'corwave_contact_nonce' ); ?>
								
								<div class='field cta-wrapper'>
									<button class='cta' type='submit' name='submit' form='form-contact'>
										<span>
											<svg class='ellypsis top'><use xlink:href='#icon-ellypsis-top'></use></svg>
											<svg class='ellypsis bottom'><use xlink:href='#icon-ellypsis-bottom'></use></svg>
											<?php _e('Send your message', 'corwave') ?>
										</span>
										<svg class='icon icon-arrow'><use xlink:href='#icon-arrow'></use></svg>
									</button>
								</div>
							</form>
						<?php endif; ?>
                    </div>
				</div>
			
			<?php else : ?>
						
				<h1>404</h1>

			<?php endif; ?>
		</div>
		<nav class="sidebar">
			<div class="sidebar-content">
				<div class="sidebar-part">
					<h3><?php _e('Bureaux', 'corwave') ?></h3>
					<div class="address">
						<svg class='icon'><use xlink:href='#icon-pin'></use></svg>
						<span>
							<?php the_field('contact_address') ?>
						</span>
					</div>
				</div>
				<div class="sidebar-part">
					<h3><?php _e('Coordonnées', 'corwave') ?></h3>
					<ul>
						<li class='sidebar-link no-underline'>
							<a href="tel:<?php the_field('contact_phone_number') ?>">
								<svg class='icon'><use xlink:href='#icon-phone'></use></svg>
								<span>
									<?php the_field('contact_phone_number') ?>
								</span>
							</a>
						</li>
						<li class='sidebar-link'>
							<a href="mailto:<?php the_field('contact_email') ?>">
								<svg class='icon'><use xlink:href='#icon-mail'></use></svg>
								<span>
									<?php the_field('contact_email') ?>
								</span>
							</a>
						</li>
					</ul>
				</div>
				<div class="sidebar-part social-networks">
					<h3><?php _e('Suivez-nous', 'corwave') ?></h3>
					<ul>
						<li>
							<a href="<?php the_field('social_networks_linkedin', 'option') ?>">
								<svg class='icon'><use xlink:href='#icon-linkedin'></use></svg>
							</a>
						</li>
						<li>
							<a href="<?php the_field('social_networks_twitter', 'option') ?>">
								<svg class='icon'><use xlink:href='#icon-twitter'></use></svg>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<?php 
		// TODO: Custom sidebar
		?>
	</div>
	<div id="corwave-map"></div>
</div>
<?php get_footer(); ?>
<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyCMAYab_tmsuABPTE_haSUcVBUqXIuuz5o&extension=.js"></script>
	<!-- <script src="//cdn.mapkit.io/v1/infobox.js"></script>
	<link href="//fonts.googleapis.com/css?family=Roboto:300,400" rel="stylesheet">
	<link href="//cdn.mapkit.io/v1/infobox.css" rel="stylesheet" > -->
	<script>
		google.maps.event.addDomListener(window, 'load', init);
		let map,
			markersArray = [];
		function bindInfoWindow(marker, map, location) {
			google.maps.event.addListener(marker, 'click', () => {
				// function close(location) {
				// 	location.ib.close();
				// 	location.infoWindowVisible = false;
				// 	location.ib = null;
				// }
				if (location.infoWindowVisible === true) {
					// close(location);
				} else {
					// markersArray.forEach(function(loc, index) {
					// 	if (loc.ib && loc.ib !== null) {
					// 		close(loc);
					// 	}
					// });
					var boxText = document.createElement('div');
					boxText.style.cssText = 'background: #fff;';
					boxText.classList.add('md-whiteframe-2dp');

					function buildPieces(location, el, part, icon) {
						if (location[part] === '') {
							return '';
						} else if (location.iw[part]) {
							switch (el) {
								case 'photo':
									if (location.photo) {
										return (
											'<div class="iw-photo" style="background-image: url(' +
											location.photo +
											');"></div>'
										);
									} else {
										return '';
									}
									break;
								case 'iw-toolbar':
									return (
										'<div class="iw-toolbar"><h3 class="md-subhead">' +
										location.title +
										'</h3></div>'
									);
									break;
								case 'div':
									switch (part) {
										case 'email':
											return (
												'<div class="iw-details"><i class="material-icons" style="color:#4285f4;"><img src="//cdn.mapkit.io/v1/icons/' +
												icon +
												'.svg"/></i><span><a href="mailto:' +
												location.email +
												'" target="_blank">' +
												location.email +
												'</a></span></div>'
											);
											break;
										case 'web':
											return (
												'<div class="iw-details"><i class="material-icons" style="color:#4285f4;"><img src="//cdn.mapkit.io/v1/icons/' +
												icon +
												'.svg"/></i><span><a href="' +
												location.web +
												'" target="_blank">' +
												location.web_formatted +
												'</a></span></div>'
											);
											break;
										case 'desc':
											return (
												'<label class="iw-desc" for="cb_details"><input type="checkbox" id="cb_details"/><h3 class="iw-x-details">Details</h3><i class="material-icons toggle-open-details"><img src="//cdn.mapkit.io/v1/icons/' +
												icon +
												'.svg"/></i><p class="iw-x-details">' +
												location.desc +
												'</p></label>'
											);
											break;
										default:
											return (
												'<div class="iw-details"><i class="material-icons"><img src="//cdn.mapkit.io/v1/icons/' +
												icon +
												'.svg"/></i><span>' +
												location.part +
												'</span></div>'
											);
											break;
									}
									break;
								case 'open_hours':
									var items = '';
									if (location.open_hours.length > 0) {
										for (
											var i = 0;
											i < location.open_hours.length;
											++i
										) {
											if (i !== 0) {
												items +=
													'<li><strong>' +
													location.open_hours[i].day +
													'</strong><strong>' +
													location.open_hours[i].hours +
													'</strong></li>';
											}
											var first =
												'<li><label for="cb_hours"><input type="checkbox" id="cb_hours"/><strong>location.open_hours[0].day</strong><strong>' +
												location.open_hours[0].hours +
												'</strong><i class="material-icons toggle-open-hours"><img src="//cdn.mapkit.io/v1/icons/keyboard_arrow_down.svg"/></i><ul>' +
												items +
												'</ul></label></li>';
										}
										return (
											'<div class="iw-list"><i class="material-icons first-material-icons" style="color:#4285f4;"><img src="//cdn.mapkit.io/v1/icons/' +
											icon +
											'.svg"/></i><ul>' +
											first +
											'</ul></div>'
										);
									} else {
										return '';
									}
									break;
							}
						} else {
							return '';
						}
					}
					boxText.innerHTML =
						buildPieces(location, 'photo', 'photo', '') +
						buildPieces(location, 'iw-toolbar', 'title', '') +
						buildPieces(location, 'div', 'address', 'location_on') +
						buildPieces(location, 'div', 'web', 'public') +
						buildPieces(location, 'div', 'email', 'email') +
						buildPieces(location, 'div', 'tel', 'phone') +
						buildPieces(location, 'div', 'int_tel', 'phone') +
						buildPieces(
							location,
							'open_hours',
							'open_hours',
							'access_time'
						) +
						buildPieces(location, 'div', 'desc', 'keyboard_arrow_down');
					// var myOptions = {
					// 	alignBottom: true,
					// 	content: boxText,
					// 	disableAutoPan: false,
					// 	maxWidth: 0,
					// 	pixelOffset: new google.maps.Size(-140, -40),
					// 	zIndex: null,
					// 	boxStyle: {
					// 		opacity: 1,
					// 		width: '280px'
					// 	},
					// 	closeBoxMargin: '0px 0px 0px 0px',
					// 	infoBoxClearance: new google.maps.Size(1, 1),
					// 	isHidden: false,
					// 	pane: 'floatPane',
					// 	enableEventPropagation: false
					// };
					// location.ib = new InfoBox(myOptions);
					// location.ib.open(map, marker);
					location.infoWindowVisible = true;
				}
			});
		}

		function init() {
			let mapOptions = {
				center: new google.maps.LatLng(48.9006158, 2.2962691000000177),
				zoom: 12,
				gestureHandling: 'auto',
				fullscreenControl: true,
				zoomControl: true,
				disableDoubleClickZoom: true,

				mapTypeControl: true,
				mapTypeControlOptions: {
					style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
				},
				scaleControl: true,
				scrollwheel: true,
				streetViewControl: true,
				draggable: true,
				clickableIcons: false,
				fullscreenControlOptions: {
					position: google.maps.ControlPosition.TOP_RIGHT,
				},
				zoomControlOptions: {
					position: google.maps.ControlPosition.RIGHT_BOTTOM,
				},
				streetViewControlOptions: {
					position: google.maps.ControlPosition.RIGHT_BOTTOM,
				},
				mapTypeControlOptions: {
					position: google.maps.ControlPosition.TOP_LEFT,
				},

				mapTypeId: google.maps.MapTypeId.ROADMAP,

				styles: [
					{
						"featureType": "all",
						"elementType": "geometry.fill",
						"stylers": [
							{
								"weight": "2.00"
							}
						]
					},
					{
						"featureType": "all",
						"elementType": "geometry.stroke",
						"stylers": [
							{
								"color": "#9c9c9c"
							}
						]
					},
					{
						"featureType": "all",
						"elementType": "labels.text",
						"stylers": [
							{
								"visibility": "on"
							}
						]
					},
					{
						"featureType": "administrative",
						"elementType": "labels.text.fill",
						"stylers": [
							{
								"color": "#5cccff"
							}
						]
					},
					{
						"featureType": "administrative.country",
						"elementType": "labels.text.fill",
						"stylers": [
							{
								"hue": "#00afff"
							}
						]
					},
					{
						"featureType": "administrative.province",
						"elementType": "labels.text.fill",
						"stylers": [
							{
								"hue": "#00afff"
							}
						]
					},
					{
						"featureType": "administrative.locality",
						"elementType": "labels.text.fill",
						"stylers": [
							{
								"color": "#3c4654"
							}
						]
					},
					{
						"featureType": "administrative.neighborhood",
						"elementType": "labels.text.fill",
						"stylers": [
							{
								"color": "#5cccff"
							}
						]
					},
					{
						"featureType": "landscape",
						"elementType": "all",
						"stylers": [
							{
								"color": "#f2f2f2"
							}
						]
					},
					{
						"featureType": "landscape",
						"elementType": "geometry.fill",
						"stylers": [
							{
								"color": "#ffffff"
							}
						]
					},
					{
						"featureType": "landscape.man_made",
						"elementType": "geometry.fill",
						"stylers": [
							{
								"color": "#ffffff"
							}
						]
					},
					{
						"featureType": "poi",
						"elementType": "all",
						"stylers": [
							{
								"visibility": "off"
							}
						]
					},
					{
						"featureType": "road",
						"elementType": "all",
						"stylers": [
							{
								"saturation": -100
							},
							{
								"lightness": 45
							}
						]
					},
					{
						"featureType": "road",
						"elementType": "geometry.fill",
						"stylers": [
							{
								"color": "#eeeeee"
							}
						]
					},
					{
						"featureType": "road",
						"elementType": "labels.text.fill",
						"stylers": [
							{
								"color": "#7b7b7b"
							}
						]
					},
					{
						"featureType": "road",
						"elementType": "labels.text.stroke",
						"stylers": [
							{
								"color": "#ffffff"
							}
						]
					},
					{
						"featureType": "road.highway",
						"elementType": "all",
						"stylers": [
							{
								"visibility": "simplified"
							}
						]
					},
					{
						"featureType": "road.arterial",
						"elementType": "labels.icon",
						"stylers": [
							{
								"visibility": "off"
							}
						]
					},
					{
						"featureType": "transit",
						"elementType": "all",
						"stylers": [
							{
								"visibility": "off"
							}
						]
					},
					{
						"featureType": "water",
						"elementType": "all",
						"stylers": [
							{
								"color": "#46bcec"
							},
							{
								"visibility": "on"
							}
						]
					},
					{
						"featureType": "water",
						"elementType": "geometry.fill",
						"stylers": [
							{
								"color": "#9fa9b6"
							}
						]
					},
					{
						"featureType": "water",
						"elementType": "labels.text.fill",
						"stylers": [
							{
								"color": "#070707"
							}
						]
					},
					{
						"featureType": "water",
						"elementType": "labels.text.stroke",
						"stylers": [
							{
								"color": "#ffffff"
							}
						]
					}
				]
			};
			let mapElement = document.getElementById('corwave-map');
			let map = new google.maps.Map(mapElement, mapOptions);
			let locations = [
				{
					title: '17 Rue de Neuilly',
					address: '17 Rue de Neuilly, 92110 Clichy, France',
					desc: '',
					tel: '',
					int_tel: '',
					email: 'contact@corwave.com',
					web: 'http://corwave.fr',
					web_formatted: 'corwave.fr',
					open: '',
					time: '',
					lat: 48.9006158,
					lng: 2.2962691000000177,
					photo: '',
					open_hours: '',
					// TODO: change to local url
					marker: '<?php echo get_template_directory_uri() . '/layoutImg/marker.png' ?>',
					iw: {
						address: true,
						desc: true,
						email: true,
						enable: true,
						int_tel: true,
						open: true,
						open_hours: true,
						photo: true,
						tel: true,
						title: true,
						web: true,
					},
				},
			];

			for (i = 0; i < locations.length; i++) {
				marker = new google.maps.Marker({
					icon: locations[i].marker,
					position: new google.maps.LatLng(
						locations[i].lat,
						locations[i].lng,
					),
					map,
					title: locations[i].title,
					address: locations[i].address,
					desc: locations[i].desc,
					tel: locations[i].tel,
					int_tel: locations[i].int_tel,
					vicinity: locations[i].vicinity,
					open: locations[i].open,
					open_hours: locations[i].open_hours,
					photo: locations[i].photo,
					time: locations[i].time,
					email: locations[i].email,
					web: locations[i].web,
					iw: locations[i].iw,
				});

				markersArray.push(marker);

				if (locations[i].iw.enable === true) {
					bindInfoWindow(marker, map, locations[i]);
				}
			}

			var myoverlay = new google.maps.OverlayView();
			myoverlay.draw = function () {
				// add an id to the layer that includes all the markers so you can use it in CSS
				this.getPanes().markerLayer.id='markerLayer';
				const images = document.getElementById('markerLayer');
				const img = images.getElementsByTagName('img');
				if (img.length) {
					var parent = img[0].parentElement;
					parent.id = 'imgMarker';
					// var duplicatedParent = parent.cloneNode(true);
					// duplicatedParent.id = 'imgMarker';
					// var replacedNode = parent.parentElement.replaceChild(duplicatedParent, parent);
				}
			};
			myoverlay.setMap(map);
		}
	</script>