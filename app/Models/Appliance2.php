<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Appliance2
 * 
 * @property int $id
 * @property string|null $name
 * @property float|null $watts
 *
 * @package App\Models
 */
class Appliance2 extends Model
{
	protected $table = 'appliance2';
	public $timestamps = false;

	protected $casts = [
		'watts' => 'float'
	];

	protected $fillable = [
		'name',
		'watts'
	];
}
