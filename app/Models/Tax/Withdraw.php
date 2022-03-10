<?php

namespace App\Models\Tax;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Classes\Tax\CurrencyConversion;

class Withdraw extends Transfer
{
	/**
	 * Transfer type: business, private
	 *
	 * @var string
	 */
	private string $type;
	private array  $rates;
	private float  $privateTaxPercent;
	private float  $businessTaxPercent;
	private int    $withdrawWeeklyCount;
	private int    $withdrawWeeklyAmount;

	/**
	 * @param int        $key
	 * @param Collection $items
	 * @param            $rates
	 */
	public function __construct(int $key, Collection $items, $rates)
	{
		parent::__construct($key, $items);

		$this->type                 = $this->item['type'];
		$this->rates                = $rates;
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
		$query = $this->_buildPreviousRecordsQuery();

		$amount            = CurrencyConversion::convertToEuro($this->item['amount'], $this->item['currency'], $this->rates);
		$previousDiscounts = 0;

		$query
			->map(function($item, $key) use (&$previousDiscounts) {
				/**
				 * Default currency is EURO, do the processes in that currency
				 */
				$amount = CurrencyConversion::convertToEuro($item['amount'], $item['currency'], $this->rates);

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
		return CurrencyConversion::convertFromEuro($amount, $this->item['currency'], $this->rates);
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
			$amount = CurrencyConversion::convertToEuro($amount, $currency, $this->rates);
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