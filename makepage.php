<?php
/**
 * PHP Class: MakePage
 *
 * Generates a WordPress page for a given permalink without causing a 404 error
 * Based on "Fake Page" code by Scott Sherrill-Mix http://scott.sherrillmix.com/blog/blogger/creating-a-better-fake-post-with-a-wordpress-plugin
 
 * By Mark Hughes, http://theWebalyst.com
 * Version: 0.1 (based on Fake Page code v1.1)
 */

class MakePage
{
	/**
	 * The slug for the fake post or page.  This determines the URL of the page that this
	 * plugin will serve up. For example:
	 * 
	 * http://yoursite.com/the-make-page-permalink or http://yoursite.com/?page_id=the-make-page-permalink
	 * @var string
	 */
	private $page_slug = 'the-make-page-permalink';
	
	/**
	 * The title for your fake post.
	 * @var string
	 */
	private $page_title = 'The "Make Page" Page Title';
	
	/**
	 * Generate a page or a post
	 * @var bool
	 */
	private $is_page = true;
	
	/**
	 * The content
	 * @var string
	 */
	private $page_content = "This page was created by MakePage, but was left with this default content.";
	
	/**
	 * Allow pings?
	 * @var string
	 */
	private $ping_status = 'open';
	
	/**
	 * Class constructor
	 */
	function MakePage($slug, $title, $ping, $page=true)
	{
		$this->page_slug = $slug;
		$this->page_title= $title;
		$this->ping_status = $ping;
		$this->is_page = $page;
		
		/**
		 * After WordPress has looked for posts this hook checks
		 * if the requested url matches $page_slug.
		 */
		add_filter('the_posts',array(&$this,'detectMakePage'));
	}

	function getSlug(){ return $this->page_slug; }
	
	function setContent($content)
	{
		$this->page_content = $content;
	}
	
	function getContent()
	{
		return $this->page_content;
	}

	/**
	 * Automatically generate the page (or post)
	 */
	function generatePage()
	{
		/**
		 * Generate the page/post automatically.
		 */		 
		
		/**
		 * Create a fake post.
		 */
		$post = new stdClass;
		
		/**
		 * The author ID for the post.  Usually 1 is the sys admin.  Your
		 * plugin can find out the real author ID without any trouble.
		 */
		$post->post_author = 1;
		
		/**
		 * The safe name for the post.  This is the post slug.
		 */
		$post->post_name = $this->page_slug;
		
		/**
		 * Not sure if this is even important.  But gonna fill it up anyway.
		 */
		$post->guid = get_bloginfo('wpurl') . '/' . $this->page_slug;
		
		
		/**
		 * The title of the page.
		 */
		$post->post_title = $this->page_title;
		
		/**
		 * This is the content of the post.  This is where the output of
		 * your plugin should go.  Just store the output from all your
		 * plugin function calls, and put the output into this var.
		 */
		$post->post_content = $this->getContent();
		
		/**
		 * Fake post ID to prevent WP from trying to show comments for
		 * a post that doesn't really exist.
		 */
		$post->ID = -1;
		
		/**
		 * Static means a page, not a post.
		 */
		$post->post_status = 'static';
		
		/**
		 * Turning off comments for the post.
		 */
		$post->comment_status = 'closed';
		
		/**
		 * Let people ping the post?  Probably doesn't matter since
		 * comments are turned off, so not sure if WP would even
		 * show the pings.
		 */
		$post->ping_status = $this->ping_status;
		
		$post->comment_count = 0;
		
		/**
		 * You can pretty much fill these up with anything you want.  The
		 * current date is fine.  It's a fake post right?  Maybe the date
		 * the plugin was activated?
		 */
		$post->post_date = current_time('mysql');
		$post->post_date_gmt = current_time('mysql', 1);

		return($post);		
	}
	
	function detectMakePage($posts){
		global $wp;
		global $wp_query;
		/**
		 * Check if the requested page matches our target 
		 */
		if (strtolower($wp->request) == strtolower($this->page_slug) || $wp->query_vars['page_id'] == $this->page_slug){
			// Add the page / post
			$posts = NULL;
			$posts[] = $this->generatePage();
		
			/**
			 * Trick wp_query into thinking this is a page (necessary for wp_title() at least)
			 * Not sure if it's cheating or not to modify global variables in a filter 
			 * but it appears to work and the codex doesn't directly say not to.
			 */
			$wp_query->is_page = true;
			//Not sure if this one is necessary but might as well set it like a true page
			$wp_query->is_singular = true;
			$wp_query->is_home = false;
			$wp_query->is_archive = false;
			$wp_query->is_category = false;
			//Longer permalink structures may not match the fake post slug and cause a 404 error so we catch the error here
			unset($wp_query->query["error"]);
			$wp_query->query_vars["error"]="";
			$wp_query->is_404 = false;
			
			
		}
		
		return $posts;
	}
}
?>