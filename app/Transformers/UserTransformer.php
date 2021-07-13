<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'userId' => (int) $user->id,
            'userName' => (string) $user->name,
            'userPhone' => (string) $user->phone,
            'userEmail' => (string) $user->email,
            'isVerified' => (string) $user->verified,
            'isAdmin' => ($user->admin === 'true'),
            'creationDate' => (string) $user->created_at,
            'updatedDate' => (string) $user->updated_at,
            //'code' => (string) $user->verify_code,

        ];

        //   "id": 3,
        // "name": "Miss Aditya Hirthe V",
        // "email": "adan.reynolds@example.org",
        // "phone": "1-480-580-2018",
        // "email_verified_at": "2021-07-08T18:22:24.000000Z",
        // "verified": "1",
        // "admin": "false",
        // "created_at": "2021-07-08T18:22:24.000000Z",
        // "updated_at": "2021-07-08T18:22:24.000000Z"
    }

    public static function originalData($index)
    {
        $attributes = [
            'userId' => 'id',
            'userName' => 'name',
            'userPhone' => 'phone',
            'userEmail'=>'email',
            'isVerified' => 'verified',
            'isAdmin' => 'admin',
            'creationDate' => 'created_at',
            'updatedDate' => 'updated_at',
            'userPassword'=>'password',
            'verify_code' =>'code'
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;

    }

    //transform for post and update

    public static function transformData($index)
    {
        $attributes = [
            'id' => 'userId',
            'name' => 'userName',
            'phone' => 'userPhone',
            'email'=>'userEmail',
            'verified' => 'isVerified',
            'admin' => 'isAdmin',
            'created_at' => 'creationDate',
            'updated_at' => 'updatedDate',
            'password'=>'userPassword',
            'code' =>'verify_code'
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;

    }
}
