<?php
/**
 * I use this file to automatically generate js code for the theme customizer, booh ya!
 */

if ( is_customize_preview() || is_admin() ) {
	return;
}

$return = 'styling';

/*-----------------------------------------------------------------------------------*/
/*	- Styling Options
/*-----------------------------------------------------------------------------------*/

if ( 'styling' == $return ) {
	$obj			= new WPEX_Theme_Customizer_Styling();
	$color_options	= $obj->wpex_color_options();

	// Loop through color options and add a theme customizer setting for it
	foreach( $color_options as $option ) {
		$type = isset( $option['type'] ) ? $option['type'] : '';
		$style = isset( $option['style'] ) ? $option['style'] : '';
		$transport = isset( $option['transport'] ) ? $option['transport'] : '';
		// Only for postMessage
		if ( 'refresh' != $transport ) {
			if ( 'text' != $type ) {
				if( 'background-color' == $style || 'background' == $style ) { ?>
				wp.customize( '<?php echo $option['id']; ?>', function( value ) {
					value.bind( function( newval ) {
						if ( newval ) {
							$( '<?php echo $option['element']; ?>' ).css('background-color', newval );
						} else {
							$( '<?php echo $option['element']; ?>' ).css('background-color', '');
						}
					} );
				} );
				<?php
				} elseif( 'color' == $style ) { ?>
				wp.customize( '<?php echo $option['id']; ?>', function( value ) {
					value.bind( function( newval ) {
						if ( newval ) {
							$( '<?php echo $option['element']; ?>' ).css('color', newval );
						} else {
							$( '<?php echo $option['element']; ?>' ).css('color', '');
						}
					} );
				} );
				<?php
				} elseif( 'border-color' == $style ) { ?>
				wp.customize( '<?php echo $option['id']; ?>', function( value ) {
					value.bind( function( newval ) {
						if ( newval ) {
							$( '<?php echo $option['element']; ?>' ).css('border-color', newval );
						} else {
							$( '<?php echo $option['element']; ?>' ).css('border-color', '');
						}
					} );
				} );
				<?php
				} elseif( 'border-top-color' == $style ) { ?>
				wp.customize( '<?php echo $option['id']; ?>', function( value ) {
					value.bind( function( newval ) {
						if ( newval ) {
							$( '<?php echo $option['element']; ?>' ).css('border-top-color', newval );
						} else {
							$( '<?php echo $option['element']; ?>' ).css('border-top-color', '');
						}
					} );
				} );
				<?php
				} elseif( 'border-bottom-color' == $style ) { ?>
				wp.customize( '<?php echo $option['id']; ?>', function( value ) {
					value.bind( function( newval ) {
						if ( newval ) {
							$( '<?php echo $option['element']; ?>' ).css('border-bottom-color', newval );
						} else {
							$( '<?php echo $option['element']; ?>' ).css('border-bottom-color', '');
						}
					} );
				} );
				<?php }
			}
		}
	}
}
/*-----------------------------------------------------------------------------------*/
/*	- Typography Options
/*-----------------------------------------------------------------------------------*/
if ( 'typography' == $return ) {
	$obj = new WPEX_Theme_Customizer_Typography();
	$elements = $obj->elements();

	// Loop through color options and add a theme customizer setting for it
	foreach( $elements as $element => $array ) {
		$target = isset ( $array['target'] ) ? $array['target'] : '';
		$theme_mods	= array( 'font-weight', 'font-style', 'font-size', 'color', 'line-height', 'letter-spacing', 'text-transform' );
		foreach ( $theme_mods as $mod ) { ?>
			wp.customize( '<?php $element .'_typography['. $mod .']'; ?>', function( value ) {
				$currentVal = $( '<?php echo $target; ?>' ).css('<?php echo $mod; ?>');
				value.bind( function( newval ) {
					<?php if ( 'font-size' == $mod ) {
						$newval = "parseInt(newval, 10) + 'px'";
					} elseif ( 'letter-spacing' == $mod ) {
						$newval = "parseInt(newval, 10) + 'px'";
					} else {
						$newval = 'newval';
					} ?>
					if ( newval ) {
						$( '<?php echo $target; ?>' ).css('<?php echo $mod; ?>', <?php echo $newval; ?> );
					} else {
						$( '<?php echo $target; ?>' ).css('<?php echo $mod; ?>', '' );
					}
				} );
			} );
		<?php
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/*	- Exit
/*-----------------------------------------------------------------------------------*/
exit;