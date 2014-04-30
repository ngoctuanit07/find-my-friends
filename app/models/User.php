<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		return $this->remember_token;
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

    public function sessions()
    {
        return $this->hasMany('UserSession');
    }

    public function locations()
    {
        return $this->hasMany('Location');
    }

    public function friends()
    {
        return $this->hasMany('Friend');
    }

    public function getLastLocation()
    {
        return $this->locations()->orderBy('id', 'desc')->take(1)->get();
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'photo' => $this->photo,
            'friends' => $this->friends->toArray(),
            'location' => $this->getLastLocation()->toArray()
        ];
    }

    public function toArrayForFriend($friendId)
    {
        $friend = $this->friends()->where('friend_user_id', $friendId)->first();
        $location = [];
        if ($friend === null) return [];
        if ($friend->canShareLocation()) $location = $this->getLastLocation()->toArray();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'photo' => $this->photo,
            'location' => $location
        ];
    }
}
