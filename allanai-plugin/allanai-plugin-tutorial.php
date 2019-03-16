<?php
/**
*@package AllanAIPlugin
*/

/*
Plugin Name: AllanAI Plugin
Plugin URI: http://allanai.io/
Description: This is the Allan AI chat plugin for Wordpress
Version: 1.0.0
Author: Allan AI
Author URI: http://allanai.io/
License: GPLv2 or later
Text Domain: allanai-plugin
*/

//if this file is called directly, abort
if( ! defined('ABSPATH')){
  die;
}

class AllanAIPlugin{
  function __construct(){

  }

  function activate(){
    flush_write_rules();
  }

  function deactivate(){

  }

  funcation uninstall(){

  }
}

if( class_exists('AllanAIPlugin')){
  $allanaiPlugin = new AllanAIPlugin();
}

//activation
register_activation_hook( __FILE__, array($allanaiPlugin, 'activate'));

//deactivation
register_activation_hook( __FILE__, array($allanaiPlugin, 'deactivate'));

//uninstall
