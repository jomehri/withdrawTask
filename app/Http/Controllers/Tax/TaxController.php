<?php

namespace App\Http\Controllers\Tax;

use App\Traits\Tax\TaxTrait;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use App\Http\Requests\Tax\TaxProcessValidator;
use Illuminate\Contracts\Foundation\Application;

class TaxController extends Controller
{
	use TaxTrait;

	private array $rows;

	/**
	 * Input CSV form page
	 *
	 * @return Application|Factory|View
	 */
	public function item()
	{
		return view('tax.item');
	}

	/**
	 * Calculations results of withdrawal and deposit taxes for imported CSV file
	 *
	 * @return Application|Factory|View
	 */
	public function store(Request $request, TaxProcessValidator $validator)
	{
		$output = [];

		$this->importFile($request->file('file'));

		dd($this->rows);

		return view('Tax.item', $output);
	}
}
