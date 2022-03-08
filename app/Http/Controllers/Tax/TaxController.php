<?php

namespace App\Http\Controllers\Tax;

use App\Http\Requests\Tax\TaxProcessValidator;
use Illuminate\Support\Facades\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class TaxController extends Controller
{
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
		$data = [];

		dd('here');

		return view('Tax.item', $data);
	}
}
