<?php

declare(strict_types=1);

namespace Zpp\Migration\Templates;

use UnexpectedValueException;

use Yii;

class AlterTableTemplate extends BaseTemplate
{
    protected string $table;

    protected array $removelist = [];
    protected array $addlist = [];
    protected array $alterlist = [];
    protected array $indexlist = [];

    public function safeUp(): void
    {
        if (!isset($this->table)) {
            throw new UnexpectedValueException('this->table must be set.');
        }
        if (empty($this->addlist) && empty($this->alterlist) && empty($this->indexlist) && empty($this->removelist)) {
            throw new UnexpectedValueException('this->*list must be set.');
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

        $this->resolveIndexes($this->table, $this->indexlist);
    }

    public function safeDown(): void
    {
        foreach ($this->addlist as $field => $type) {
            $sql = "SHOW INDEX FROM {$this->table} WHERE Column_name = '$field' AND Key_name like 'fk_%'";
            $queryAll = Yii::$app->db->createCommand($sql)->queryAll();
            if (!empty($queryAll)) {
                $this->dropForeignKey($queryAll[0]['Key_name'], $this->table);
            }
            $this->dropColumn($this->table, $field);
        }
    }
}
