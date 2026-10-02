<?php

namespace Palasthotel\WordPress\GuestUser;

use WP_Session_Tokens;

defined( 'ABSPATH' ) || exit;

class Repository {

	public function setIsGuest( $userId, bool $isGuest ) {
		if ( $isGuest ) {
			update_user_meta( $userId, Plugin::USER_META_IS_GUEST, Plugin::USER_META_IS_GUEST_VALUE );
			// Sign the account out everywhere it is still signed in.
			WP_Session_Tokens::get_instance( $userId )->destroy_all();
		} else {
			delete_user_meta( $userId, Plugin::USER_META_IS_GUEST );
		}
	}

	public function isGuest( $userId ): bool {
		return get_user_meta( $userId, Plugin::USER_META_IS_GUEST, true ) === Plugin::USER_META_IS_GUEST_VALUE;
	}
}
