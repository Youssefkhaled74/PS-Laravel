<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Vendor Onboarding Language Lines
    |--------------------------------------------------------------------------
    */

    'onboarding' => [
        'commercial_saved' => 'Commercial data saved successfully.',
        'subscription_selected' => 'Subscription package selected successfully.',
        'payment_confirmed' => 'Payment confirmed successfully.',
        'payment_failed' => 'Payment failed. Please try again.',
        'payment_pending' => 'Payment is pending confirmation.',
    ],

    'status' => [
        'pending' => 'Pending verification',
        'otp_pending' => 'OTP verification pending',
        'subscription_pending' => 'Please select a subscription package',
        'payment_pending' => 'Payment pending',
        'payment_failed' => 'Payment failed',
        'awaiting_approval' => 'Your application is under review',
        'active' => 'Active',
        'rejected' => 'Application rejected',
        'suspended' => 'Account suspended',
    ],

    'errors' => [
        'invalid_step' => 'Invalid onboarding step.',
        'terms_not_accepted' => 'You must accept the terms and conditions.',
        'commercial_required' => 'Commercial data is required.',
        'subscription_required' => 'Subscription selection is required.',
        'payment_required' => 'Payment is required.',
        'commercial_or_freelance_required' => 'Either commercial register number or freelance document number is required.',
        'subscription_not_found' => 'Subscription not found or does not belong to you.',
    ],

    'fields' => [
        'commercial_name' => 'commercial name',
        'brands' => 'brands',
        'brand' => 'brand',
        'bank' => 'bank',
        'bank_account_number' => 'bank account number',
        'id_card_file' => 'ID card file',
        'commercial_file' => 'commercial register file',
        'package' => 'package',
        'billing_period' => 'billing period',
        'subscription' => 'subscription',
        'payment_method' => 'payment method',
        'payment_status' => 'payment status',
    ],
];
