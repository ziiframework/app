<?php

declare(strict_types=1);

namespace Zpp\Migration\Templates;

use yii\base\InvalidValueException;
use yii\db\Migration;
use function Symfony\Component\String\u;

class BaseTemplate extends Migration
{
    protected const INDEX_T_INDEX = 'index';
    protected const INDEX_T_UNIQUE = 'unique';
    protected const INDEX_T_FOREIGN = 'foreign';

    protected const FK_RESTRICT = 'RESTRICT';
    protected const FK_CASCADE = 'CASCADE';
    protected const FK_SETNULL = 'SET NULL';

    protected function createIndexes(string $table, array $indexes): void
    {
        foreach ($indexes as $field => $type) {
            if ($type === self::INDEX_T_INDEX) {
                $this->createIndex(
                    "idx_{$table}_" . u($field)->snake()->toString(),
                    $table,
                    $field
                );
            } elseif ($type === self::INDEX_T_UNIQUE) {
                $t_name = "uiq_{$table}_" . u($field)->snake()->toString();

                if (str_contains($field, ',')) {
                    $field = explode(',', $field);
                }

                $this->createIndex(
                    $t_name,
                    $table,
                    $field,
                    true
                );
            } elseif ($type === self::INDEX_T_FOREIGN) {
                [$field, $refTable, $refColumn, $onDelete, $onUpdate] = explode(',', $field);
                $this->addForeignKey(
                    "fk_{$table}_" . u($field)->snake()->toString(),
                    $table,
                    $field,
                    $refTable,
                    $refColumn,
                    $onDelete,
                    $onUpdate
                );
            } else {
                throw new InvalidValueException('Invalid INDEX type');
            }
        }
    }

    protected function removeIndexes(string $table, array $indexes): void
    {
        foreach ($indexes as $field => $type) {
            if ($type === self::INDEX_T_INDEX) {
                $this->dropIndex(
                    "idx_{$table}_" . u($field)->snake()->toString(),
                    $table
                );
            } elseif ($type === self::INDEX_T_UNIQUE) {
                $t_name = "uiq_{$table}_" . u($field)->snake()->toString();

                $this->dropIndex(
                    $t_name,
                    $table
                );
            } elseif ($type === self::INDEX_T_FOREIGN) {
                $this->dropForeignKey(
                    "fk_{$table}_" . u($field)->snake()->toString(),
                    $table
                );
            } else {
                throw new InvalidValueException('Invalid INDEX type');
            }
        }
    }
}
