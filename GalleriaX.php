<?php
/**
 * Plugin Name: GalleriaX
 * Description: A modern and responsive gallery plugin for WordPress, perfect for photographers and bloggers.
 * Version: 1.0.0
 * Author: Mayada Elnady
 */

if(!defined('WPINC')){
    exit("do not access this file directly");
}

define('GalleriaX_version', time());
define('GalleriaX_file', __FILE__);
define('GalleriaX_directory', dirname(__FILE__));
define('GalleriaX_url', plugins_url('', __FILE__));

//var_dump(GalleriaX_url);

if(!class_exists('GalleriaX')){
    include_once GalleriaX_directory .'/includes/GalleriaX_class.php';
}