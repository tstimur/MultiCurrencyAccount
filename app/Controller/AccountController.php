<?php

namespace Controller;

use AccountModel;
use CurrencyModel;

class AccountController
{
    private AccountModel $account;

    public function __construct(string $currencyCode, float $rate, AccountModel $account)
    {
        $account = new AccountModel((new CurrencyModel($currencyCode, $rate)));
        $this->account = $account;
    }

    /**
     * @return string
     */
    public function getMainCurrencyOfAccount(): string
    {
        return $this->account->getMainCurrency();
    }

    /**
     * @return AccountModel
     */
    public function getAccount(): AccountModel
    {
        return $this->account;
    }

    public function AddCurrency(string $currencyCode, float $rate): void
    {
        $currency = new CurrencyModel($currencyCode, $rate);
        $this->account->addCurrency($currency);
    }

    /**
     * @param string $currencyCode
     * @return array
     */
    public function setMainCurrency(string $currencyCode): array
    {
        if ($this->account->setMainCurrency($currencyCode) === true) {
            return [
                'status' => 'success',
                'message' => 'Main currency successfully changed to ' . $currencyCode
            ];
        }
        return [
            'status' => 'error',
            'message' => 'Currency not found in account.'
        ];
    }

    /**
     * @param string $currencyCode
     * @param float $amount
     * @return string[]
     */
    public function deposit(string $currencyCode, float $amount): array
    {
        if ($amount < 0) {
            return [
                'status' => 'error',
                'message' => 'The amount cannot be negative.'
            ];
        }
        $this->account->deposit($currencyCode, $amount);
        return [
            'status' => 'success',
            'message' => 'Balance replenished in the amount of ' . $amount
        ];
    }

    /**
     * @param string $currency
     * @param float $amount
     * @return string[]
     */
    public function withdraw(string $currency, float $amount): array
    {
        $amount = (float)$amount;
        if ($amount > $this->account->getBalance($currency)) {
            return [
                'status' => 'error',
                'message' => 'Insufficient balance.'
            ];
        }
        $this->account->withdraw($currency, $amount);
        return [
            'status' => 'success',
            'message' => 'Written off ' . $amount . ' ' . $currency
        ];
    }

    /**
     * @param string $currencyCode
     * @return array|string
     */
    public function getBalance(string $currencyCode = ''): array|string
    {
        if ($this->account->getBalance($currencyCode) === false) {
            return [
                'status' => 'error',
                'message' => 'Currency not found in account.'
            ];
        } elseif ($currencyCode !== '') {
            return 'On account ' . $this->account->getBalance($currencyCode) . ' ' . $this->getMainCurrencyOfAccount();
        } else {
            return 'On account ' . $this->account->getBalance($currencyCode) . ' ' . $currencyCode;
        }
    }

    /**
     * @param string $fromCurrency
     * @param string $toCurrency
     * @param float $amount
     * @return string[]
     */
    public function convertCurrency(string $fromCurrency, string $toCurrency, float $amount): array
    {
        $amount = (float)$amount;
        if ($this->account->getBalance($fromCurrency) < $amount) {
            return [
                'status' => 'error',
                'message' => 'Insufficient balance.'
            ];
        }
        if ($this->account->convertCurrency($fromCurrency, $toCurrency, $amount)) {
            return [
                'status' => 'success',
                'message' => $amount . ' ' . $fromCurrency . ' successfully converted to ' . $toCurrency
            ];
        }
        return [
            'status' => 'error',
            'message' => 'Currency not supported.'
        ];
    }

    /**
     * @param string $currencyCode
     * @return string[]
     */
    public function disableCurrency(string $currencyCode): array
    {
        if ($this->account->removeCurrency($currencyCode)) {
            return [
                'status' => 'success',
                'message' =>
                    $currencyCode .
                    ' is disabled. The balance is converted to the main currency - ' .
                    $this->getMainCurrencyOfAccount()
            ];
        }
        return [
            'status' => 'error',
            'message' => 'Currency not supported.'
        ];
    }
}