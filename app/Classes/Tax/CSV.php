<?php

namespace App\Classes\Tax;

use Carbon\Carbon;
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
		/**
		 * Read Excel rows
		 */
		$items = Excel::toCollection(new Collection(), $file);

		/**
		 * build key names (key name translation)
		 */
		$items = self::prepareKeyNames($items->values()[0]);

		/**
		 * Sort from oldest to newest, in case input file is not sorted by default
		 */
		$items = self::sortByDateAscending($items);

		return $items->values();
	}

	/**
	 * @param Collection $items
	 *
	 * @return Collection
	 */
	private static function prepareKeyNames(Collection $items): Collection
	{
		$items->transform(function($item, $key) {

			return Collect(
				[
					'id'       => $key,
					'date'     => $item[0],
					'userId'   => $item[1],
					'type'     => $item[2],
					'action'   => $item[3],
					'amount'   => $item[4],
					'taxable'  => $item[4],
					'currency' => $item[5],
					'tax'      => null,
				]
			);
		});

		return $items;
	}

	/**
	 * @param Collection $items
	 *
	 * @return Collection
	 */
	private static function sortByDateAscending(Collection $items): Collection
	{
		return $items->sortBy('date');
	}
}
