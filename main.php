<?php
/*
Plugin Name: theWebalyst EU Cookie Law Plugin
Plugin URI: http://theWebalyst.com/wordpress-plugins#id=wpeu
Description: This plugin is enough to ensure most websites comply with the EU privacy, or cookie, directive (which applies to non-EU and EU websites). Covers Google Analytics (more to follow), and measures to preserve your SEO performance.
Version: 1.11
Author: http://theWebalyst.com/wordpress-plugins#id=wpeu
Author URI: http://theWebalyst.com
License:	Copyright (c) 2012 theWebalyst.com
			This program is distributed in the hope that it will 
			be useful, but WITHOUT ANY WARRANTY; without even the 
			implied warranty of MERCHANTABILITY or FITNESS FOR A 
			PARTICULAR PURPOSE.

	
Prefix: twcookies_

TODO:
*/

require_once 'makepage.php';

/**************************************************************
 * Create an object to generate a WordPress page automatically
 */
$privacy_page = new MakePage('website-privacy-cookies','Website Privacy & Cookies','open');

$privacy_page->setContent('<div>
<p>
This page describes how we treat the information provided by visitors, what other information we gather and how we use it, why we sometimes need to store "cookies" and how to prevent this.
</p>
<p>
In common with almost all professionally run websites, this website logs the IP address of each visitor in order to keep it running reliably. This is also essential for protecting the website and its visitors from malicious attacks, including infection with malware.
</p>
<p>
</div>
</p>
<p>
This website provides information as a service to vistors such as yourself, and to do this reliably and efficiently, it sometimes places small amounts of information on your computer or device (e.g. mobile phone). This includes small files known as cookies.
</p>
<p>
The cookies stored by this website cannot be used to identify you personally.
</p>
<h2>How Cookies Are Used</h2>
<p>
We use cookies to understand what pages and information visitors find useful, and to detect problems such as broken links, or pages which are taking a long time to load.
</p>
<p>
We sometimes use cookies to remember a choice you make on one page, when you have moved to another page if that information can be used to make the website work better. For example:
</p>
<ul>
	<li>avoiding the need to ask for the same information several times during a session (e.g. when filling in forms), or</li>
	<li>remembering that you have logged in, so that you don'."'".'t have to re-enter your username and password on every page.</li>
</ul>
<h2>Cookies for Google Analytics</h2>
<p>
We use analytics to measure how many visitors are using the website, which pages interest them and so on, and this involves storing the following cookies:
</p>
<ul>
Name: _utma
Typical content: randomly generated number
Expires: 2 years
</ul>
<ul>
Name: _utmb
Typical content: randomly generated number
Expires: 30 minutes
</ul>
<ul>
Name: _utmc
Typical content: randomly generated number
Expires: when user exits browser
</ul>
<ul>
Name: _utmz
Typical content: randomly generated number + info on how the site was reached (e.g. directly or via a link, organic search or paid search)
Expires: 6 months
</ul>
<p>
For more information  <a title="opens in a new window" href="http://www.google.com/analytics/" target="_blank">Google Analytics </a>website.
<h2>Disabling Cookies</h2>
You can prevent the setting of cookies by adjusting the settings on your browser (see your browser Help for how to do this). Be aware that disabling cookies will affect the functionality of this and many other websites that you visit.
<h2>Personal data</h2>
Personal information that you submit to us through this website will only be used for the purposes we solicited it. For example, to respond to an enquiry, and will not be used for any other purpose without your consent. We will never pass it on to any third party without your consent, unless legally required to do so.
<h2>Other Websites</h2>
The information on this page applies only to <strong>this website</strong> and not to other sites linked to from these pages.');

/////////////////////////// PLUGIN CUSTOMISATION //////////////////////////
/*
 * Customise how the plugin appears in the UI
 */
$twcookies_sname = "tW EU Cookies";
$twcookies_lname = "theWebalyst UK/EU Cookie Law";
$twcookies_plugin_id = "thewebalyst-privacyandcookies-plugin";

$twcookies_link_style = "font-size:10pt;text-align:right;float:right;clear:both;margin:8px;";
$twcookies_privacy_link_text = "Website Privacy & Cookies";

add_action('wp_head','twcookies_head_inserts');
add_action('wp_footer','twcookies_footer_inserts');
add_action('admin_menu', 'twcookies_plugin_menu');

$twcookies_sname = "tW EU Cookies";
$twcookies_lname = "theWebalyst EU Cookies";
$twcookies_plugin_id = "thewebalyst-twtup-plugin";

/***************************** Page Header Inserts ****************************
 * 
 */
function twcookies_head_inserts(){
	global $twcookies_lname;
	global $privacy_page;
	
	$slug = basename( get_permalink() );
	if ( $slug == $privacy_page->getSlug()){
		echo "
<!-- $twcookies_lname: Prevent indexing of the Privacy & Cookies Page -->
<meta name='robots' content='noindex'>

";
	}
}

/***************************** Page Footer Inserts ****************************
 * 
 */
function twcookies_footer_inserts(){
	global $twcookies_sname, $twcookies_lname;
	global $twcookies_link_style, $twcookies_privacy_page_url, $twcookies_privacy_link_text;
	global $privacy_page;
	
	$blog_url = get_bloginfo('url');

	echo "
<!-- $twcookies_sname: START footer insert -->
<div id='cookie-footer-link' style='$twcookies_link_style'><a href='$blog_url" . "/" . $privacy_page->getSlug() . "' rel='nofollow'>$twcookies_privacy_link_text</a>
</div>
<!-- $twcookies_sname: END footer insert -->\n
	";
}

/***************************************************************/
/* Social Media Sharing Shortcodes */

add_shortcode('someshortcode', 'twcookies_someshortcode');

function twcookies_someshortcode($atts, $content = null, $shortcode = "" ){
	
	$defaults = array(
		// Defaults
		'prefix' => 'Liking: ',
		'content' => get_the_title(),
		'tooltip' => 'Tweet a link to this page',
		'imageurl' => get_bloginfo('url') . '/webalyst.com/twitter-tweet-small.png',
		'imagestyle' => 'border: 0; padding:0;'
	);
		
	// Predefined shortcode parameters (parse and offer defaults)
	extract(shortcode_atts($defaults, $atts));

	return '<a rel="external nofollow" 
				target="_blank" 
				href="http://twitter.com/share?text=' . $prefix . $content . '" 
				title="' . $tooltip . '">
			<img style="' . $imagestyle . '" 
				src="' . $imageurl . '"
				border="0" 
				alt="' . $tooltip . '" />
			</a>';
}


/***************************************************************/
// Simple SEO per Post based on tags

/*
function tags_to_keywords(){
    global $post;
    if(is_single() || is_page()){
        $tags = wp_get_post_tags($post->ID);
    }
}

function tags_to_keywords(){
    global $post;
    if(is_single() || is_page()){
        $tags = wp_get_post_tags($post->ID);
        foreach($tags as $tag){
            $tag_array[] = $tag->name;
        }
        $tag_string = implode(', ',$tag_array);
        if($tag_string !== ''){
            echo "<meta name='keywords' content='".$tag_string."' />rn";
        }
    }
}

add_action('wp_head','tags_to_keywords');

// add except as description
function excerpt_to_description(){
    global $post;
    if(is_single() || is_page()){
        $all_post_content = wp_get_single_post($post->ID);
        $excerpt = substr($all_post_content->post_content, 0, 100).' [...]';
        echo "<meta name='description' content='".$excerpt."' />rn";
    }
    else{
        echo "<meta name='description' content='".get_bloginfo('description')."' />rn";
    }
}

add_action('wp_head','excerpt_to_description');
***************************************************************/

/**************************************************************
 * Plugin Settings UI
 * 
 */

function twcookies_plugin_menu() {
	global $twcookies_sname;
	global $twcookies_lname;
	global $twcookies_plugin_id;
	
//  add_options_page($twcookies_sname . " Options", $twcookies_sname, 'manage_options', $twcookies_plugin_id, 'twcookies_plugin_options');
}

function twcookies_plugin_options() {
	global $twcookies_lname;
	
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	// The following options code gives an example form with nonce
	// protection, and an example action link with nonce protection.
	// (I.e. customise the 'plugin-name-action_' strings etc.)
	//
	// This sample code needs customising, and for you to include
	// nonce checking code in the backend/implementation action
	// similar to:
	//	<?php check_admin_referer('plugin-name-action_' . $your_object); ? >
	//
	// Details here: http://markjaquith.wordpress.com/2006/06/02/wordpress-203-nonces/
	//
	// 
   
	$form_object_name = "form_features";	// Differentiate per form (e.g. "advanced_settings")
  
echo "
<div class='wrap'>
<p>$twcookies_lname plugin options form goes here.</p>

<!-- Form with nonce protection -->
<form>
<?php 
if ( function_exists('wp_nonce_field') )
	wp_nonce_field('plugin-name-action_' . $form_object_name);
?>
</form>

<!-- Action link with nonce protection -->
<?php
$link = 'your-url.php';
$link = ( function_exists('wp_nonce_url') ) ? wp_nonce_url($link, 'plugin-name-action_' . $your_object) : $link;
?>

<a href='<?php echo $link; ?>'>Action Link</a>

</div>";

}
	
?>
