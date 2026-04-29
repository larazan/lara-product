<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    //
    protected $guarded = [
		'id',
		'created_at',
		'updated_at'
	];

    /**
	 * Relationship with the product
	 *
	 * @return array
	 */
	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
