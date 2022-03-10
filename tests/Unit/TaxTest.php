<?php

namespace Tests\Unit;

use App\Http\Controllers\Tax\TaxController;
use App\Traits\Tax\TaxTrait;
use Tests\TestCase;
use App\Classes\Tax\CSV;
use Illuminate\Http\UploadedFile;

class TaxTest extends TestCase
{
	/**
	 * Automation test for provided input and output in the body of assignment question
	 *
	 * @return void
	 */
	public function test_taxes()
	{
		$file = new UploadedFile('public/input.csv', 'input.csv');

		$taxObj = new TaxController();
		$items  = $taxObj->prepareRows($file);

		$calculatedResult = $items->pluck('tax')->toArray();
		$correctResult    = $this->_getCorrectResult();

		/**
		 * Both should be the same
		 */
		if($calculatedResult != $correctResult)
		{
			$this->fail('Calculated results are not the same as provided in the assignment body');
		}

		$this->assertTrue(true);
	}

	/**
	 * @return string[]
	 */
	private function _getCorrectResult(): array
	{
		return [
			0.60,
			3.00,
			0.00,
			0.06,
			1.50,
			0,
			0.70,
			0.30,
			0.30,
			3.00,
			0.00,
			0.00,
			8612,
		];
	}

}
