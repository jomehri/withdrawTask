<?php

namespace App\Traits\Tax;

use App\Classes\Tax\CSV;
use Illuminate\Http\UploadedFile;

trait TaxTrait
{
	/**
	 * Import file and initialize rows as array
	 *
	 * @param UploadedFile $file
	 *
	 * @return void
	 */
	private function importFile(UploadedFile $file): void
	{
		/**
		 * TODO: you can store physical file on storage if you needed (optional)
		 */

		$this->rows = CSV::getRows($file);
	}
}