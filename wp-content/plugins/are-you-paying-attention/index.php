<?php

/*
	Plugin Name: Are You Paying Attention Quiz
	Description: Give your readers a multiple choice question.
	Version: 1.0
	Author: Ara
*/

if( ! defined('ABSPATH')) exit;  //Exit if accessed directly

class AreYouPayingAttention{
	function __construct(){
		add_action('init',array($this, 'adminAssets'));
	}
	// register a block type from within PHP(dynamic block)
	function adminAssets(){
		// enqueue_block_editor_assets -> wp_enqueue_script - load file
		// wp_register_script - instead of loading JS file directly, register a script with a name(first param), and reference it

		// wp_register_script('ournewblocktype', plugin_dir_url(__FILE__) . '/build/index.js', array('wp-blocks','wp-element', 'wp-editor'));
		// wp_register_style('quizeditcss', plugin_dir_url(__FILE__) . '/build/index.css');
		register_block_type(__DIR__, array(
			// 'editor_script' => 'ournewblocktype',
			// 'editor_style' => 'quizeditcss',
			'render_callback' => array($this, 'theHTML')
		));
	}

	function theHTML($attributes){
		// load script file here(only when frontend-html is being rendered)
		if(!is_admin()){
			// wp-element is WP version react 
			wp_enqueue_script('attentionFrontend', plugin_dir_url(__FILE__).'build/frontend.js',array('wp-element'));
			wp_enqueue_style('attentionFrontendStyle', plugin_dir_url(__FILE__).'build/frontend.css');
		}

		// output buffer (things between start and get_clean are going to be returned)
		// return '<p>Today the sky is ' . esc_html($attributes['skyColor']) . ' and the grass is '. esc_html($attributes['grassColor']) .'.</p>';
		ob_start(); ?>
		<div class="paying-attention-update-me"><pre style="display: none;"><?php echo wp_json_encode($attributes); ?></pre></div>
		<?php return ob_get_clean();
	}
}

$areYouPayingAttention = new AreYouPayingAttention();