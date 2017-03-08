<?php


	/**
	 * Update a customer's email address in EDD when they update it in WPWA
	 * @param  Integer $user_id   The user's ID
	 * @param  String  $old_email The user's previous email address
	 */
	function gmt_edd_for_wpwa_update_email( $user_id, $old_email ) {

		if ( !class_exists( 'EDD_Customer' ) ) return;

		// Get email data from EDD and user in database
		$customer = new EDD_Customer( $old_email );
		$user = get_user_by( 'id', $user_id );

		// Make sure customer is found
		if ( $customer->ID === 0 ) return;

		// If new email isn't listed in customer profile, add it
		if ( $customer->email !== $user->user_email && ( !is_array( $customer->emails ) && !in_array( $user->user_email, $customer->emails ) ) ) {
			$customer->add_email( $user->user_email );
		};

		// Update customer email
		$customer->set_primary_email( $user->user_email );

	}
	add_action( 'wpwebapp_after_email_change', 'gmt_edd_for_wpwa_update_email', 10, 2 );