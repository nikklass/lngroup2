<?php

return [

    'role_structure' => [
        
        'superadministrator' => [
            
            'users' => 'c,r,u,d',
            'accounts' => 'c,r,u,d',
            'acls' => 'c,r,u,d',
            'profiles' => 'c,r,u,d',
            'sms' => 'c,r,u,d',
            'scheduledsms' => 'c,r,u,d',
            'groups' => 'c,r,u,d',
            'withdrawals' => 'c,r,u,d',
            'deposits' => 'c,r,u,d',
            'repayments' => 'c,r,u,d',
            'accounts' => 'c,r,u,d',
            'loans' => 'c,r,u,d',
            'reports' => 'c,r,u,d',
            'chatchannels' => 'c,r,u,d'

        ],  

        'administrator' => [
            
            'users' => 'c,r,u,d',
            'accounts' => 'c,r,u,d',
            'profiles' => 'c,r,u,d',
            'sms' => 'c,r,u,d',
            'scheduledsms' => 'c,r,u,d',
            'groups' => 'c,r,u,d',
            'withdrawals' => 'c,r,u,d',
            'deposits' => 'c,r,u,d',
            'repayments' => 'c,r,u,d',
            'accounts' => 'c,r,u,d',
            'loans' => 'c,r,u,d',
            'reports' => 'c,r,u,d',
            'chatchannels' => 'c,r,u'

        ],

        'manager' => [

            'profiles' => 'r,u',
            'reports' => 'c,r'

        ],

        'user' => [

            'profiles' => 'c,r,u,d'

        ]

    ],

    'permission_structure' => [
        
        /*'cru_user' => [
            'profile' => 'c,r,u'
        ],*/

    ],

    'permissions_map' => [

        'c' => 'create',
        'r' => 'list',
        'u' => 'update',
        'd' => 'delete'

    ]

];
