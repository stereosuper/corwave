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
	$upload_overrides   = array( 'test_form' => false );

	$moveFile = wp_handle_upload($joinedFile, $upload_overrides);
	if ($moveFile) {
		$attachments = $moveFile['file'];
	} else {
		$attachments = false;
	}
} else {
	$joinedFile = false;
}
$acceptTerms = isset($_POST['accept_terms']);
// TODO: Send newsletter
$acceptNewsletter = isset($_POST['accept_newsletter']);
$spamUrl = isset($_POST['url']) ? strip_tags(stripslashes($_POST['url'])) : '';

$current_language = getCurrentBlogLanguage();

// TODO: Change mail to
// $mailto = get_field('emailsContact', 'options');
$mailto = 'alban@stereosuper.fr';

switch ($current_language) {
	case 'en':
		// Form labels' texts
		$firstNameLabel = 'First Name';
		$lastNameLabel = 'Last Name';
		$mailLabel = 'Email';
		$companyLabel = 'Company';
		$subjectLabel = 'Subject';
		$msgLabel = 'Message';
		$fileUploadTxt = 'Join a file:';
		$acceptTermsLabel = 'By submitting this form, I consent to be recontacted within the framework of this commercial relationship.';
		$acceptNewsletterLabel = "I would like to recieve Corwave's newsletter.";

		$firstNamePlaceholder = 'Your first name';
		$lastNamePlaceholder = 'Your last name';
		$mailPlaceholder = 'contact@email.com';
		$companyPlaceholder = 'Your company name...';
		$subjectPlaceholder = 'Describe your request';
		$msgPlaceholder = 'Enter your text here';

		$sendButtonLabel = 'Send your message';

		// Errors' texts
		$errorMailTxt = 'The email address is not valid.';
		$errorCaptchaTxt = 'Please prove that you are not a robot.';
		$errorAcceptTermsTxt = 'Please accept the form\'s terms & conditions.';
		$errorSendTxt = 'We are sorry, an error has occured! Please try again later.';
		$errorEmptyTxt = 'A required field might be empty.';
		$errorDisplayedMessage = 'Please correct the following mistakes.';

		$successTxt = 'Thank you, your message has been sent! We will get back at you as soon as possible.';
        break;
	case 'fr':
		$firstNameLabel = 'Prénom';
		$lastNameLabel = 'Nom';
		$mailLabel = 'Email';
		$companyLabel = 'Société';
		$subjectLabel = 'Sujet';
		$msgLabel = 'Message';
		$fileUploadTxt = 'Joindre un fichier:';
		$acceptTermsLabel = "En soumettant ce formulaire, j'autorise que les informations saisies soient utilisées pour permettre de me recontacter dans le cadre de la relation commerciale qui découle de ce contact";
		$acceptNewsletterLabel = "J'aimerai recevoir les offres et nouveautés de Corwave.";

		$firstNamePlaceholder = 'Votre prénom';
		$lastNamePlaceholder = 'Votre nom';
		$mailPlaceholder = 'contact@email.com';
		$companyPlaceholder = 'Le nom de votre entreprise';
		$subjectPlaceholder = 'Décrivez votre demande';
		$msgPlaceholder = 'Saisissez votre message ici';

		$sendButtonLabel = 'Envoyer votre message';

		$errorMailTxt = 'L\'adresse email est invalide.';
		$errorCaptchaTxt = 'Faîtes la vérification pour dire que vous n\'êtes pas un robot.';
		$errorAcceptTermsTxt = 'Veuillez accepter les termes et conditions du formulaire.';
		$errorSendTxt = 'Nous sommes désolés, une erreur est survenue! Merci de réssayer plus tard.';
		$errorEmptyTxt = 'Un champs requis est vide.';
		$errorDisplayedMessage = 'Merci de corriger les erreurs ci-dessous.';

		$successTxt = 'Merci, votre message a bien été envoyé! Nous vous répondrons dans les plus bref délais.';
        break;
	default:
		$firstNameLabel = 'First Name';
		$lastNameLabel = 'Last Name';
		$mailLabel = 'Email';
		$companyLabel = 'Company';
		$subjectLabel = 'Subject';
		$msgLabel = 'Message';
		$fileUploadTxt = 'Join a file:';
		$acceptTermsLabel = 'By submitting this form, I consent to be recontacted within the framework of this commercial relationship.';
		$acceptNewsletterLabel = "I would like to recieve Corwave's newsletter.";

		$firstNamePlaceholder = 'Your first name';
		$lastNamePlaceholder = 'Your last name';
		$mailPlaceholder = 'contact@email.com';
		$companyPlaceholder = 'Your company name...';
		$subjectPlaceholder = 'Describe your request';
		$msgPlaceholder = 'Enter your text here';

		$sendButtonLabel = 'Send your message';

		$errorMailTxt = 'The email address is not valid.';
		$errorCaptchaTxt = 'Please prove that you are not a robot.';
		$errorAcceptTermsTxt = 'Please accept the form\'s terms & conditions.';
		$errorSendTxt = 'We are sorry, an error has occured! Please try again later.';
		$errorEmptyTxt = 'A required field might be empty.';
		$errorDisplayedMessage = 'Please correct the following mistakes.';

		$successTxt = 'Thank you, your message has been sent! We will get back at you as soon as possible.';
        break;
}

if( isset($_POST['submit']) ){

	// COMBAK: Uncomment captcha part
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
		// COMBAK: Uncomment error captcha
		// $errorCaptcha = true;
		// $error = true;
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
                
				switch ($current_language) {
					case 'en':
						$subjectMail = 'New message from corwave.fr';
						$content = 
							"Contact form message:\r\n" .
							"From: " . $firstName . " " . $lastName .
							" working at " . $company . ".\r\n" .
							'Email: ' . $mail . "\r\n" .
							'Subject: ' . $subject . "\r\n\r\n" .
							'Message: ' . $msg;
						break;
					case 'fr':
						$subjectMail = 'Nouveau message provenant de corwave.fr';
						$content = 
							"Formulaire de contact:\r\n" .
							"De: " . $firstName . " " . $lastName .
							" travaillant à " . $company . ".\r\n" .
							'Email: ' . $mail . "\r\n" .
							'Sujet: ' . $subject . "\r\n\r\n" .
							'Message: ' . $msg;
						break;
					default:
						$subjectMail = 'New message from corwave.fr';
						$content = 
							"Contact form message:\r\n" .
							"From: " . $firstName . " " . $lastName .
							" working at " . $company . ".\r\n" .
							'Email: ' . $mail . "\r\n" .
							'Subject: ' . $subject . "\r\n\r\n" .
							'Message: ' . $msg;
						break;
				}
				
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
<div <?php // echo $has_sidebar_class ?>>
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
    <div class='container pb <?php // echo $custom_anchors_sidebar ?>'>
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
                                <?php echo $successTxt ?>
                            </p>
                        <?php } else if( $error ) { ?>
                            <p class='form-error'>
                                <?php if( $errorSend ) { ?>
                                    <?php echo $errorSendTxt; ?>
                                <?php } else { ?>
									<?php if ($errorEmpty) : ?>
                                    	<span><?php echo $errorEmptyTxt; ?></span>
									<?php endif; ?>
									<span><?php echo $errorDisplayedMessage; ?></span>
									<?php if ($errorMail) : ?>
                                    	<span><?php echo $errorMailTxt; ?></span>
									<?php endif; ?>
									<?php if ($errorCaptcha) : ?>
                                    	<span><?php echo $errorCaptchaTxt; ?></span>
									<?php endif; ?>
									<?php if ($errorAcceptTerms) : ?>
                                    	<span><?php echo $errorAcceptTermsTxt; ?></span>
									<?php endif; ?>
                                <?php } ?>
                            </p>
                        <?php } ?>

						<?php if (!$success) : ?>
							<form method='post' action='<?php the_permalink(); ?>#form' class='<?php if( $success ) echo "success"; ?>' id='form-contact' enctype="multipart/form-data">
								<div class='field <?php if($errorFirstName) echo 'error'; ?>'>
									<label for='first-name'><?php echo $firstNameLabel ?></label>
									<input type='text' name='first_name' id='first-name' value='<?php echo esc_attr( $firstName ); ?>' placeholder='<?php echo $firstNamePlaceholder ?>' required>
								</div>

								<div class='field <?php if($errorLastName) echo 'error'; ?>'>
									<label for='last-name'><?php echo $lastNameLabel ?></label>
									<input type='text' name='last_name' id='last-name' value='<?php echo esc_attr( $lastName ); ?>' placeholder='<?php echo $lastNamePlaceholder ?>' required>
								</div>

								<div class='field <?php if($errorMail) echo 'error'; ?>'>
									<label for='email'><?php echo $mailLabel ?></label>
									<input type='email' name='email' id='email' value='<?php echo esc_attr( $mail ); ?>' placeholder='<?php echo $mailPlaceholder ?>' required>
								</div>

								<div class='field'>
									<label for='company'><?php echo $companyLabel ?></label>
									<input type='text' name='company' id='company' value='<?php echo esc_attr( $company ); ?>' placeholder='<?php echo $companyPlaceholder ?>' required>
								</div>

								<div class='field <?php if($errorSubject) echo 'error'; ?>'>
									<label for='subject'><?php echo $subjectLabel ?></label>
									<input type='text' name='subject' id='subject' class='subject' value='<?php echo esc_attr( $subject ); ?>' placeholder='<?php echo $subjectPlaceholder ?>' required>
								</div>

								<div class='field <?php if($errorMsg) echo 'error'; ?>'>
									<label for='message'><?php echo $msgLabel ?></label>
									<textarea name='message' id='message' placeholder="<?php echo $msgPlaceholder ?>" required><?php echo esc_textarea( $msg ); ?></textarea>
								</div>

								<div class='field <?php if($errorAcceptTerms) echo 'error'; ?>'>
									<input type="checkbox" id="accept-terms" name="accept_terms" <?php echo $acceptTerms ? 'checked' : ''; ?>/>
									<label for="accept-terms"><?php echo $acceptTermsLabel ?></label>
								</div>

								<div class='field'>
									<input type="checkbox" id="accept-newsletter" name="accept_newsletter" <?php echo $acceptNewsletter ? 'checked' : ''; ?>/>
									<label for="accept-newsletter"><?php echo $acceptNewsletterLabel ?></label>
								</div>
								
								<div class='field'>
									<label for="file-upload"><?php echo $fileUploadTxt ?></label>
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
										<?php echo $sendButtonLabel ?>
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
		<?php 
		// if ($has_sidebar) {
		// 	echo $custom_sidebar_menu;
		// }
		?>
	</div>
</div>
<?php get_footer(); ?>