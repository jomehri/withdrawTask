<?php

namespace App\Models\Tax;

use Carbon\Carbon;
use App\Classes\Tax\CurrencyRate;
use Illuminate\Support\Collection;

class Withdraw extends Transfer
{
	/**
	 * Transfer type: business, private
	 *
	 * @var string
	 */
	private string $type;
	private float  $businessTaxPercent;
	private float  $privateTaxPercent;
	private int    $withdrawWeeklyAmount;
	private int    $withdrawWeeklyCount;

	/**
	 * @param int        $key
	 * @param Collection $items
	 */
	public function __construct(int $key, Collection $items)
	{
		parent::__construct($key, $items);

		$this->type                 = $this->item['type'];
		$this->businessTaxPercent   = config('tax.WITHDRAW_BUSINESS_TAX_PERCENT');
		$this->privateTaxPercent    = config('tax.WITHDRAW_PRIVATE_TAX_PERCENT');
		$this->withdrawWeeklyAmount = config('tax.WITHDRAW_TAX_FREE_PRIVATE_WEEKLY_AMOUNT');
		$this->withdrawWeeklyCount  = config('tax.WITHDRAW_TAX_FREE_PRIVATE_WEEKLY_COUNT');
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

			$result = $result * $this->privateTaxPercent;
		}
		else
		{
			$result = $this->item['amount'] * $this->businessTaxPercent;
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
		$previousDiscounts = min($previousDiscounts, $this->withdrawWeeklyAmount);

		/**
		 * Now process current row's discount
		 */
		if($amount + $previousDiscounts < $this->withdrawWeeklyAmount)
		{
			/**
			 * Amount doesn't reach discount limit?
			 */
			$amount = 0;
		}
		elseif($query->count() <= $this->withdrawWeeklyCount)
		{
			$amount = $amount - $this->withdrawWeeklyAmount + $previousDiscounts;
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