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
     * @return string
     * Получение основной валюты счета
     */
    public function getMainCurrency(): string
    {
        return $this->mainCurrency->getCode();
    }

    /**
     * @param CurrencyModel $currency
     * @return void
     * Добавление новой валюты в счет
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
     * Выбор основной валюты счета
     */
    public function setMainCurrency(string $currencyCode): bool
    {
        if (!empty($this->currencies[$currencyCode])) {
            $this->mainCurrency = $this->currencies[$currencyCode];
            return true;
        }
        return false;
    }

    /**
     * @param string $currencyCode
     * @param float $amount
     * @return bool
     * Пополнение средств
     * Проверка суммы пополнения на отрицательные значения в контроллере перед передачей в метод deposit()
     */
    public function deposit(string $currencyCode, float $amount): bool
    {
        if ($amount < 0) {
            return false;
        }
        if (isset($this->balances[$currencyCode])) {
            $this->balances[$currencyCode] += $amount;
            return true;
        }
        return false;
    }

    /**
     * @param string $currencyCode
     * @param float $amount
     * @return float|bool
     * Списание средств
     */
    public function withdraw(string $currencyCode, float $amount): float|bool
    {
        if (!isset($this->balances[$currencyCode])) {
            return false;
        }

        $this->balances[$currencyCode] -= $amount;
        return $amount;
    }

    /**
     * @param string $currencyCode
     * @return mixed
     * Показать баланс по конкретной валюте или суммарный по основной валюте
     */
    public function getBalance(string $currencyCode = ''): mixed
    {
        if ($currencyCode === '') {
            $currencyCode = $this->mainCurrency->getCode();
            if (!isset($this->balances[$currencyCode])) {
                return false;
            }
            $sum = $this->balances[$currencyCode];
            foreach ($this->currencies as $code => $currency) {
                if ($code === $currencyCode) {
                    continue;
                }
                $sum += $currency->convertTo($this->balances[$code], $currencyCode);
            }
            return $sum;
        }
        if (!isset($this->balances[$currencyCode])) {
            return false;
        }
        return $this->balances[$currencyCode];
    }

    /**
     * @param string $fromCurrencyCode
     * @param string $toCurrencyCode
     * @param float $amount
     * @return bool
     * Конвертация валюты
     */
    public function convertCurrency(string $fromCurrencyCode, string $toCurrencyCode, float $amount): bool
    {
        if (!isset($this->balances[$fromCurrencyCode]) || !isset($this->currencies[$toCurrencyCode])) {
            return false;
        }

        $fromCurrency = $this->currencies[$fromCurrencyCode];
        $toCurrency = $this->currencies[$toCurrencyCode];
        $fromAmount = $this->withdraw($fromCurrencyCode, $amount);
        $convertedAmount = $fromCurrency->convertTo($fromAmount, $toCurrency);
        $this->deposit($toCurrencyCode, $convertedAmount);

        return true;
    }

    /**
     * @param string $currencyCode
     * @return bool
     * Отключение валюты
     */
    public function removeCurrency(string $currencyCode): bool
    {
        if (!isset($this->balances[$currencyCode])) {
            return false;
        }
        $balance = $this->balances[$currencyCode];
        $balance = $this->withdraw($currencyCode, $balance);
        $currency = $this->currencies[$currencyCode];
        $this->deposit($this->mainCurrency->getCode(), $currency->convertTo($balance, $this->mainCurrency->getCode()));
        unset($this->balances[$currencyCode]);
        unset($this->currencies[$currencyCode]);

        return true;
    }


    /**
     * @param string $currencyCode
     * @param float $rate
     * @return bool
     */
    public function updateExchangeRate(string $currencyCode, float $rate): bool
    {
        if (!isset($this->currencies[$currencyCode])) {
            return false;
        }
        $this->currencies[$currencyCode]->setExchangeRateToBase($rate);
        return true;
    }

    /**
     * @return array
     */
    public function getCurrencies(): array
    {
        $currencies = [];
        foreach ($this->currencies as $code => $currency) {
            $currencies[] = $code;
        }
        return $currencies;
    }
}