<?php

class AccountModel
{
    private array $balances = [];
    private CurrencyModel $mainCurrency;
    private array $currencies = [];

    /**
     * @param CurrencyModel $mainCurrency
     */
    public function __construct(CurrencyModel $mainCurrency)
    {
        $this->mainCurrency = $mainCurrency;
        $this->addCurrency($mainCurrency);
    }

    /**
     * @param CurrencyModel $currency
     * @return void
     */
    public function addCurrency(CurrencyModel $currency): void
    {
        $this->currencies[$currency->getCode()] = $currency;
        if (!empty($this->balances[$currency->getCode()])) {
            $this->balances[$currency->getCode()] = 0.00;
        }
    }

    /**
     * @param string $currencyCode
     * @return bool
     */
    public function setMainCurrency(string $currencyCode): bool
    {
        if (!empty($this->currencies[$currencyCode])) {
            $this->mainCurrency = $this->currencies[$currencyCode];
            return true;
        }
        return false;
    }
}