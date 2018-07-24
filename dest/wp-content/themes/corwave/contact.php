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
$errorAcceptTerms = false;
$errorEmpty = false;
$errorSend = false;
$errorCaptcha = false;

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
                                    	<span><?php _e('A required field might be empty.', 'corwave') ?></span>
									<?php endif; ?>
									<span><?php _e('Please correct the following mistakes.', 'corwave') ?></span>
									<?php if ($errorMail) : ?>
                                    	<span><?php _e('The email address is not valid.', 'corwave') ?></span>
									<?php endif; ?>
									<?php if ($errorCaptcha) : ?>
                                    	<span><?php _e('Please prove that you are not a robot.', 'corwave') ?></span>
									<?php endif; ?>
									<?php if ($errorAcceptTerms) : ?>
                                    	<span><?php _e('Please accept the form\'s terms & conditions.', 'corwave') ?></span>
									<?php endif; ?>
									<?php if ($errorFileUpload) : ?>
                                    	<span><?php _e('Please insert a non-corrupted media file.', 'corwave') ?></span>
									<?php endif; ?>
                                <?php } ?>
                            </p>
                        <?php } ?>

						<?php if (!$success) : ?>
							<form method='post' action='<?php the_permalink(); ?>#form' class='<?php if( $success ) echo "success"; ?>' id='form-contact' enctype="multipart/form-data">
								<div class='field <?php if($errorFirstName) echo 'error'; ?>'>
									<label for='first-name'><?php _e('First Name', 'corwave') ?></label>
									<input type='text' name='first_name' id='first-name' value='<?php echo esc_attr( $firstName ); ?>' placeholder='<?php _e('Your first name', 'corwave') ?>' required>
								</div>

								<div class='field <?php if($errorLastName) echo 'error'; ?>'>
									<label for='last-name'><?php _e('Last Name', 'corwave') ?></label>
									<input type='text' name='last_name' id='last-name' value='<?php echo esc_attr( $lastName ); ?>' placeholder='<?php _e('Your last name', 'corwave') ?>' required>
								</div>

								<div class='field <?php if($errorMail) echo 'error'; ?>'>
									<label for='email'><?php _e('Email', 'corwave') ?></label>
									<input type='email' name='email' id='email' value='<?php echo esc_attr( $mail ); ?>' placeholder='<?php _e('contact@email.com', 'corwave') ?>' required>
								</div>

								<div class='field'>
									<label for='company'><?php _e('Company', 'corwave') ?></label>
									<input type='text' name='company' id='company' value='<?php echo esc_attr( $company ); ?>' placeholder='<?php _e('Your company name...', 'corwave') ?>' required>
								</div>

								<div class='field <?php if($errorSubject) echo 'error'; ?>'>
									<label for='subject'><?php _e('Subject', 'corwave') ?></label>
									<input type='text' name='subject' id='subject' class='subject' value='<?php echo esc_attr( $subject ); ?>' placeholder='<?php _e('Describe your request', 'corwave') ?>' required>
								</div>

								<div class='field field-textarea <?php if($errorMsg) echo 'error'; ?>'>
									<label for='message'><?php _e('Message', 'corwave') ?></label>
									<textarea name='message' id='message' placeholder="<?php _e('Enter your text here', 'corwave') ?>" required><?php echo esc_textarea( $msg ); ?></textarea>
								</div>

								<div class='field checkbox <?php if($errorAcceptTerms) echo 'error'; ?>'>
									<input type="checkbox" id="accept-terms" name="accept_terms" <?php echo $acceptTerms ? 'checked' : ''; ?>/>
									<label for="accept-terms"><?php _e('By submitting this form, I consent to be recontacted within the framework of this commercial relationship.', 'corwave') ?></label>
								</div>

								<div class='field file'>
									<label for="file-upload"><?php _e('Join a file:', 'corwave') ?></label>
									<input id="file-upload" type="file" name="file_upload" accept="media_type">
								</div>

								<div class="g-recaptcha" data-sitekey="6Lcey2UUAAAAALlhYkUUiRNJVh-uOyeVtoq7Ab1G"></div>

								<div class='hidden'>
									<input type='url' name='url' id='url' value='<?php echo esc_url( $spamUrl ); ?>'>
									<label for='url'><?php _e('Please leave this field empty.', 'corwave') ?></label>
								</div>

								<?php wp_nonce_field( 'corwave_contact', 'corwave_contact_nonce' ); ?>

								<button class='cta' type='submit' name='submit' form='form-contact'>
									<span>
										<svg class='ellypsis top'><use xlink:href='#icon-ellypsis-top'></use></svg>
										<svg class='ellypsis bottom'><use xlink:href='#icon-ellypsis-bottom'></use></svg>
										<?php _e('Send your message', 'corwave') ?>
									</span>
									<svg class='icon icon-arrow'><use xlink:href='#icon-arrow'></use></svg>
								</button>
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
						<li class='sidebar-link'>
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
</div>
<?php get_footer(); ?>