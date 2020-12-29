<?php
/**
@package WPGrabber
Plugin Name: WPGrabber
Plugin URI: http://wpgrabber.biz
Description: WordPess Grabber plugin для автонаполнения вашего сайта контентом
Version: 4.9.51 (27.04.2018)
Author: GrabTeam (close) - new WPGrabber_Biz
Author URI: http://wpgrabber.biz
*/
  if (defined('WPGRABBER_VERSION')) {
    die('На сайте активирован плагин WPGrabber версии '.WPGRABBER_VERSION.'. Пожалуйста, деактивируйте его перед активацией данного плагина.');
  }
  define('WPGRABBER_VERSION', '4.9.51');

  define('WPGRABBER_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
  define('WPGRABBER_PLUGIN_URL', plugin_dir_url( __FILE__ ));
  define('WPGRABBER_PLUGIN_FILE', __FILE__);

  require WPGRABBER_PLUGIN_DIR.'init.php';
?>