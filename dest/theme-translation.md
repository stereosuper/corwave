# <center>Theme translation</center>

## Steps

First things first, install Loco Translate plugin on your WordPress install.

Select you theme and then go to the _"Advanced"_ tab. In this tab you'll have to set the _Project name_, _Text domain_, _File prefix_, and _Template file_.

Then return to the _Overview_ tab and click on _Create template_. In here you'll have to select the language that you wanna translate you theme in and select the _Author_ path.

Once you're done with Loco's configuration go to your _function.php_.

In the _function.php_

```php
/*----------------------------*/
/* I18n
/*----------------------------*/

add_action( 'after_setup_theme', 'my_language_translation_setup' );
function my_language_translation_setup(){
    load_theme_textdomain( 'your_domain_name', get_template_directory() . '/languages' );

    $locale = get_locale();
    $locale_file = get_template_directory() . "/languages/$locale.php";

    if ( is_readable( $locale_file ) ) {
        require_once( $locale_file );
    }
}
```

Last step:

-   **TRANSLATE YOUR THEME HARDCODED STRINGS**

Made with ❤️ by Stéréosuper ✌️👍
