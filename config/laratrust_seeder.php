<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => true,
    'create_employees' => true,
    'create_types' => true,
    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,
    'roles_structure' => [
        'superadministrator' => [
            'user' => 'c,r,u,d',
            'role' => 'c,r,u,d',
            'product' => 'c,r,u,d',
            'category' => 'c,r,u,d',
            'section' => 'c,r,u,d',
            'store' => 'c,r,u,d',
            'custom_field' => 'c,r,u,d',
            'brand' => 'c,r,u,d',
        ],
        'administrator' => [
            'user' => 'c,r,u,d',
            'product' => 'r,u',
            'category' => 'r,u',
            'section' => 'r,u',
            'store' => 'r,u',
            'custom_field' => 'r,u',
            'brand' => 'r,u',
        ],
        'user' => [
            'product' => 'r',
            'category' => 'r',
            'section' => 'r',
            'store' => 'r',
            'custom_field' => 'r',
            'brand' => 'r',
        ],
        'role_name' => [
            'module_1_name' => 'c,r,u,d',
        ]
    ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
