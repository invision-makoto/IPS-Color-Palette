//<?php

class colorpalette_hook_colorPalette extends _HOOK_CLASS_
{

/* !Hook Data - DO NOT REMOVE */
public static function hookData() {
 return array_merge_recursive( array (
  'imageInfo' => 
  array (
    0 => 
    array (
      'selector' => '#elGalleryImageStats > [data-role=\'imageStats\']',
      'type' => 'add_inside_end',
      'content' => '{{if $image->color_palette}}
	{{if settings.cpalette_show_dominant and $image->dominant_color}}
		<h2 class="ipsType_minorHeading">{lang="dominant_color"}</h2>
		<div class="swatches">
			<div class="swatch swatch_tiny swatch_dominant {{if $image->dominant_color_lightness >= 85}}swatch_light{{else}}swatch_dark{{endif}}" style="background-color: {$image->dominant_color}">
				{{if $image->dominant_color_name}}
					{lang="cpalette_color_{$image->dominant_color_name}"}
				{{endif}}
			</div>
		</div>
		<br>
	{{endif}}
	{{if settings.cpalette_show_palette and $image->color_palette}}
		<h2 class="ipsType_minorHeading">{lang="color_palette"}</h2>
		<div class="swatches">
			{{foreach $image->color_palette as $color}}
				<div class="swatch swatch_tiny" style="background-color: {$color}"></div>
			{{endforeach}}
		</div>
	{{endif}}
	{{if (settings.cpalette_show_dominant and $image->dominant_color) or (settings.cpalette_show_palette and $image->color_palette)}}
		<hr class=\'ipsHr\'>
	{{endif}}
{{endif}}',
    ),
  ),
), parent::hookData() );
}
/* End Hook Data */




}