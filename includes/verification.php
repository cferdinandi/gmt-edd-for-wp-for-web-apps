<?php


	/**
	 * Looks up purchases by email that match the registering user
	 *
	 * This is for users that purchased as a guest and then came
	 * back and created an account.
	 *
	 * @access      public
	 * @since       1.6
	 * @param       int $user_id - the new user's ID
	 * @return      void
	 */
	function gmt_edd_for_wpwa_add_past_purchases_to_new_user( $user_id ) {

		$email    = get_the_author_meta( 'user_email', $user_id );

		$payments = edd_get_payments( array( 's' => $email ) );

		if( $payments ) {

			// Set a flag to force the account to be verified before purchase history can be accessed
			edd_set_user_to_pending( $user_id );

			// edd_send_user_verification_email( $user_id );

			foreach( $payments as $payment ) {
				if( intval( edd_get_payment_user_id( $payment->ID ) ) > 0 ) {
					continue; // This payment already associated with an account
				}

				$meta                    = edd_get_payment_meta( $payment->ID );
				$meta['user_info']       = maybe_unserialize( $meta['user_info'] );
				$meta['user_info']['id'] = $user_id;
				$meta['user_info']       = $meta['user_info'];

				// Store the updated user ID in the payment meta
				edd_update_payment_meta( $payment->ID, '_edd_payment_meta', $meta );
				edd_update_payment_meta( $payment->ID, '_edd_payment_user_id', $user_id );
			}
		}

	}
	remove_action( 'user_register', 'edd_add_past_purchases_to_new_user', 10 );
	add_action( 'user_register', 'gmt_edd_for_wpwa_add_past_purchases_to_new_user', 10, 1 );



	/**
	 * Verify new user in EDD
	 * @param  integer $user_id [description]
	 */
	function gmt_edd_for_wpwa_verify_user( $user_id = 0 ) {
		edd_set_user_to_verified( $user_id );
	}
	add_action( 'wpwebapp_after_password_reset', 'gmt_edd_for_wpwa_verify_user' );