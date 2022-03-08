<?php

namespace App\Traits\Tax;

use App\Classes\Tax\CSV;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

trait TaxTrait
{
	/**
	 * @param UploadedFile $file
	 *
	 * @return Collection
	 */
	private function prepareRows(UploadedFile $file): Collection
	{
		return $this->prepareFile($file);
	}

	/**
	 * Import file and initialize rows as array
	 *
	 * @param UploadedFile $file
	 *
	 * @return Collection
	 */
	private function prepareFile(UploadedFile $file): Collection
	{
		return CSV::getRows($file);
	}
}