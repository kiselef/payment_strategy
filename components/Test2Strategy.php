<?php
namespace app\components;

/**
 * Стратегия для провайдера 2.
 *
 * @package app\components
 */
class Test2Strategy extends PaymentStrategy
{
    public function getSalt()
    {
        return 'hash_salt2';
    }

    protected function errorMessage()
    {
        return 'ERROR';
    }

    protected function successMessage()
    {
        return 'OK';
    }
}