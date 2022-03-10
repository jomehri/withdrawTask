<?php

namespace App\Traits\Tax;

use App\Classes\Tax\CSV;
use App\Classes\Tax\Rates;
use App\Models\Tax\Deposit;
use App\Models\Tax\Transfer;
use App\Models\Tax\Withdraw;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

trait TaxTrait
{
	/**
	 * Prepare all rows and do the math calculations
	 *
	 * @param UploadedFile $file
	 *
	 * @return Collection
	 */
	public function prepareRows(UploadedFile $file): Collection
	{
		$rows = $this->prepareFile($file);

		return $this->calculateTaxes($rows);
	}

	/**
	 * Import file and initialize rows as array
	 *
	 * @param UploadedFile $file
	 *
	 * @return Collection
	 */
	public function prepareFile(UploadedFile $file): Collection
	{
		return CSV::getRows($file);
	}

	/**
	 * Calculate Taxes line by line and output
	 *
	 * @param Collection $items
	 *
	 * @return Collection
	 */
	private function calculateTaxes(Collection $items): Collection
	{
		$ratesClass = new Rates();
		$rates      = $ratesClass->rates;

		$items->map(function(&$item, $key) use ($items, $rates) {

			/**
			 * Each action has its own class and its own formula: deposit, withdraw
			 */
			if($this->_isDeposit($item))
			{
				$transfer = new Deposit($key, $items, $rates);
			}
			else
			{
				$transfer = new Withdraw($key, $items, $rates);
			}

			$item['tax'] = $transfer->calculateTax();
		});

		return $items;
	}

	/**
	 * @param object $item
	 *
	 * @return bool
	 */
	private function _isDeposit(object $item): bool
	{
		return $item['action'] === Transfer::ACTION_DEPOSIT;
	}

}