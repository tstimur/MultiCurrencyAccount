<?php


use PHPUnit\Framework\TestCase;

class AccountModelTest extends TestCase
{


    /**
     * @return void
     */
    public function testDeposit()
    {
        $account = new AccountModel(new Currency('RUB', 1));
        $account->addCurrency(new Currency('EUR', 80));
        $account->addCurrency(new Currency('USD', 70));

        $account->setMainCurrency('RUB');

        $account->getCurrencies();

        $account->deposit('RUB', 1000);
        $account->deposit('EUR', 50);
        $account->deposit('USD', 40);

        $this->assertEquals(1000, $account->getBalance('RUB'));
        $this->assertEquals(50, $account->getBalance('EUR'));
        $this->assertEquals(40, $account->getBalance('USD'));

    }

    public function testGetBalance()
    {

    }
}
