<?php

namespace Palasthotel\WordPress\GuestUser;

defined( 'ABSPATH' ) || exit;

class AdminView extends Components\Component {

	const NONCE_ACTION = "guest_user_set_flag";
	const NONCE_NAME = "guest_user_nonce";

	public function onCreate() {
		parent::onCreate();

		add_action('user_new_form', [$this, 'user_new_form']);
        add_action('user_register', [$this, 'user_register']);

		add_action( 'edit_user_profile', [ $this, 'edit_user_profile' ] );
		add_action( 'edit_user_profile_update', [ $this, 'edit_user_profile_update' ] );
	}

	public function user_new_form($type){
        if($type === "add-new-user"){
	        $this->render(false);
        }

	}

	public function edit_user_profile( \WP_User $user ) {
		$isGuest = $this->plugin->repository->isGuest( $user->ID );
		$this->render($isGuest);
	}

	private function render( $checked ) {

		?>
            <h2><?php esc_html_e("Guest User", "guest-user"); ?></h2>
            <table class="form-table">
                <tbody>
                <tr>
                    <th>
                        <label for="guest-user"><?php esc_html_e("Is guest", "guest-user"); ?></label>
                    </th>
                    <td>
                        <label for="guest-user">
                            <?php wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME ); ?>
                            <input type="checkbox" name="is_guest_user" id="guest-user" value="true" <?php checked( $checked ); ?> /> <?php esc_html_e("If checked, user cannot sign in.", "guest-user"); ?>
                        </label>
                    </td>
                </tr>
                </tbody>
            </table>
		<?php
	}

    public function user_register($user_id){
	    $this->trySafe($user_id);
    }

	public function edit_user_profile_update($user_id){
		$this->trySafe($user_id);
	}

    private function trySafe($user_id){
	    if ( !current_user_can( 'edit_user', $user_id ) ) {
		    return false;
	    }

	    // Only requests from the form above carry the nonce; user_register fires for
	    // every new account, however it is created.
	    if ( empty( $_POST[ self::NONCE_NAME ] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST[ self::NONCE_NAME ] ) ), self::NONCE_ACTION ) ) {
		    return false;
	    }

	    $this->plugin->repository->setIsGuest(
		    $user_id,
		    !empty($_POST["is_guest_user"]) && $_POST["is_guest_user"] == "true"
	    );

	    return true;
    }
}
