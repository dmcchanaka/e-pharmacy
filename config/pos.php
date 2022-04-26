<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Privileged user types for medical app
    |--------------------------------------------------------------------------
    |
    | Please put the medical representative user type ids below. It will enables
    | the user type to using medical app.
    |
    */
    'super_admin_user_type'=>explode(',',env('SUPER_ADMIN_USER_TYPES','1'))

];

