<?php

declare(strict_types=1);

namespace Zpp\Models;

use yii\behaviors\AttributeTypecastBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id ID
 */
class BaseTableModel extends ActiveRecord
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
}
