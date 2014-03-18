<?php

namespace Rarst\Holder;

/**
 * Main plugin's class.
 */
class Plugin {

	static $themes = array();

	static $default_arguments = array(
		'width'      => '100%', // integer for pixels or integer with percent sign for fluid
		'height'     => '100%', // integer for pixels or integer with percent sign for fluid
		'theme'      => false,  // gray | industrial | social or custom
		'background' => false,  // hex color code
		'foreground' => false,  // hex color code
		'text'       => false,
	);

	/**
	 * Plugin load.
	 */
	static function on_load() {

		add_action( 'init', array( __CLASS__, 'init' ) );
	}

	/**
	 * Register shortcode.
	 */
	static function init() {

		add_shortcode( 'holder', array( __CLASS__, 'shortcode' ) );
	}

	/**
	 * Enqueue script if not done yet.
	 */
	static function enqueue() {

		static $enqueued = false;

		if ( ! $enqueued ) {

			wp_enqueue_script( 'holder', plugins_url( '/js/holder.js', dirname( __FILE__ ) ), array(), 1.7, true );
			$enqueued = true;
		}
	}

	/**
	 * Retrieve img tag for placeholder.
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	static function get_img( $args = array() ) {

		self::enqueue();

		$args = wp_parse_args( $args, self::$default_arguments );
		$path = '/' . $args['width'] . 'x' . $args['height'];

		if( ! empty( $args['theme'] ) )
			$path .= '/' . $args['theme'];
		elseif ( ! empty( $args['background'] ) && ! empty( $args['foreground'] ) )
			$path .= '/#' . trim( $args['background'], '#' ) . ':#' . trim( $args['foreground'], '#' );

		if( ! empty( $args['text'] ) )
			$path .= '/text:' . $args['text'];

		if( ! empty( $args['font'] ) )
			$path .= '/font:' . $args['font'];

		return '<img data-src="holder.js'. esc_attr( $path ) .'" />';
	}

	/**
	 * Shortcode handler.
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	static function shortcode( $args ) {

		return self::get_img( shortcode_atts( self::$default_arguments, $args ) );
	}

	/**
	 * Add Holder theme to use.
	 *
	 * @param string $name       of theme
	 * @param string $background color in hex
	 * @param string $foreground color in hex
	 * @param int    $size       of font
	 * @param string $text       default for theme
	 * @param string $font       font-family to use
	 */
	static function add_theme( $name, $background, $foreground, $size = 12, $text = '', $font = '' ) {

		static $enqueued = false;

		if ( ! $enqueued ) {

			add_action( 'wp_print_footer_scripts', array( __CLASS__, 'wp_print_footer_scripts' ), 11 );
			$enqueued = true;
		}

		self::$themes[] = compact( 'name', 'background', 'foreground', 'size', 'text', 'font' );
	}

	/**
	 * Output code for custom themes if needed.
	 */
	static function wp_print_footer_scripts() {

		if ( empty( self::$themes ) || ! wp_script_is( 'holder', 'done' ) )
			return;

		?>
  <script type=text/javascript>
			<?php foreach ( self::$themes as $theme ) : ?>
      Holder.add_theme("<?php echo esc_js( $theme['name'] ) ?>", {
          background:"#<?php echo esc_js( trim( $theme['background'], '#' ) ) ?>",
          foreground:"#<?php echo esc_js( trim( $theme['foreground'], '#' ) ) ?>",
					<?php if ( ! empty( $theme['text'] ) ) : ?>text:"<?php echo esc_js( $theme['text'] ); ?>",<?php endif; ?>
					<?php if ( ! empty( $theme['font'] ) ) : ?>font:"<?php echo esc_js( $theme['font'] ); ?>",<?php endif; ?>
          size      :<?php echo (int) $theme['size']; ?>
      });
			<?php endforeach; ?>
  </script>
		<?php
	}
}