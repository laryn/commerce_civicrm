<?php


/*
 * $civicrm_address = array(
    1 => array(
      'location_type_id'       => 1,
      'is_primary'             => TRUE,
      'city'                   => $address['locality'],
      'state_province'         => $address['administrative_area'],
      'postal_code'            => $address['postal_code'],
      'street_address'         => $address['thoroughfare'],
      'supplemental_address_1' => $address['premise'],
      'country'                => $address['country']
    )
  );
 */

/**
 * Implements hook_commerce_civicrm_params().
 *
 * @param $params
 *   civicrm_location_update params array.
 * @param $order
 *   Commerce order object.
 * @param $cid
 *   CiviCRM contact id.
 */
function hook_commerce_civicrm_params(&$params, $order, $cid) {
  // You can grab the profile of the customer like so.
  $order_wrapper = entity_metadata_wrapper('commerce_order', $order);
  $profile = $order_wrapper->commerce_customer_billing->value();
  $profile_wrapper = entity_metadata_wrapper('commerce_customer_profile', $profile);

  // Then alter $params as required to add any custom commerce fields to send to CiviCRM.
  $params['job_title'] = $profile_wrapper->field_job_title->value();
  $params['current_employer'] = $profile_wrapper->field_organisation->value();
  $params['phone'] = array(
    array(
      'is_primary' => TRUE,
      'phone' => $profile_wrapper->field_phone->value(),
      'phone_type_id' => 1,
      'location_type' => 'Home'
    )
  );

  // Update the contact, need this for details like employer, job title etc.
  $params['id'] = $params['contact_id'];
  $contact = civicrm_api('contact', 'update', $params);
  // It would be good to make these mappable through a GUI.
}

