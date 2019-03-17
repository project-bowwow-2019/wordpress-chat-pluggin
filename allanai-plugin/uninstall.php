<?php

/**
*Trigger this file on Plugin uninstall
*
*@package Allan AI Plugin
*/
// If uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
  exit();

delete_option( 'allanai_plugin_email' );
