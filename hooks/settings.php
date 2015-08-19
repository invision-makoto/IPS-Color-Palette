//<?php

class colorpalette_hook_settings extends _HOOK_CLASS_
{

	/**
	 * Manage settings
	 *
	 * @return	void
	 */
	protected function manage()
	{
		$return = call_user_func_array( 'parent::manage', func_get_args() );

		/* Fetch our settings form that should be set as the output now */
		$form = \IPS\Output::i()->output;

		/* Make sure we're really working with a form instance */
		if ( $form instanceof \IPS\Helpers\Form )
		{
			$form->addHeader( 'cpalette_settings_header' );
			$form->add( new \IPS\Helpers\Form\YesNo( 'cpalette_show_palette', \IPS\Settings::i()->cpalette_show_palette, FALSE ) );
			$form->add( new \IPS\Helpers\Form\YesNo( 'cpalette_show_dominant', \IPS\Settings::i()->cpalette_show_dominant, FALSE ) );
			$form->add( new \IPS\Helpers\Form\Select( 'cpalette_accuracy', \IPS\Settings::i()->cpalette_accuracy, TRUE, array(
				'options' => array( 5 => 'Highest', 10 => 'High', 18 => 'Medium', 30 => 'Low', 75 => 'Lowest' )
			) ) );

			if ( $values = $form->values() )
			{
				$form->saveAsSettings( array(
					'cpalette_show_dominant'	=> $values['cpalette_show_dominant'],
					'cpalette_accuracy'			=> $values['cpalette_accuracy']
				) );
			}

			\IPS\Output::i()->output = $form;
		}
		else
		{
			/* We shouldn't get here, but just as a safety precaution in case things change in the future */
			\IPS\Log::i( \LOG_ERR )->write( 'Unable to display Color Palette settings, output is not a valid ' .
				'\IPS\Helpers\Form instance - please upgrade the Color Palette application' );
		}

		return $return;
	}


	/**
	 * Rebuild gallery image palettes
	 *
	 * @return	void
	 */
	protected function rebuildPalettes()
	{
		$perGo = 5;

		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('gallery_rebuildimages');
		\IPS\Output::i()->output = new \IPS\Helpers\MultipleRedirect( \IPS\Http\Url::internal( 'app=gallery&module=gallery&controller=settings&do=rebuildPalettes' ), function( $doneSoFar ) use( $perGo )
		{
			$select = \IPS\Db::i()->select( '*', 'gallery_images', array( 'image_media=?', 0 ), 'image_id', array( $doneSoFar, $perGo ), NULL, NULL, \IPS\Db::SELECT_SQL_CALC_FOUND_ROWS );
			$total	= $select->count( TRUE );

			if ( !$select->count() )
			{
				return NULL;
			}

			foreach ( $select as $row )
			{
				try
				{
					$image	= \IPS\gallery\Image::constructFromData( $row );
					$image->buildPalette();
					$image->save();
				}
				catch ( \Exception $e ) {}
			}

			$doneSoFar += $perGo;
			return array( $doneSoFar, \IPS\Member::loggedIn()->language()->addToStack('rebuilding'), ( 100 * $doneSoFar ) / $total );

		}, function()
		{
			\IPS\Output::i()->redirect( \IPS\Http\Url::internal( 'app=gallery&module=gallery&controller=settings' ), 'completed' );
		} );
	}

}