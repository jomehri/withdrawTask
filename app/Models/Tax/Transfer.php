<?php

namespace App\Models\Tax;

use Illuminate\Support\Collection;

abstract class Transfer
{
	public const ACTION_DEPOSIT  = 'deposit';
	public const ACTION_WITHDRAW = 'withdraw';

	public const TYPE_PRIVATE  = 'private';
	public const TYPE_BUSINESS = 'business';

	/**
	 * Taxes will be rounded up, up to this number of decimal places
	 * Do not add 0, provide integers greater than 0 only
	 *
	 * TODO add more currency decimal points if you want to skip the default
	 */
	protected const CURRENCY_ROUND_UP_DECIMAL_PLACES = [
		'EUR' => 2,
		'USD' => 2,
	];

	/**
	 * @var int Key of selected row to be calculated
	 */
	protected int $key;

	/**
	 * @var Collection Selected item row
	 */
	protected Collection $item;

	/**
	 * @var Collection All items
	 */
	protected Collection $items;

	/**
	 * @var int|null Up to this number of decimal places should be rounded up
	 */
	protected ?int $roundUpDecimalPlaces;

	/**
	 * @param int        $key
	 * @param Collection $items
	 */
	protected function __construct(int $key, Collection $items)
	{
		$this->key   = $key;
		$this->items = $items;
		$this->item  = $items[$key];

		/**
		 * Taxes will be rounded up to this number of decimal places, default is 1
		 */
		$this->roundUpDecimalPlaces = self::CURRENCY_ROUND_UP_DECIMAL_PLACES[$this->item['currency']] ?? null;
	}

	/**
	 * @param float $amount
	 *
	 * @return float
	 */
	protected function roundUp(float $amount): float
	{
		/**
		 * No round up decimal places defined yet? do normal ceil instead
		 */
		if(!$this->roundUpDecimalPlaces)
		{
			return ceil($amount);
		}

		return round($amount, $this->roundUpDecimalPlaces);
	}

	/**
	 * @return float
	 */
	abstract public function calculateTax(): float;

}