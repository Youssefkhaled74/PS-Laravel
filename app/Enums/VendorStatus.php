<?php

namespace App\Enums;

enum VendorStatus: string
{
    case PENDING = 'pending';
    case PHONE_VERIFIED = 'phone_verified';
    case COMMERCIAL_SUBMITTED = 'commercial_submitted';
    case PACKAGE_SELECTED = 'package_selected';
    case PAYMENT_PENDING = 'payment_pending';
    case AWAITING_APPROVAL = 'awaiting_approval';
    case ACTIVE = 'active';
    case REJECTED = 'rejected';
    case PAYMENT_FAILED = 'payment_failed';
    case OTP_PENDING = 'otp_pending';
}
