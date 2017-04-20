<?php
namespace app\components;

/**
 * Стратегия для провайдера 1.
 *
 * @package app\components
 */
class Test1Strategy extends PaymentStrategy
{
    public function getSalt()
    {
        return 'hash_salt1';
    }

    protected function errorMessage()
    {
        return '<answer>0</answer>';
    }

    protected function successMessage()
    {
        return '<answer>1</answer>';
    }
}