<?php
namespace app\components;


use yii\base\Model;

/**
 * Модель стратегии ответа на запрос платежного
 * провайдера взависимости от типа.
 *
 * @package app\components
 */
abstract class PaymentStrategy extends Model implements StrategyInterface
{
    const ERROR_MD5 = 'Invalid MD5.';

    public $user_id;
    public $amount;
    public $md5;

    /**
     * Валидация входных данных провайдера.
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['user_id', 'amount', 'md5'], 'required'],
            ['user_id', 'integer'],
            ['amount', 'double'],
            ['md5', 'string'],
            ['md5', 'trim'],
            ['md5', 'checkMD5']
        ];
    }

    /**
     * Возвращает результат операции для платежной системы.
     *
     * @return string
     */
    public function getResponse()
    {
        if ($this->validate()) {
            return $this->successMessage();
        }

        return $this->errorMessage();
    }

    /**
     * Валидатор MD5.
     *
     * @param $attribute
     */
    public function checkMD5($attribute)
    {
        $current_md5 = md5($this->user_id . $this->amount . $this->getSalt());

        if ($current_md5 != $this->md5) {
            $this->addError($attribute, self::ERROR_MD5);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode([
            'user_id' => $this->user_id,
            'amount' => $this->amount,
            'md5' => $this->md5,
            'strategy' => static::className()
        ]);
    }

    /**
     * Возвращает соль.
     * @return string
     */
    abstract public function getSalt();

    /**
     * Возвращает сообщение об ошибке.
     * @return string
     */
    abstract protected function errorMessage();

    /**
     * Возвращает сообщение об успехе.
     * @return string
     */
    abstract protected function successMessage();
}