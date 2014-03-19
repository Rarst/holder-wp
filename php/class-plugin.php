<?php

namespace Rarst\Holder;

/**
 * Main plugin's class.
 */
class Plugin {

	public $components_url;

	public $themes = array();

	public $default_arguments = array(
		'width'      => '100%', // integer for pixels or integer with percent sign for fluid
		'height'     => '100%', // integer for pixels or integer with percent sign for fluid
		'theme'      => false,  // sky | vine | lava | gray | industrial | social or custom
		'background' => false,  // hex color code
		'foreground' => false,  // hex color code
		'text'       => false,
	);

	/**
	 * Plugin load.
	 */
	public function on_load() {

		$this->components_url = plugins_url( '/components', __DIR__ );

		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Register shortcode.
	 */
	public function init() {

		add_shortcode( 'holder', array( $this, 'shortcode' ) );
	}

	/**
	 * Enqueue script if not done yet.
	 */
	public function enqueue() {

		static $enqueued = false;

		if ( ! $enqueued ) {

			wp_enqueue_script( 'holder', $this->components_url . '/holder/holder.js', array(), 2.3, true );
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
	public function get_img( $args = array() ) {

		$this->enqueue();

		$args = (object) wp_parse_args( $args, $this->default_arguments );
		$path = "/{$args->width}x{$args->height}";

		if ( ! empty( $args->theme ) ) {
			$path .= '/' . $args->theme;
		} elseif ( ! empty( $args->background ) && ! empty( $args->foreground ) ) {
			$args->background = trim( $args->background, '#' );
			$args->foreground = trim( $args->foreground, '#' );
			$path .= "/#{$args->background}:#{$args->foreground}";
		}

		if ( ! empty( $args->text ) ) {
			$path .= "/text:{$args->text}";
		}

		if ( ! empty( $args->font ) ) {
			$path .= "/font:{$args->font}";
		}

		return '<img data-src="holder.js'. esc_attr( $path ) .'" />';
	}

	/**
	 * Shortcode handler.
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function shortcode( $args ) {

		return $this->get_img( shortcode_atts( $this->default_arguments, $args ) );
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
	public function add_theme( $name, $background, $foreground, $size = 12, $text = '', $font = '' ) {

		static $enqueued = false;

		if ( ! $enqueued ) {

			add_action( 'wp_print_footer_scripts', array( $this, 'wp_print_footer_scripts' ), 11 );
			$enqueued = true;
		}

		$background = '#' . trim( $background, '#' );
		$foreground = '#' . trim( $foreground, '#' );

		$this->themes[$name] = compact( 'background', 'foreground', 'size', 'text', 'font' );
	}

	/**
	 * Output code for custom themes if needed.
	 */
	public function wp_print_footer_scripts() {

		if ( empty( $this->themes ) || ! wp_script_is( 'holder', 'done' ) )
			return;

		?>
		<script type=text/javascript>
			<?php foreach ( $this->themes as $name => $theme ) : $theme = json_encode( array_filter( $theme ) ); ?>
			Holder.add_theme("<?= esc_js( $name ) ?>", <?= $theme ?>);
			<?php endforeach; ?>
		</script>
		<?php
	}
}