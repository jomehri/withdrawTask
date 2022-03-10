<?php

return [
	'WITHDRAW_BUSINESS_TAX_PERCENT'           => env('WITHDRAW_BUSINESS_TAX_PERCENT'),
	'WITHDRAW_PRIVATE_TAX_PERCENT'            => env('WITHDRAW_PRIVATE_TAX_PERCENT'),
	'DEPOSIT_TAX_PERCENT'                     => env('DEPOSIT_TAX_PERCENT'),

	/**
	 * Up to this amount of USD is no-tax
	 */
	'WITHDRAW_TAX_FREE_PRIVATE_WEEKLY_AMOUNT' => env('WITHDRAW_TAX_FREE_PRIVATE_WEEKLY_AMOUNT'),

	/**
	 * Only this number of payments are free in each week
	 */
	'WITHDRAW_TAX_FREE_PRIVATE_WEEKLY_COUNT'  => env('WITHDRAW_TAX_FREE_PRIVATE_WEEKLY_COUNT'),

	/**
	 * boolean, if true: fetches conversion rates online, else use offline conversion rates as given in assignment body
	 */
	'ONLINE_CONVERSION_RATES'                 => env('ONLINE_CONVERSION_RATES'),

	/**
	 * The URL of json live conversion rates
	 */
	'ONLINE_CONVERSION_URL'                   => env('ONLINE_CONVERSION_URL'),
];
