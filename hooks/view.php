//<?php

class colorpalette_hook_view extends _HOOK_CLASS_
{
	/**
	 * Add our custom CSS to the view page output
	 *
	 * @return	void
	 * @link	http://www.videojs.com/projects/mimes.html
	 * @note	Only HTML5 and some flash-based video formats will work. MP4, webm and ogg are relatively safe bets but anything else isn't likely to play correctly.
	 *	The above link will allow you to check what is supported in the browser you are using.
	 * @note	As of RC1 we fall back to a generic 'embed' for non-standard formats for better upgrade compatibility...need to look into transcoding in the future
	 */
	protected function manage()
	{
		\IPS\Output::i()->cssFiles = array_merge( \IPS\Output::i()->cssFiles, \IPS\Theme::i()->css( 'color_palette.css', 'colorpalette' ) );
		return call_user_func_array( 'parent::manage', func_get_args() );
	}

}