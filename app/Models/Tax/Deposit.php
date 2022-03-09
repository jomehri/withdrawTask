<?php

namespace App\Models\Tax;

use Illuminate\Support\Collection;

class Deposit extends Transfer
{
	/**
	 * Tax Percent
	 */
	const TAX_PERCENT = 0.03;

	/**
	 * @param int        $key
	 * @param Collection $items
	 */
	public function __construct(int $key, Collection $items)
	{
		parent::__construct($key, $items);
	}

	/**
	 * @return float
	 */
	public function calculateTax(): float
	{
		$result = ($this->item['amount'] * self::TAX_PERCENT) / 100;

		return $this->roundUp($result);
	}

}