<?php

namespace App\Models\Tax;

use Illuminate\Support\Collection;

class Deposit extends Transfer
{
	private float $taxPercent;

	/**
	 * @param int        $key
	 * @param Collection $items
	 */
	public function __construct(int $key, Collection $items)
	{
		parent::__construct($key, $items);

		$this->taxPercent = config('tax.DEPOSIT_TAX_PERCENT');
	}

	/**
	 * @return float
	 */
	public function calculateTax(): float
	{
		$result = $this->item['amount'] * $this->taxPercent;

		return $this->roundUp($result);
	}

}