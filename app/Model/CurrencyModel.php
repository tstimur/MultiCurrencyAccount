<?php

class CurrencyModel
{
    private string $code;
    private float $exchangeRate;

    /**
     * @param string $code
     * @param float $exchangeRate
     */
    public function __construct(string $code, float $exchangeRate)
    {
        $this->code = $code;
        $this->exchangeRate = $exchangeRate;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return float
     */
    public function getExchangeRate(): float
    {
        return $this->exchangeRate;
    }

    /**
     * @param float $rate
     * @return void
     */
    public function setExchangeRate(float $rate): void
    {
        $this->exchangeRate = $rate;
    }

    /**
     * @param float $amount
     * @param CurrencyModel $desiredСurrency
     * @return float
     */
    public function convertTo(float $amount, CurrencyModel $desiredСurrency): float
    {
        return $amount * ($this->exchangeRate / $desiredСurrency->getExchangeRate());
    }

}