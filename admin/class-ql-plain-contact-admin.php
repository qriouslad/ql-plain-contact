<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://bowo.io
 * @since      1.0.0
 *
 * @package    Ql_Plain_Contact
 * @subpackage Ql_Plain_Contact/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ql_Plain_Contact
 * @subpackage Ql_Plain_Contact/admin
 * @author     Bowo <hello@bowo.io>
 */
class Ql_Plain_Contact_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode( 'plaincontact', array( $this, 'ql_plain_contact' ) );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ql_Plain_Contact_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ql_Plain_Contact_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ql-plain-contact-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ql_Plain_Contact_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ql_Plain_Contact_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ql-plain-contact-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Shortcode function
	 */
	public function ql_plain_contact($atts) {

		$atts = shortcode_atts(
					array(),
					$atts
				);

		// Ensure form data originates from the contact form, and that submit button with the specified 'name' is set, i.e. clicked

		if ( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && ( isset( $_POST['pc_formsend'] ) ) ){

			// Grab posted form data and sanitize them
			$form_data = array(
				'pc_name'    => sanitize_text_field( $_POST['pc_name'] ),
				'pc_email'   => sanitize_email( $_POST['pc_email'] ),
				'pc_subject' => sanitize_text_field( $_POST['pc_subject'] ),
				'pc_sum'     => sanitize_text_field( $_POST['pc_sum'] ),
				'pc_message' => sanitize_textarea_field( $_POST['pc_message'] ),
			);

		}

		dump($form_data);

		ob_start();

		?>

		<form action="" method="post" id="" class="plain-contact">

			<p>
				<label for="pc_name">Name</label>
			</p>
			<p>
				<input type="text" name="pc_name" id="pc_name" class="" maxlength="100" value="" />
			</p>

			<p>
				<label for="pc_email">Email</label>
			</p>
			<p>
				<input type="text" name="pc_email" id="pc_email" class="" maxlength="100" value="" />
			</p>

			<p>
				<label for="pc_subject">Subject</label>
			</p>
			<p>
				<input type="text" name="pc_subject" id="pc_subject" class="" maxlength="100" value="" />
			</p>

			<p>
				<label for="pc_sum">Sum of [formula]</label>
			</p>
			<p>
				<input type="text" name="pc_sum" id="pc_sum" class="" maxlength="" value="" />
			</p>

			<p>
				<label for="pc_message">Message</label>
			</p>
			<p>
				<textarea rows="5" name="pc_message" id="pc_message" class="">Message content... </textarea>
			</p>

			<p><input type="submit" value="Submit" name="pc_formsend" id="pc_formsend" class="" /></p>

		</form>

		<?php

		return ob_get_clean();

	}



}
