<?php


/*
 *   $civicrm_address = array(
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
 * Implementation of hook_commerce_civicrm_address
 * @param $civicrm_address
 * @param $order
 * @param $cid
 */
function hook_commerce_civicrm_params(&$params, $order, $cid) {
  // You can grab the profile of the customer like so..
  $profile_id = $order->commerce_customer_billing['und'][0]['profile_id'];
  if (!$profile_id) return;
  $profile = commerce_customer_profile_load($profile_id);
  // Then alter $params as required to add any custom commerce fields to send to CiviCRM.
  $params['job_title'] = $profile->field_job_title['und'][0]['value'];
  $params['current_employer'] = $profile->field_organisation['und'][0]['value'];
  $params['phone'] = array(
    'is_primary' => TRUE,
    'phone' => $profile->field_phone['und'][0]['value'],
    'phone_type_id' => 1,
    'location_type' => 'Home'
  );
  // It would be good to make these mappable through a GUI
}