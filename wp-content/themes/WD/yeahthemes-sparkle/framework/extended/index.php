<?php
// This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;
/**
 *Extended code
/*---------------------------------------------------------------------------------------------*/


/**
 * Initialize the metabox class.
 */
add_action( 'init', 'mdf_initialize_cmb_meta_boxes', 9999 );
function mdf_initialize_cmb_meta_boxes() {
	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'cmbf/init.php';
}
//require_once("cmbf/example-functions.php");
require_once("class.tgm-plugin-activation.php");
require_once("md-shortcodes/md-shortcodes.php");
//require_once('MCAPI.class.php');