<?php

declare(strict_types=1);

namespace Zpp\Migration\Templates;

use yii\db\Migration;
use yii\helpers\Inflector;

class BaseTemplate extends Migration
{
    protected const INDEX_T_INDEX = 'index';
    protected const INDEX_T_UNIQUE = 'unique';
    protected const INDEX_T_FOREIGN = 'foreign';

    protected const FK_RESTRICT = 'RESTRICT';
    protected const FK_CASCADE = 'CASCADE';
    protected const FK_SETNULL = 'SET NULL';

    protected function resolveIndexes(string $table, array $indexes): void
    {
        foreach ($indexes as $field => $type) {
            if ($type === self::INDEX_T_INDEX) {
                $this->createIndex(
                    "idx_{$table}_" . Inflector::camel2id($field, '_'), $table, $field
                );
            } elseif ($type === self::INDEX_T_UNIQUE) {
                $this->createIndex(
                    "uiq_{$table}_" . Inflector::camel2id($field, '_'), $table, $field, true
                );
            } elseif ($type === self::INDEX_T_FOREIGN) {
                [$field, $refTable, $refColumn, $onDelete, $onUpdate] = explode(',', $field);
                $this->addForeignKey(
                    "fk_{$table}_" . Inflector::camel2id($field, '_'), $table, $field,
                    $refTable,
                    $refColumn,
                    $onDelete,
                    $onUpdate
                );
            }
        }
    }
}
