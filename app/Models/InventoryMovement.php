<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
	protected $fillable = [
		'product_variant_id',
		'movement_type',
		'quantity',
		'quantity_before',
		'quantity_after',
		'reference_type',
		'reference_id',
		'reason',
		'user_id',
	];

	public function variant(): BelongsTo
	{
		return $this->belongsTo(ProductVariant::class, 'product_variant_id');
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}


