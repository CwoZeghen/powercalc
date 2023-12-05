<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Contact
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $appliance
 * @property string $token
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Contact extends Model
{
	protected $table = 'contact';

	protected $casts = [
		'email_verified_at' => 'datetime'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'name',
		'email',
		'phone',
		'address',
		'appliance',
		'token',
		'email_verified_at'
	];
}
