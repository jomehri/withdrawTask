<?php

namespace App\Classes\Tax;

class CurrencyConversion
{
	/**
	 * Convert other currencies back to EURO
	 *
	 * @param float  $amount
	 * @param string $toCurrency
	 * @param array  $rates
	 *
	 * @return float
	 */
	public static function convertFromEuro(float $amount, string $toCurrency, array $rates): float
	{
		$rate = $rates[$toCurrency];

		return $amount * $rate;
	}

	/**
	 * Convert other currencies back to EURO
	 *
	 * @param float  $amount
	 * @param string $fromCurrency
	 * @param array  $rates
	 *
	 * @return float
	 */
	public static function convertToEuro(float $amount, string $fromCurrency, array $rates): float
	{
		$rate = $rates[$fromCurrency];

		return $amount / $rate;
	}

}