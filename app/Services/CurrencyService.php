<?php

namespace App\Services;

use App\Models\currencies as Currency;
use App\Models\shippingRates as ShippingRate;

class CurrencyService
{
    /**
     * Converts an amount in USD to the specified currency ID.
     */
    public function convertUsdToCurrency(float $amount, int $currencyId): float
    {
        $currency = Currency::find($currencyId);
        if (!$currency || strtoupper($currency->currency) === 'USD') {
            return $amount;
        }

        $rate = (float) $currency->usdRate;
        return $amount * $rate;
    }

    /**
     * Converts an amount in the specified currency ID back to USD.
     */
    public function convertCurrencyToUsd(float $amount, int $currencyId): float
    {
        $currency = Currency::find($currencyId);
        if (!$currency || strtoupper($currency->currency) === 'USD') {
            return $amount;
        }

        $rate = (float) $currency->usdRate;
        return $rate != 0 ? $amount / $rate : 0.0;
    }

    /**
     * Calculates shipping cost based on parameters and returns it in the requested currency ID.
     */
    public function calculateShippingCost(float $weight, string $shippingType, string $from, string $to, int $currencyId): float
    {
        // Fetch matching rate (stored in USD)
        $rate = ShippingRate::where('shtype', $shippingType)
            ->where('from', $from)
            ->where('to', $to)
            ->where('weight_from', '<=', $weight)
            ->where('Weight_to', '>=', $weight)
            ->first();

        // Fallback to highest weight range if not in specific range
        if (!$rate) {
            $rate = ShippingRate::where('shtype', $shippingType)
                ->where('from', $from)
                ->where('to', $to)
                ->orderBy('Weight_to', 'desc')
                ->first();
        }

        $usdPricePerUnit = $rate ? (float) $rate->price : 0.0;
        $totalUsdCost = $weight * $usdPricePerUnit;

        return $this->convertUsdToCurrency($totalUsdCost, $currencyId);
    }

    /**
     * Calculates the total cost of all contents in the order currency ID.
     */
    public function calculateOrderContentsTotal($packages, string $shippingType, string $from, string $to, int $orderCurrencyId): float
    {
        $totalUsd = 0.0;
        foreach ($packages as $package) {
            if ($package->price !== null && (float) $package->price > 0) {
                $totalUsd += (float) $package->price;
            } else {
                $rate = ShippingRate::where('shtype', $shippingType)
                    ->where('from', $from)
                    ->where('to', $to)
                    ->where('weight_from', '<=', $package->weight)
                    ->where('Weight_to', '>=', $package->weight)
                    ->first();

                if (!$rate) {
                    $rate = ShippingRate::where('shtype', $shippingType)
                        ->where('from', $from)
                        ->where('to', $to)
                        ->orderBy('Weight_to', 'desc')
                        ->first();
                }

                $usdPricePerUnit = $rate ? (float) $rate->price : 0.0;
                $totalUsd += ($package->weight * $usdPricePerUnit);
            }
        }

        return $this->convertUsdToCurrency($totalUsd, $orderCurrencyId);
    }

    /**
     * Calculates the total cost of all expenses in the order currency.
     */
    public function calculateOrderExpensesTotal($expenses): float
    {
        $totalInOrderCurrency = 0.0;
        foreach ($expenses as $expense) {
            $totalInOrderCurrency += (float) $expense->amount;
        }
        return $totalInOrderCurrency;
    }

    /**
     * Calculates the total cost of all shipment services in the order currency.
     */
    public function calculateOrderServicesTotal($shipmentServices, int $orderCurrencyId): float
    {
        $totalUsd = 0.0;
        if ($shipmentServices) {
            foreach ($shipmentServices as $service) {
                $totalUsd += (float) $service->price * (int) $service->quantity;
            }
        }
        return $this->convertUsdToCurrency($totalUsd, $orderCurrencyId);
    }

    /**
     * Calculates the final invoice total (Contents + Expenses + Services).
     */
    public function calculateFinalInvoice($packages, $expenses, string $shippingType, string $from, string $to, int $orderCurrencyId, $shipmentServices = []): float
    {
        $contentsTotal = $this->calculateOrderContentsTotal($packages, $shippingType, $from, $to, $orderCurrencyId);
        $expensesTotal = $this->calculateOrderExpensesTotal($expenses);
        $servicesTotal = $this->calculateOrderServicesTotal($shipmentServices, $orderCurrencyId);

        return $contentsTotal + $expensesTotal + $servicesTotal;
    }
}
