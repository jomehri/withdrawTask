<?php

namespace App\Classes\Tax;

use Illuminate\Http\UploadedFile;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class CSV
{
	/**
	 * Get CSV file content as a multi row Array
	 *
	 * @param UploadedFile $file
	 *
	 * @return Collection
	 */
	public static function getRows(UploadedFile $file): Collection
	{
		$items = Excel::toCollection(new Collection(), $file);

		return $items->values()[0];
	}
}
