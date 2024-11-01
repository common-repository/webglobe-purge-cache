<?php
/*
 * Plugin Name: Webglobe Purge Cache
 * Description: Purges the Nginx cache when you publish or update a post or page.
 * Version: 1.2
 * Author: Webglobe a.s.
 * Requires at least: 6.0
 * Requires PHP:      7.2
 * Author URI:        https://webglobe.com/
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * License:           GPL v2 or later
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
function webglobe_purge_cache($post_id) {
    $urls = array();

    $link = get_permalink($post_id);
    $parse = wp_parse_url($link);
    $home_page_url = $parse['scheme'] . '://' . $parse['host'] . '/purge';
    if (isset($parse['scheme'], $parse['host'], $parse['path'])) {
        array_push($urls, $parse['scheme'] . '://' . $parse['host'] . '/purge' . $parse['path']);
    }

    $home_page = home_url();
    $parse_home = wp_parse_url($home_page);
    if (isset($parse_home['scheme'], $parse_home['host'], $parse_home['path'])) {
        $home_page_url = $parse_home['scheme'] . '://' . $parse_home['host'] . '/purge';
        if ($parse_home['path'] != '') {
            $home_page_url = $home_page_url . $parse_home['path'] . '/';
        } else {
            $home_page_url = $home_page_url . '/';
        }
        array_push($urls, $home_page_url);
    }

    if (get_option('show_on_front') == 'page') {
        $posts_page = get_option('page_for_posts');
        $posts_page_link = get_permalink($posts_page);
        $parse_posts = wp_parse_url($posts_page_link);
        if (isset($parse_posts['scheme'], $parse_posts['host'], $parse_posts['path'])) {
            array_push($urls, $parse_posts['scheme'] . '://' . $parse_posts['host'] . '/purge' . $parse_posts['path']);
        }
    }

    if (isset($home_page_url)) {
        array_push($urls, $home_page_url . '/feed/');
        array_push($urls, $home_page_url . '/comments/feed/');
    }

    foreach (array_unique($urls) as $uri) {
        wp_remote_get($uri,array('headers'=>array('Accept-Encoding' => 'gzip, deflate, br')));
    }

}
add_action('save_post', 'webglobe_purge_cache');

function webglobe_purge_cache_all() {   // purge all pages
    $urls = array();
    
    $all_post_ids = get_posts(array(
        'post_type' => 'any', // 'any' will include posts and pages
        'posts_per_page' => -1, // Retrieve all posts
        'fields' => 'ids', // Retrieve only post IDs
    ));
    foreach ($all_post_ids as $post_id) {
        $link = get_permalink($post_id);
        $parse = wp_parse_url($link);
        $home_page_url = $parse['scheme'] . '://' . $parse['host'] . '/purge';
        if (isset($parse['scheme'], $parse['host'], $parse['path'])) {
            array_push($urls, $parse['scheme'] . '://' . $parse['host'] . '/purge' . $parse['path']);
        }
    }    
    
    $home_page = home_url();
    $parse_home = wp_parse_url($home_page);
    if (isset($parse_home['scheme'], $parse_home['host'], $parse_home['path'])) {
        $home_page_url = $parse_home['scheme'] . '://' . $parse_home['host'] . '/purge';
        if ($parse_home['path'] != '') {
            $home_page_url = $home_page_url . $parse_home['path'] . '/';
        } else {
            $home_page_url = $home_page_url . '/';
        }
        array_push($urls, $home_page_url);
    }

    if (get_option('show_on_front') == 'page') {
        $posts_page = get_option('page_for_posts');
        $posts_page_link = get_permalink($posts_page);
        $parse_posts = wp_parse_url($posts_page_link);
        if (isset($parse_posts['scheme'], $parse_posts['host'], $parse_posts['path'])) {
            array_push($urls, $parse_posts['scheme'] . '://' . $parse_posts['host'] . '/purge' . $parse_posts['path']);
        }
    }

    if (isset($home_page_url)) {
        array_push($urls, $home_page_url . '/comments/feed/');
    }

    foreach (array_unique($urls) as $uri) {
        wp_remote_get($uri,array('headers'=>array('Accept-Encoding' => 'gzip, deflate, br')));
    }

}



function webglobe_purge_cache_menu() {
    add_action('wp_before_admin_bar_render', 'webglobe_purge_cache_button');
}


function webglobe_purge_cache_button() {
    global $wp_admin_bar;
    $wp_admin_bar->add_menu(array(
        'id' => 'cache-cleaner',
        'title' => 'Purge all cache',
        'href' => '#',
        'meta' => array(
            'onclick' => 'jQuery.post(ajaxurl, {action: "webglobe_purge_cache_cleaner"}, function(response) {console.log("yes"), alert("Cache purged successfull");});'
           // 'onclick' => 'showLoading(); jQuery.post(ajaxurl, {action: "webglobe_purge_cache_cleaner"}, function(response) { hideLoading(); alert(response); });',
        )
    ));
}
add_action('admin_menu', 'webglobe_purge_cache_menu');


function webglobe_purge_cache_cleaner() {
    webglobe_purge_cache_handle_purge_cache();
    echo 'Cache bola úspešne vymazaná!';
    die();
}
add_action('wp_ajax_webglobe_purge_cache_cleaner', 'webglobe_purge_cache_cleaner');


// Handle the AJAX request
function webglobe_purge_cache_handle_purge_cache() {
    // Check nonce and permissions
    error_log('Cache purged successfully.');
    // Get all post IDs
    webglobe_purge_cache_all();


}
add_action('wp_ajax_webglobe_purge_cache', 'webglobe_purge_cache_handle_purge_cache');














    


