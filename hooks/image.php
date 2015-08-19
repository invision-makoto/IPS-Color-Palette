//<?php

\IPS\IPS::$PSR0Namespaces['ColorThief'] = \IPS\ROOT_PATH . '/applications/colorpalette/sources/3rd_party/ColorThief';
\IPS\IPS::$PSR0Namespaces['Coma'] = \IPS\ROOT_PATH . '/applications/colorpalette/sources/3rd_party/Coma';
use ColorThief\ColorThief;
use Coma\ColorDistance;
use Coma\sRGB;

class colorpalette_hook_image extends _HOOK_CLASS_
{
	/**
	 * Hook into the form submission to get our color palette
	 *
	 * @param	array	$values	Values from form
	 * @return	void
	 */
	public function processForm( $values )
	{
		$image = \IPS\File::get( 'gallery_Images', $values['imageLocation'] );
		$this->buildPalette( $image );

		return call_user_func_array( 'parent::processForm', func_get_args() );
	}

	/**
	 * Regenerate the color palettes for uploaded images
	 *
	 * @param	\IPS\File|NULL	$file	Base file to create from (if not supplied it will be found automatically)
	 * @return	void
	 */
	public function buildPalette($file = null)
	{
		$file = $file ? $file->contents() : \IPS\File::get( 'gallery_Images', $this->original_file_name )->contents();

		/* Get the dominant color */
		if ( $domColor = ColorThief::getColor( $file, \IPS\Settings::i()->cpalette_accuracy ?: 10 ) )
		{
			$sRGB = new sRGB( $domColor[0], $domColor[1], $domColor[2] );
			$HSL  = \IPS\colorpalette\Colors::rgbToHsl( $domColor[0], $domColor[1], $domColor[2] );
			$this->dominant_color = \strtolower( $sRGB->toHex() );  // Because lowercase is more presentable

			/* Get our closest matching color title */
			$colorDistance = new ColorDistance();
			$closest['name'] = null;
			$closest['distance'] = 0;

			$colors = \IPS\colorpalette\Colors::all();
			foreach ( $colors as $color )
			{
				$sRGB2 = new sRGB( $color['hex'] );
				$distance = $colorDistance->cie94( $sRGB, $sRGB2 );

				if ( ( $distance < $closest['distance'] ) or !$closest['distance'] )
				{
					$closest['name'] = $color['name'];
					$closest['distance'] = $distance;
				}
			}

			$this->dominant_color_name = $closest['name'];
			$this->dominant_color_lightness = $HSL[2];
		}

		/* Get the full color palette */
		if ( $colorPalette = array_slice( ColorThief::getPalette( $file, 10,
			\IPS\Settings::i()->cpalette_accuracy ?: 10 ), 0, 9 ) )  // array slice in case function is buggy (+/- 2)
		{
			/* Convert RGB arrays to HEX strings */
			$hexPalette = array();

			foreach ( $colorPalette as $color )
			{
				$sRGB = new sRGB( $color[0], $color[1], $color[2] );
				$hexPalette[] = \strtolower( $sRGB->toHex() );
			}

			$this->color_palette = $hexPalette;
		}
	}

	/**
	 * Set the images color palette
	 *
	 * @param	array|string	$value	Either an array or CSV string of HEX colors
	 * @return	void
	 */
	public function set_color_palette($value)
	{
		/* If we were given an array of colors, implode it into a CSV string now */
		if ( is_array($value) ) {
			$value = implode( ',', $value );
		}

		$this->_data['color_palette'] = $value;
	}

	/**
	 * Get the images color palette
	 *
	 * @return	array
	 */
	public function get_color_palette()
	{
		return $this->_data['color_palette'] ? explode( ',', $this->_data['color_palette'] ) : array();
	}
}