<?php

namespace App\Models\Tax;

use Illuminate\Support\Collection;

class Withdraw extends Transfer
{
	/**
	 * Tax Percent
	 */
	const BUSINESS_TAX_PERCENT = 0.5;
	const PRIVATE_TAX_PERCENT  = 0.3;

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
			$result = ($this->item['amount'] * self::PRIVATE_TAX_PERCENT) / 100;
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
}