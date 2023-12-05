<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Addon
 * 
 * @property int $id
 * @property string $name
 * @property float $cost
 * @property string $description
 *
 * @package App\Models
 */
class Addon extends Model
{
	protected $table = 'addons';
	public $timestamps = false;

	protected $casts = [
		'cost' => 'float'
	];

	protected $fillable = [
		'name',
		'cost',
		'description'
	];
}
