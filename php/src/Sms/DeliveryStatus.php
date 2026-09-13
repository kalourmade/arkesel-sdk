<?php

declare(strict_types=1);

namespace Kalourmade\Arkesel\Sms;

enum DeliveryStatus: string
{
    case Delivered = 'DELIVERED';
    case Submitted = 'SUBMITTED';
    case Prohibited = 'PROHIBITED';
    case Queued = 'QUEUED';
    case NotDelivered = 'NOT_DELIVERED';
    case Expired = 'EXPIRED';
}
