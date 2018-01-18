<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * Get the user that owns the meta.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        $userRoles = [];
        foreach ($this->roles as $role) {
            $userRoles[] = $role['name'];
        }
        return in_array('admin', $userRoles);
    }

    /**
     * @return mixed
     */
    public function getDorelApiToken()
    {
        return $this->dorel_api_token;
    }

    /**
     * @param string $token
     */
    public function setDorelApiToken(string $token)
    {
        $this->dorel_api_token = $token;
    }
}
