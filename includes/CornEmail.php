<?php

namespace WeLabs\Demo;

use WC_Email;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CornEmail extends WC_Email {
	/**
	 * Email method ID.
	 *
	 * @var String
	 */
	public $id;

	/**
	 * Email method title.
	 *
	 * @var string
	 */
	public $title;

	/**
	 * 'yes' if the method is enabled.
	 *
	 * @var string yes, no
	 */
	public $enabled;

	/**
	 * Description for the email.
	 *
	 * @var string
	 */
	public $description;

	/**
	 * Default heading.
	 *
	 * Supported for backwards compatibility but we recommend overloading the
	 * get_default_x methods instead so localization can be done when needed.
	 *
	 * @var string
	 */
	public $heading = '';

	/**
	 * Default subject.
	 *
	 * Supported for backwards compatibility but we recommend overloading the
	 * get_default_x methods instead so localization can be done when needed.
	 *
	 * @var string
	 */
	public $subject = '';

	/**
	 * Plain text template path.
	 *
	 * @var string
	 */
	public $template_plain;

	/**
	 * HTML template path.
	 *
	 * @var string
	 */
	public $template_html;

	/**
	 * Template path.
	 *
	 * @var string
	 */
	public $template_base;

	/**
	 * Recipients for the email.
	 *
	 * @var string
	 */
	public $recipient;

	/**
	 * Object this email is for, for example a customer, product, or email.
	 *
	 * @var object|bool
	 */
	public $object;

	/**
	 * Mime boundary (for multipart emails).
	 *
	 * @var string
	 */
	public $mime_boundary;

	/**
	 * Mime boundary header (for multipart emails).
	 *
	 * @var string
	 */
	public $mime_boundary_header;

	/**
	 * True when email is being sent.
	 *
	 * @var bool
	 */
	public $sending;

	/**
	 * True when the email notification is sent manually only.
	 *
	 * @var bool
	 */
	protected $manual = false;

	/**
	 * True when the email notification is sent to customers.
	 *
	 * @var bool
	 */
	protected $customer_email = false;


	/**
	 * The new email address of the user.
	 *
	 * @var string
	 */
	protected $new_email;

	/**
	 * Display name of the user.
	 *
	 * @var string
	 */
	protected $user_name;

	/**
	 * Display name of the user.
	 *
	 * @var string
	 */
	protected $product;

	public function __construct() {
		$this->id             = 'welabs_demo_cron_email';
		$this->title          = 'Product Update Notification';
		$this->description    = 'This email is sent when a product is updated to the admin.';
		$this->heading        = 'Product Updates';
		$this->subject        = 'Recently Updated Products';
		$this->template_html  = '/emails/product-update-email.php';
		$this->template_plain = '/emails/plain-product-update-email.php';
		$this->template_base =  DEMO_TEMPLATE_DIR;

		parent::__construct();

		$this->recipient = get_option( 'admin_email' );

	}

	/**
	 * Trigger the email.
	 */
	public function trigger( $product ) {
		if ( empty( $this->recipient ) ) {
			return;
		}
		$this->product = $product;
		if ( $this->is_enabled() ) {
			$this->setup_locale();
			$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), array(), '' );
			$this->restore_locale();
		}
	}

	/**
	 * Get email subject.
	 *
	 * @since  3.6.0
	 * @return string
	 */
	public function get_default_subject() {
		return $this->get_option( 'subject', __( 'Subject', 'demo' ) );
	}

	/**
	 * Get email heading.
	 *
	 * @since  3.6.0
	 * @return string
	 */
	public function get_default_heading() {
		return __( 'Email sent', ' demo' );
	}

	/**
	 * Get email content (fixing method signature).
	 */
	public function get_content() {
		ob_start();
		wc_get_template(
			$this->template_html,
			$this->product,
			'',
			$this->template_base,
		);
		return ob_get_clean();
	}
}
