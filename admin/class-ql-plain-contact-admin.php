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
	 * Conditionally start session for simple numeric captcha feature
	 * Source: https://stackoverflow.com/a/49025103
	 */

	public function session_starter() {

		// Start session for simple math captcha validation
		if ( ! isset( $_SESSION ) ) session_start();

	}

	/**
	 * Shortcode function
	 */
	public function ql_plain_contact($atts) {

		// Define random number between 10 to 99 to use in captcha
		$_SESSION['pc_randomnumber'] = isset( $_SESSION['pc_randomnumber'] ) ? $_SESSION['pc_randomnumber'] : rand( 45, 99 );

		$num1 = rand( 15, 30 );
		$num2 = $_SESSION['pc_randomnumber'] - $num1;

		// Create nonce
		$session_nonce = wp_create_nonce( 'contact_message_' . $_SESSION['pc_randomnumber'] );

		$atts = shortcode_atts(
					array(
						'error_incomplete_fields' => 'Please fill in all the required fields.',
						'error_invalid_name'      => 'Please enter at least 2 characters',
						'error_invalid_email'     => 'Please enter a valid email',
						'error_invalid_subject'   => 'Please enter at least 2 characters',
						'error_invalid_message'   => 'Please enter at least 10 characters',
						'error_invalid_number'    => 'Please enter the correct number',
						'valid_nonce'             => 'All good. Thanks for using a valid nonce.',
						'invalid_nonce'           => 'Looks like you\'re not posting data from this contact form. Better luck next time. :-)',
						'input_valid_message'     => 'All is good.',
						'success'                 => 'Thank you for your message! I will get back to you as soon as I can.',
						'email_to'                => get_bloginfo('admin_email'),
					),
					$atts
				);

		// Set default values for variables before form data is submitted

		$error_status = array();

		$error = false;

		$process_complete = false;

		$success_info = '';

		// Ensure form data originates from the contact form, and that submit button with the specified 'name' is set, i.e. clicked

		if ( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && ( isset( $_POST['pc_formsend'] ) ) ) {

			// Grab posted form data and sanitize them
			$form_data = array(
				'pc_name'    => sanitize_text_field( stripslashes( $_POST['pc_name'] ) ),
				'pc_email'   => sanitize_email( stripslashes( $_POST['pc_email'] ) ),
				'pc_subject' => sanitize_text_field( stripslashes ( $_POST['pc_subject'] ) ),
				'pc_message' => sanitize_textarea_field( stripslashes( $_POST['pc_message'] ) ),
				'pc_num'     => sanitize_text_field( $_POST['pc_num'] ),
				'pc_nonce'   => sanitize_text_field( $_POST['pc_nonce'] ),
			);

			// Validate form data and define error message if validation failed

			if ( ( strlen( $form_data['pc_name'] ) < 2 ) || empty( $form_data['pc_name'] ) ) {

				$error_status['pc_name'] = true;
				$error = true;
				$validation_result['pc_name'] = $atts['error_invalid_name'];

			} else {

				$error_status['pc_name'] = false;
				$validation_result['pc_name'] = $atts['input_valid_message'];

			}

			if ( ( strlen( $form_data['pc_email'] ) < 2 ) || empty( $form_data['pc_email'] ) ) {

				$error_status['pc_email'] = true;
				$error = true;
				$validation_result['pc_email'] = $atts['error_invalid_email'];

			} else {

				$error_status['pc_email'] = false;
				$validation_result['pc_email'] = $atts['input_valid_message'];

			}

			if ( ( strlen( $form_data['pc_subject'] ) < 2 ) || empty( $form_data['pc_subject'] ) ) {

				$error_status['pc_subject'] = true;
				$error = true;
				$validation_result['pc_subject'] = $atts['error_invalid_subject'];

			} else {

				$error_status['pc_subject'] = false;
				$validation_result['pc_subject'] = $atts['input_valid_message'];

			}

			if ( ( strlen( $form_data['pc_message'] ) < 10 ) || empty( $form_data['pc_message'] ) ) {

				$error_status['pc_message'] = true;
				$error = true;
				$validation_result['pc_message'] = $atts['error_invalid_message'];

			} else {

				$error_status['pc_message'] = false;
				$validation_result['pc_message'] = $atts['input_valid_message'];

			}

			if ( $form_data['pc_num'] == $_SESSION['pc_randomnumber'] ) {

				$error_status['pc_num'] = false;
				$validation_result['pc_num'] = $atts['input_valid_message'];

			} else {

				$error_status['pc_num'] = true;
				$error = true;
				$validation_result['pc_num'] = $atts['error_invalid_number'];

			}

			if ( wp_verify_nonce( $form_data['pc_nonce'], 'contact_message_' . $_SESSION['pc_randomnumber'] ) ) {

				$error_status['pc_nonce'] = false;
				$validation_result['pc_nonce'] = $atts['valid_nonce'];

			} else {

				$error_status['pc_nonce'] = true;
				$error = true;
				$validation_result['pc_nonce'] = $atts['invalid_nonce'];

			}

			if ( $error == false ) {

				// Send email

				$subject = '[' . get_bloginfo('name') . '] ' . $form_data['pc_subject'];

				$message = 'From: ' . $form_data['pc_name'] . ' <' . $form_data['pc_email'] . '>' . "\r\n\r\n" . $form_data['pc_message'];

				wp_mail( $atts['email_to'], $subject, $message );

				// Mark process as complete. This will be used to clear session variable (random number)
				$process_complete = true;

				// Success message variable
				$success_info = '<p class="success">'.$atts['success'].'</p>';

			}

		}

		// For checking variables with https://wordpress.org/plugins/debug-toolkit/
		// dump( $form_data );
		// dump( $error_status );
		// dump( $error );
		// dump( $validation_result );
		// dump( $process_complete );
		// dump( $_SESSION['pc_randomnumber'] );
		// dump( $session_nonce );

		// Return the contact form from output buffer

		ob_start();

		?>

		<form action="" method="post" id="" class="plain-contact">

			<p>
				<label for="pc_name">Name:</label>
				<input type="text" name="pc_name" id="pc_name" class="" maxlength="100" value="<?php echo stripslashes( $_POST['pc_name'] ); ?>" />
				<span class="<?php echo ( ( true == $error_status['pc_name'] ) ? 'error' : 'hide' ) ?>"><?php echo $validation_result['pc_name']; ?></span>
			</p>

			<p>
				<label for="pc_email">Email:</label>
				<input type="text" name="pc_email" id="pc_email" class="" maxlength="100" value="<?php echo stripslashes( $_POST['pc_email'] ); ?>" />
				<span class="<?php echo ( ( true == $error_status['pc_email'] ) ? 'error' : 'hide' ) ?>"><?php echo $validation_result['pc_email']; ?></span>
			</p>

			<p>
				<label for="pc_subject">Subject:</label>
				<input type="text" name="pc_subject" id="pc_subject" class="" maxlength="100" value="<?php echo stripslashes( $_POST['pc_subject'] ); ?>" />
				<span class="<?php echo ( ( true == $error_status['pc_subject'] ) ? 'error' : 'hide' ) ?>"><?php echo $validation_result['pc_subject']; ?></span>
			</p>

			<p>
				<label for="pc_message">Message:</label>
				<textarea rows="5" name="pc_message" id="pc_message" class=""><?php echo stripslashes( $_POST['pc_message'] ); ?></textarea>
				<span class="<?php echo ( ( true == $error_status['pc_message'] ) ? 'error' : 'hide' ) ?>"><?php echo $validation_result['pc_message']; ?></span>
			</p>

			<p>
				<input type="hidden" name="pc_nonce" value="<?php echo $session_nonce; ?>" />
				<label for="pc_num"><?php echo $num2 ?> + <?php echo $num1; ?> =</label>
				<input type="text" name="pc_num" id="pc_num" class="" maxlength="" value="<?php echo stripslashes( $_POST['pc_num'] ); ?>" />
				<input type="submit" value="Submit" name="pc_formsend" id="pc_formsend" class="" />
				<span class="pc-num <?php echo ( ( true == $error_status['pc_num'] ) ? 'error' : 'hide' ) ?>"><?php echo $validation_result['pc_num']; ?></span>
			</p>

			<span class="pc-nonce <?php echo ( ( true == $error_status['pc_nonce'] ) ? 'error' : 'hide' ) ?>"><?php echo $validation_result['pc_nonce']; ?></span>

		</form>

		<?php

		$form = ob_get_clean();

		// Unset session variable. Clears random number.
		if ( $process_complete == true ) {

			unset( $_SESSION['pc_randomnumber'] );

			return $success_info;

			// For checking variables with https://wordpress.org/plugins/debug-toolkit/
			// dump( $_SESSION['pc_randomnumber'] );

		}

		return $form;

	}



}
