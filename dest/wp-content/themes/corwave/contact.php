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
$errorCompany = false;
$errorMail = false;
$errorMailTxt = false;
$errorSubject = false;
$errorMsg = false;
$errorEmpty = false;
$errorSend = false;

$firstName = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
$lastName = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
$company = isset($_POST['company']) ? sanitize_text_field($_POST['company']) : '';
$mail = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
$subject = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : '';
$msg = isset($_POST['message']) ? strip_tags(stripslashes($_POST['message'])) : '';
$spamUrl = isset($_POST['url']) ? strip_tags(stripslashes($_POST['url'])) : '';

$current_language = getCurrentBlogLanguage();

// TODO: Change mail to
// $mailto = get_field('emailsContact', 'options');
$mailto = 'alban@stereosuper.fr';

if( isset($_POST['submit']) ){

	// COMBAK: Uncomment captcha part
	// $response = $_POST['g-recaptcha-response'];
	// $remoteip = $_SERVER['REMOTE_ADDR'];
	// $api_url = "https://www.google.com/recaptcha/api/siteverify?secret=" 
	//     . $privatekey
	//     . "&response=" . $response
	//     . "&remoteip=" . $remoteip ;

	// $decode = json_decode(file_get_contents($api_url), true);
	
	// if ($decode['success'] == true) {
	// 	// C'est un humain
		
	// } else {
	// 	// C'est un robot ou le code de vérification est incorrecte
	// 	$errorCaptchaTxt = 'Faîtes la vérification pour dire que vous n\'êtes pas un robot.';
	// 	$errorCaptcha = true;
	// 	$error = true;
	// }

	if( !isset($_POST['corwave_contact_nonce']) || !wp_verify_nonce($_POST['corwave_contact_nonce'], 'corwave_contact') ){
	
		$error = true;
		$errorSend = 'Nous sommes désolés, une erreur est survenue! Merci de réssayer plus tard.';

	}else{

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

		if( empty($company) ){
			$errorCompany = true;
			$errorEmpty = true;
			$error = true;
		}

		if( empty($mail) ){
			$errorMail = true;
			$errorEmpty = true;
			$error = true;
		}else{
			if( !filter_var($mail, FILTER_VALIDATE_EMAIL) ){
				$errorMail = true;
				$error = true;

				switch ($current_language) {
					case 'en':
						$errorMailTxt = 'The email address is not valid.';
						break;
					case 'fr':
						$errorMailTxt = 'L\'adresse email est invalide.';
						break;
					default:
						$errorMailTxt = 'The email address is not valid.';
						break;
				}
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


		if( !$error ){
			if( empty($spamUrl) ){
				$headers = 
						"MIME-Version: 1.0" . "\r\n" .
						"Content-type: text/plain; charset=utf-8" . "\r\n" .
						"From: " . $firstName . " " . $lastName . " <" . $mail . ">" . "\r\n" .
						"Reply-To: " . $firstName . " " . $lastName . " <" . $mail . ">" ."\r\n" .
						"X-Mailer: PHP/" . phpversion();  
                
                // COMBAK: Change content
				
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
				
				$sent = wp_mail($mailto, $subjectMail, $content, $headers);
				
				if( $sent ){
					$success = true;
				}else{
					$error = true;
					switch ($current_language) {
						case 'en':
							$errorSend = 'We are sorry, an error has occured! Please try again later.';
							break;
						case 'fr':
							$errorSend = 'Nous sommes désolés, une erreur est survenue! Merci de réssayer plus tard.';
							break;
						default:
							$errorSend = 'Nous sommes désolés, une erreur est survenue! Merci de réssayer plus tard.';
							break;
					}
				}
			}else{
				$success = true;
			}
		}
	}
}

// Form labels' texts
switch ($current_language) {
	case 'en':
		$firstNameLabel = 'First Name';
		$lastNameLabel = 'Last Name';
		$mailLabel = 'Email';
		$companyLabel = 'Company';
		$subjectLabel = 'Subject';
		$msgLabel = 'Message';

		$firstNamePlaceholder = 'Your first name';
		$lastNamePlaceholder = 'Your last name';
		$mailPlaceholder = 'contact@email.com';
		$companyPlaceholder = 'Your company name...';
		$subjectPlaceholder = 'Describe your request';
		$msgPlaceholder = 'Enter your text here';

		$sendButtonLabel = 'Send your message';
        break;
	case 'fr':
		$firstNameLabel = 'Prénom';
		$lastNameLabel = 'Nom';
		$mailLabel = 'Email';
		$companyLabel = 'Société';
		$subjectLabel = 'Sujet';
		$msgLabel = 'Message';

		$firstNamePlaceholder = 'Votre prénom';
		$lastNamePlaceholder = 'Votre nom';
		$mailPlaceholder = 'contact@email.com';
		$companyPlaceholder = 'Le nom de votre entreprise';
		$subjectPlaceholder = 'Décrivez votre demande';
		$msgPlaceholder = 'Saisissez votre message ici';

		$sendButtonLabel = 'Envoyer votre message';
        break;
	default:
		$firstNameLabel = 'First Name';
		$lastNameLabel = 'Last Name';
		$mailLabel = 'Email';
		$companyLabel = 'Company';
		$subjectLabel = 'Subject';
		$msgLabel = 'Message';

		$firstNamePlaceholder = 'Your first name';
		$lastNamePlaceholder = 'Your last name';
		$mailPlaceholder = 'contact@email.com';
		$companyPlaceholder = 'Your company name...';
		$subjectPlaceholder = 'Describe your request';
		$msgPlaceholder = 'Enter your text here';

		$sendButtonLabel = 'Send your message';
        break;
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
                                Merci, votre message a bien été envoyé!
                                <span>Nous vous répondrons dans les plus bref délais.</span>
                            </p>
                        <?php } else if( $error ) { ?>
                            <p class='form-error'>
                                <?php if( $errorSend ) { ?>
                                    <?php echo $errorSend; ?>
                                <?php } else { ?>
                                    <?php if ($errorEmpty) echo 'Merci de corriger les erreurs ci-dessous.'; ?>
                                    <span><?php if($errorCaptcha) echo $errorCaptchaTxt; ?></span>
                                    <span><?php if($errorMailTxt) echo $errorMailTxt; ?></span>
                                <?php } ?>
                            </p>
                        <?php } ?>

                        <form method='post' action='<?php the_permalink(); ?>#form' class='<?php if( $success ) echo "success"; ?>' id='form-contact'>
                            <div class='field <?php if($errorFirstName) echo 'error'; ?>'>
                                <label for='name'><?php echo $firstNameLabel ?></label>
                                <input type='text' name='first_name' id='name' value='<?php echo esc_attr( $firstName ); ?>' placeholder='<?php echo $firstNamePlaceholder ?>' required>
                            </div>

                            <div class='field <?php if($errorLastName) echo 'error'; ?>'>
                                <label for='name'><?php echo $lastNameLabel ?></label>
                                <input type='text' name='last_name' id='name' value='<?php echo esc_attr( $lastName ); ?>' placeholder='<?php echo $lastNamePlaceholder ?>' required>
                            </div>

                            <div class='field <?php if($errorMail) echo 'error'; ?>'>
                                <label for='email'><?php echo $mailLabel ?></label>
                                <input type='email' name='email' id='email' value='<?php echo esc_attr( $mail ); ?>' placeholder='<?php echo $mailPlaceholder ?>' required>
                            </div>

                            <div class='field <?php if($errorCompany) echo 'error'; ?>'>
                                <label for='company'><?php echo $companyLabel ?></label>
                                <input type='text' name='company' id='company' value='<?php echo esc_attr( $company ); ?>' placeholder='<?php echo $companyPlaceholder ?>' required>
                            </div>

							<!-- TODO: Change object field to subject field -->
                            <div class='field <?php if($errorSubject) echo 'error'; ?>'>
                                <label for='subject'><?php echo $subjectLabel ?></label>
                                <input type='text' name='subject' id='subject' class='subject' value='<?php echo esc_attr( $subject ); ?>' placeholder='<?php echo $subjectPlaceholder ?>' required>
                            </div>

                            <div class='field field-top <?php if($errorMsg) echo 'error'; ?>'>
                                <label for='message'><?php echo $msgLabel ?></label>
                                <textarea name='message' id='message' placeholder="<?php echo $msgPlaceholder ?>" required><?php echo esc_textarea( $msg ); ?></textarea>
                            </div>

                            <div class="g-recaptcha" data-sitekey="6Lcey2UUAAAAALlhYkUUiRNJVh-uOyeVtoq7Ab1G"></div>

                            <div class='hidden'>
                                <input type='url' name='url' id='url' value='<?php echo esc_url( $spamUrl ); ?>'>
                                <label for='url'>Merci de laisser ce champ vide.</label>
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