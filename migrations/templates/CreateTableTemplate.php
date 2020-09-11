<?php declare(strict_types=1);

namespace app\migrations\templates;

use yii\base\InvalidConfigException;
use yii\db\Migration;
use yii\helpers\Inflector;

class CreateTableTemplate extends Migration
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
    protected $comment;

    protected $addlist = [];
    protected $indexlist = [];

    /**
     * {@inheritdoc}
     */
    public function safeUp(): bool
    {
        if (!$this->table) {
            throw new InvalidConfigException('this->table must be set.');
        }
        if (!$this->comment) {
            throw new InvalidConfigException('this->comment must be set.');
        }
        if (empty($this->addlist)) {
            throw new InvalidConfigException('this->addlist must be set.');
        }

        $c = [];
        $c['id'] = 'int NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT "ID"';
//        $z = $this->withDefault ? [
//            'ip' => $this->string(254)->null()->comment('IP'),
//            'cu' => $this->integer()->null()->comment('CU'),
//            'uu' => $this->integer()->null()->comment('UU'),
//            'ca' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "CA"',
//            'ua' => 'TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP COMMENT "UA"',
//        ] : [];

        $this->createTable(
            $this->table,
            array_merge($c, $this->addlist),
            "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci COMMENT='{$this->comment}'"
        );

        foreach ($this->indexlist as $field => $type) {
            if ($type === self::INDEX_TYPE_INDEX) {
                $kprefix = 'idx_' . trim($this->table, '{}%') . '_';
                $this->createIndex(
                    $kprefix . Inflector::camel2id($field, '_'),
                    $this->table,
                    $field
                );
            } elseif ($type === self::INDEX_TYPE_UNIQUE) {
                $kprefix = 'uiq_' . trim($this->table, '{}%') . '_';
                $this->createIndex(
                    $kprefix . Inflector::camel2id($field, '_'),
                    $this->table,
                    $field,
                    true
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

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): bool
    {
        $this->dropTable($this->table);

        return true;
    }
}
