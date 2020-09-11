<?php declare(strict_types=1);

namespace app\migrations\templates;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\Migration;
use yii\helpers\Inflector;

class AlterTableTemplate extends Migration
{
    protected const INDEX_TYPE_INDEX = 'index';
    protected const INDEX_TYPE_UNIQUE = 'unique';
    protected const INDEX_TYPE_FOREIGN = 'foreign';

    protected const ON_DELETE_CASCADE = 'CASCADE';
    protected const ON_DELETE_SETNULL = 'SET NULL';
    protected const ON_DELETE_RESTRICT = 'RESTRICT';
    protected const ON_UPDATE_CASCADE = 'CASCADE';
    protected const ON_UPDATE_SETNULL = 'SET NULL';
    protected const ON_UPDATE_RESTRICT = 'RESTRICT';

    protected $table;

    protected $removelist = [];
    protected $addlist = [];
    protected $alterlist = [];
    protected $indexlist = [];

    public function safeUp(): void
    {
        if (!$this->table) {
            throw new InvalidConfigException('this->table must be set.');
        }
        if (empty($this->addlist) && empty($this->alterlist) && empty($this->indexlist) && empty($this->removelist)) {
            throw new InvalidConfigException('this->columns4* must be set.');
        }

        foreach ($this->removelist as $field) {
            $this->dropColumn($this->table, $field);
        }

        foreach ($this->addlist as $field => $type) {
            $this->addColumn($this->table, $field, $type);
        }
        foreach ($this->alterlist as $field => $type) {
            $this->alterColumn($this->table, $field, $type);
        }

        foreach ($this->indexlist as $field => $type) {
            if ($type === self::INDEX_TYPE_INDEX) {
                $this->createIndex(
                    'idx_' . Inflector::camel2id($field, '_'), $this->table, $field
                );
            } elseif ($type === self::INDEX_TYPE_UNIQUE) {
                $this->createIndex(
                    'uiq_' . Inflector::camel2id($field, '_'), $this->table, $field, true
                );
            } elseif ($type === self::INDEX_TYPE_FOREIGN) {
                [$field, $refTable, $refColumn, $onDelete, $onUpdate] = explode(',', $field);
                $kprefix = 'fk_' . trim($this->table, '{}%') . '_';
                $this->addForeignKey(
                    $kprefix . Inflector::camel2id($field, '_'),
                    $this->table,
                    $field,
                    $refTable,
                    $refColumn,
                    $onDelete,
                    $onUpdate
                );
            }
        }
    }

    public function safeDown(): void
    {
        foreach ($this->addlist as $field => $type) {
            $sql = "SHOW INDEX FROM $this->table WHERE Column_name = '$field' AND Key_name like 'fk_%'";
            $queryAll = Yii::$app->db->createCommand($sql)->queryAll();
            if (!empty($queryAll)) {
                $this->dropForeignKey($queryAll[0]['Key_name'], $this->table);
            }
            $this->dropColumn($this->table, $field);
        }
    }
}
