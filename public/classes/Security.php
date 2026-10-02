<?php

namespace Palasthotel\WordPress\GuestUser;

use WP_Error;
use WP_User;

defined( 'ABSPATH' ) || exit;

class Security extends Components\Component {

	public function onCreate() {
		parent::onCreate();
		add_filter( "authenticate", [ $this, 'authenticate' ], 100 );
		// Late, so it sees the user that cookies, application passwords or any
		// authentication plugin resolved before.
		add_filter( "determine_current_user", [ $this, 'determine_current_user' ], 100 );
		add_filter( "wp_is_application_passwords_available_for_user", [ $this, 'application_passwords_available' ], 10, 2 );
	}

	/**
	 * @param WP_User|WP_Error|null $user
	 *
	 * @return WP_User|WP_Error
	 */
	public function authenticate( $user ) {

		if ( $user instanceof WP_User ) {

			$isGuest = $this->plugin->repository->isGuest( $user->ID );

			if ( $isGuest ) {
				return new WP_Error( 'guest', __( "Guest accounts cannot sign in.", "guest-user" ) );
			}
		}

		return $user;
	}

	/**
	 * A guest is never the current user, however the request authenticates.
	 *
	 * @param int|false $userId
	 *
	 * @return int|false
	 */
	public function determine_current_user( $userId ) {
		if ( $userId && $this->plugin->repository->isGuest( $userId ) ) {
			return false;
		}

		return $userId;
	}

	/**
	 * @param bool $available
	 * @param WP_User $user
	 *
	 * @return bool
	 */
	public function application_passwords_available( $available, $user ) {
		if ( $user instanceof WP_User && $this->plugin->repository->isGuest( $user->ID ) ) {
			return false;
		}

		return $available;
	}
}
