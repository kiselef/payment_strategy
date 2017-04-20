<?php

namespace app\components;

use app\models\User;
use app\models\UserTransaction;

/**
 * Обработчик запроса провайдера.
 *
 * @property PaymentStrategy $strategy Объект-стратегия для работы с провайдером
 * @property string $response Ответ для провайдера
 * @property array $errors Список ошибок
 */
class Payer
{
    private $strategy;
    private $response;
    private $errors;

    function __construct(PaymentStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * Возвращает результат обработки данных для соответствующего провайдера.
     * Если ошибок нет, обновляет баланс пользователя и сохраняет транзакцию.
     *
     * @return string
     */
    public function pay()
    {
        Logger::log($this->strategy, 'Request params');

        $this->response = $this->strategy->getResponse();
        Logger::log($this->response, 'Response');

        $this->errors = $this->strategy->getErrors();
        if (empty($this->errors)) {
            $is_update = User::updateBalance($this->strategy->user_id, $this->strategy->amount);
            Logger::log($is_update, 'Is update');

            $is_transact = (new UserTransaction([
                'user_id' => $this->strategy->user_id,
                'amount' => $this->strategy->amount
            ]))->save();
            Logger::log($is_transact, 'Is transact');
        } else {
            Logger::log($this->errors, 'Errors');
        }

        return $this->response;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}