<?php

namespace App\Models\Tax;

use App\Classes\Tax\CurrencyRate;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class Withdraw extends Transfer
{
	/**
	 * Tax Percent
	 */
	const BUSINESS_TAX_PERCENT = 0.5;
	const PRIVATE_TAX_PERCENT  = 0.3;

	/**
	 * Only this number of First payments are free in each week
	 */
	const NO_TAX_PRIVATE_WEEKLY_COUNT = 3;

	/**
	 * Up to this amount of USD is no-tax
	 */
	const NO_TAX_PRIVATE_WEEKLY_AMOUNT = 1000;

	/**
	 * Transfer type: business, private
	 *
	 * @var string
	 */
	private string $type;

	/**
	 * @param int        $key
	 * @param Collection $items
	 */
	public function __construct(int $key, Collection $items)
	{
		parent::__construct($key, $items);

		$this->type = $this->item['type'];
	}

	/**
	 * @return float
	 */
	public function calculateTax(): float
	{
		if($this->_isPrivate())
		{
			/**
			 * Convert to EURO if in other currencies
			 */
			$amount = $this->_getAmountInEuro();

			$result = $this->getTaxableAmount();

			$result = ($result * self::PRIVATE_TAX_PERCENT) / 100;
		}
		else
		{
			$result = ($this->item['amount'] * self::BUSINESS_TAX_PERCENT) / 100;
		}

		return $this->roundUp($result);
	}

	/**
	 * @return bool
	 */
	private function _isPrivate(): bool
	{
		return $this->type === parent::TYPE_PRIVATE;
	}

	/**
	 * Only this part of withdraw amount has to pay tax, rest is free of tax
	 *
	 * @return float
	 */
	private function getTaxableAmount(): float
	{
		// TODO remove this
//		if($this->key != 5)
//		{
//			return 0;
//		}


		$query = $this->_buildPreviousRecordsQuery();

		$amount            = CurrencyRate::convertToEuro($this->item['amount'], $this->item['currency']);
		$previousDiscounts = 0;

		$query
			->map(function($item, $key) use (&$previousDiscounts) {
				/**
				 * Default currency is EURO, do the processes in that currency
				 */
				$amount = CurrencyRate::convertToEuro($item['amount'], $item['currency']);

				$previousDiscounts += $amount;
			});

		/**
		 * Previous discounts couldn't exceed 1000 EURO
		 */
		$previousDiscounts = min($previousDiscounts, self::NO_TAX_PRIVATE_WEEKLY_AMOUNT);

		/**
		 * Now process current row's discount
		 */
		if($amount + $previousDiscounts < self::NO_TAX_PRIVATE_WEEKLY_AMOUNT)
		{
			/**
			 * Amount doesn't reach discount limit?
			 */
			$amount = 0;
		}
		elseif($query->count() <= self::NO_TAX_PRIVATE_WEEKLY_COUNT)
		{
			$amount = $amount - self::NO_TAX_PRIVATE_WEEKLY_AMOUNT + $previousDiscounts;
		}

		/**
		 * Always convert back to default currency which is EURO
		 */
		return CurrencyRate::convertFromEuro($amount, $this->item['currency']);
	}

	/**
	 * @return float|mixed
	 */
	private function _getAmountInEuro()
	{
		$amount   = $this->item['amount'];
		$currency = $this->item['currency'];

		if($currency !== 'EUR')
		{
			$amount = CurrencyRate::convertToEuro($amount, $currency);
		}

		return $amount;
	}

	/**
	 * @return Collection
	 */
	private function _buildPreviousRecordsQuery(): Collection
	{
		$startOfWeek = Carbon::parse($this->item['date'])->startOfWeek();
		$endOfWeek   = Carbon::parse($this->item['date'])->endOfWeek();

		$query = $this->items
			->where("id", "<", $this->item['id'])
			->where("userId", $this->item['userId'])
			->where("action", Transfer::ACTION_WITHDRAW)
			->whereBetween("date", [$startOfWeek, $endOfWeek]);

		return $query;
	}
}