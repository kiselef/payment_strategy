<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;

/**
 *
 *
 * @property integer $id ID
 * @property string $name Name
 * @property float $balance Balance
 * @property string $updated_at Updated At
 */
class User extends ActiveRecord
{
    public static function tableName()
    {
        return 'user';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()')
            ],
        ];
    }

    /**
     * Обновляет баланс пользователя user_id на сумму amount.
     *
     * @param $user_id
     * @param $amount
     *
     * @return int
     */
    public static function updateBalance($user_id, $amount)
    {
        $sql = 'UPDATE ' . self::tableName() . ' SET `balance`=`balance`+:amount WHERE `id`=:user_id';

        return \Yii::$app->db->createCommand($sql)
            ->bindValues([
                ':amount' => $amount,
                ':user_id' => $user_id
            ])
            ->execute();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(UserTransaction::className(), ['user_id' => 'id']);
    }
}
