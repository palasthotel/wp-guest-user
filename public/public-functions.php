<?php

use Palasthotel\WordPress\GuestUser\Plugin;

defined( 'ABSPATH' ) || exit;

function guest_user_is_guest( $userId ): bool {
	return Plugin::instance()->repository->isGuest($userId);
}