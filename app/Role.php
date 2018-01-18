<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $table = 'roles';

    public $timestamps = false;

    /**
     * Get the users that have the role.
     */
    public function user()
    {
        return $this->belongsToMany('App\User');
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
