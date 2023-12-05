<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Appliance
 * 
 * @property int $id
 * @property string $name
 * @property float $watts
 *
 * @package App\Models
 */
class Appliance extends Model
{
	protected $table = 'appliance';
	public $timestamps = false;

	protected $casts = [
		'watts' => 'float'
	];

	protected $fillable = [
		'name',
		'watts'
	];
}
