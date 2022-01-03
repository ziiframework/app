<?php

declare(strict_types=1);

namespace Zpp\Models;

use Yii;
use yii\behaviors\AttributeTypecastBehavior;
use yii\db\ActiveRecord;
use yii\web\Application as WebApplication;

/**
 * @property int $id Primary Key
 * @property string $client_ip Client IP (may not exist)
 * @property string $created_at Created time (may not exist)
 */
abstract class BaseModel extends ActiveRecord
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['typecast'] = [
            'class' => AttributeTypecastBehavior::class,
            'attributeTypes' => [],
            'typecastAfterValidate' => true,
            'typecastBeforeSave' => false,
            'typecastAfterSave' => false,
            'typecastAfterFind' => true,
        ];

        return $behaviors;
    }

    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'id' => 'ID',
        ]);
    }

    public function beforeSave($isNewRecord): bool
    {
        if (!parent::beforeSave($isNewRecord)) {
            return false;
        }

        if ($isNewRecord) {
            if ($this->canSetProperty('created_at')) {
                $this->created_at = date('Y-m-d H:i:s');
            }
            if (Yii::$app instanceof WebApplication) {
                $clientIp = Yii::$app->getRequest()->getUserIP();
                if ($clientIp !== null && $this->canSetProperty('client_ip')) {
                    $this->client_ip = $clientIp;
                }
            }
        }

        return true;
    }
}
