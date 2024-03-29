<?php
/**
 * @brief		Uninstall callback
 * @author		<a href='http://www.invisionpower.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) 2001 - SVN_YYYY Invision Power Services, Inc.
 * @license		http://www.invisionpower.com/legal/standards/
 * @package		IPS Social Suite
 * @subpackage	Color Palette
 * @since		19 Aug 2015
 * @version		SVN_VERSION_NUMBER
 */

namespace IPS\colorpalette\extensions\core\Uninstall;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Uninstall callback
 */
class _Uninstall
{
	/**
	 * Code to execute before the application has been uninstalled
	 *
	 * @param	string	$application	Application directory
	 * @return	array
	 */
	public function preUninstall( $application )
	{
	}

	/**
	 * Code to execute after the application has been uninstalled
	 *
	 * @param	string	$application	Application directory
	 * @return	array
	 */
	public function postUninstall( $application )
	{
		$galColumns = array(
			'image_dominant_color', 'image_dominant_color_name', 'image_dominant_color_lightness', 'image_color_palette'
		);

		foreach ( $galColumns as $column )
		{
			if ( \IPS\Db::i()->checkForColumn( 'gallery_images', $column ) ) {
				\IPS\Db::i()->dropColumn( 'gallery_images', $column );
			}
		}
	}
}