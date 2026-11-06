<?php

return [
    'leads' => [
        'type' => 'workflow',
        'marking_store' => [
            'type' => 'multiple_state',
            'arguments' => ['status'],
        ],
        'supports' => [App\Lead::class],
        'places' => ['draft', 'review', 'rejected', 'published'],
        'transitions' => [
            'to_review' => [
                'from' => 'draft',
                'to' => 'review',
            ],
            'publish' => [
                'from' => 'review',
                'to' => 'published',
            ],
            'reject' => [
                'from' => 'review',
                'to' => 'rejected',
            ],
        ],
    ],
];
