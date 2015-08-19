//<?php

class colorpalette_hook_colorPalette extends _HOOK_CLASS_
{

/**
 * The number of hoops we had to jump through here just to keep things properly ordered is a bit ridiculous
 */

/* !Hook Data - DO NOT REMOVE */
public static function hookData() {
 return array_merge_recursive( array (
  'imageInfo' => 
  array (
    0 => 
    array (
      'selector' => '#elGalleryImageStats > div[data-role=\'imageStats\'] > div:has(\'ul.ipsList_inline > li > i.fa-comments\')',
      'type' => 'add_after',
      'content' => '{{if (settings.cpalette_show_dominant and $image->dominant_color) or (settings.cpalette_show_palette and $image->color_palette)}}
	<hr class=\'ipsHr\'>
{{endif}}
{template="colorPalette" app="colorpalette" group="gallery" params="$image"}',
    ),
    1 => 
    array (
      'selector' => '#elGalleryImageStats > div[data-role=\'imageStats\'] > div.ipsType_center:first',
      'type' => 'add_after',
      'content' => '{{if !($image->directContainer() instanceof \IPS\gallery\Album)}}
    {{if (settings.cpalette_show_dominant and $image->dominant_color) or (settings.cpalette_show_palette and $image->color_palette)}}
        <hr class=\'ipsHr\'>
    {{endif}}
	{template="colorPalette" app="colorpalette" group="gallery" params="$image"}
{{endif}}',
    ),
    2 => 
    array (
      'selector' => '#elGalleryImageStats > div[data-role=\'imageStats\']',
      'type' => 'add_inside_start',
      'content' => '{{if !($image->directContainer() instanceof \IPS\gallery\Album) and !$image->container()->allow_rating}}
	{template="colorPalette" app="colorpalette" group="gallery" params="$image"}
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