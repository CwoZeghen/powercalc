<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Appsetting
 * 
 * @property int $id
 * @property float $cost
 * @property float $peakload
 *
 * @package App\Models
 */
class Appsetting extends Model
{
	protected $table = 'appsetting';
	public $timestamps = false;

	protected $casts = [
		'cost' => 'float',
		'peakload' => 'float'
	];

	protected $fillable = [
		'cost',
		'peakload'
	];
}
