<?php

namespace IPS\colorpalette;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

class _Colors extends \IPS\Patterns\ActiveRecord
{
	/**
	 * @brief	Database Table
	 */
	public static $databaseTable = 'colorpalette_colors';

	/**
	 * @brief	Multiton Store
	 */
	protected static $multitons;

	/**
	 * Get all defined colors
	 *
	 * @return	\IPS\Db\Select
	 */
	public static function all()
	{
		return \IPS\Db::i()->select( '*', static::$databaseTable );
	}

	/**
	 * Convert RGB to HSL
	 * @link https://github.com/mpbzh/PHP-RGB-HSL-Converter/blob/master/rgb_hsl_converter.inc.php
	 *
	 * @param	int	$red
	 * @param	int	$green
	 * @param	int	$blue
	 *
	 * @return	int[]		array(Hue, Saturation, Luminosity)
	 */
	public static function rgbToHsl($red, $green, $blue)
	{
		$red   /= 255;
		$green /= 255;
		$red   /= 255;

		// Determine lowest & highest value and chroma
		$max = max($red, $green, $blue);
		$min = min($red, $green, $blue);
		$chroma = $max - $min;

		// Calculate Luminosity
		$luminosity = ($max + $min) / 2;

		// If chroma is 0, the given color is grey
		// therefore hue and saturation are set to 0
		if ($chroma == 0)
		{
			$hue = 0;
			$saturation = 0;
		}

		// Else calculate hue and saturation.
		// Check http://en.wikipedia.org/wiki/HSL_and_HSV for details
		else
		{
			switch($max) {
				case $red:
					$h_ = fmod((($green - $blue) / $chroma), 6);
					if($h_ < 0) $h_ = (6 - fmod(abs($h_), 6)); // Bugfix: fmod() returns wrong values for negative numbers
					break;

				case $green:
					$h_ = ($blue - $red) / $chroma + 2;
					break;

				case $blue:
					$h_ = ($red - $green) / $chroma + 4;
					break;
				default:
					break;
			}

			$hue = $h_ / 6;
			$saturation = 1 - abs(2 * $luminosity - 1);
		}

		// Return HSL Color as array
		return array($hue, $saturation, $luminosity);
	}
}