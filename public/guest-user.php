<?php

/**
 * Plugin Name: Guest User
 * Plugin URI: https://github.com/palasthotel/wp-guest-user
 * Description: Label accounts as guests and prevent the login
 * Version: 1.0.1
 * Author: Palasthotel <webmaster@palasthotel.de>
 * Author URI: https://palasthotel.de
 * Text Domain: guest-user
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * License: GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * @copyright Copyright (c) 2022, Palasthotel
 * @package Palasthotel\WordPress\GuestUser
 */

namespace Palasthotel\WordPress\GuestUser;

defined( 'ABSPATH' ) || exit;

require_once dirname(__FILE__)."/vendor/autoload.php";

class Plugin extends Components\Plugin {

	/**
	 * @var Repository
	 */
	public $repository;

	/**
	 * @var Security
	 */
	public $security;

	/**
	 * @var AdminView
	 */
	public $adminView;

	const DOMAIN = "guest-user";

	const USER_META_IS_GUEST = "_is_guest_user";
	const USER_META_IS_GUEST_VALUE = "yes";

	function onCreate() {

		$this->loadTextdomain(Plugin::DOMAIN, "languages");

		$this->repository = new Repository();
		$this->security   = new Security($this);
		$this->adminView  = new AdminView($this);

	}
}

Plugin::instance();

require_once dirname(__FILE__)."/public-functions.php";