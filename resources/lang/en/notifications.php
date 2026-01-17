<?php

return [
    'fetched' => 'Notifications fetched successfully.',
    'mark_read' => 'Notification marked as read.',
    'read_all' => 'All notifications marked as read.',

    'sections' => [
        'today' => 'Today',
        'yesterday' => 'Yesterday',
        'earlier' => 'Earlier',
    ],

    'types' => [
        'order_shipped' => [
            'title' => 'Your order has been shipped',
            'body' => 'Your order #:order_id has been shipped.',
        ],
        'rate_request' => [
            'title' => 'New rating request',
            'body' => 'A customer requested a rating for order #:order_id',
        ],
        'general' => [
            'title' => 'Notification',
            'body' => 'You have a new notification.',
        ],
    ],

    'errors' => [
        'not_found' => 'Notification not found.',
    ],
];
