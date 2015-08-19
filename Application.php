<?php
/**
 * @brief		Color Palette Application Class
 * @author		<a href='https://www.Makoto.io'>Makoto Fujimoto</a>
 * @copyright	(c) 2015 Makoto Fujimoto
 * @package		IPS Social Suite
 * @subpackage	Color Palette
 * @since		18 Aug 2015
 * @version		1.0.0
 */
 
namespace IPS\colorpalette;

/**
 * Color Palette Application Class
 */
class _Application extends \IPS\Application
{
	/**
	 * Application icon
	 *
	 * @note	Return the class for the icon (e.g. 'globe')
	 * @return	string|null
	 */
	protected function get__icon()
	{
		return 'paint-brush';
	}
}