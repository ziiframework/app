<?php

declare(strict_types=1);

namespace Zpp\Migration\Templates;

use UnexpectedValueException;
use Webmozart\Assert\Assert;
use Yii;

class AlterTableTemplate extends BaseTemplate
{
    protected string $table;

    protected array $removelist = [];
    protected array $removeindexlist = [];

    protected array $addlist = [];
    protected array $alterlist = [];
    protected array $indexlist = [];

    public function safeUp(): void
    {
        if (!isset($this->table)) {
            throw new UnexpectedValueException('this->table must be set.');
        }

        if (
            empty($this->addlist)
            && empty($this->alterlist)
            && empty($this->indexlist)
            && empty($this->removelist)
            && empty($this->removeindexlist)
        ) {
            throw new UnexpectedValueException('this->*list must be set.');
        }

        $this->removeIndexes($this->table, $this->removeindexlist);

        foreach ($this->removelist as $field) {
            // remove foreign key first
            $sql = "SHOW INDEX FROM `" . $this->table . "` WHERE `Column_name` = '$field'";
            $indexes = Yii::$app->db->createCommand($sql)->queryAll();

            Assert::isArray($indexes);

            foreach ($indexes as $t_index) {
                if (str_starts_with($t_index['Key_name'], 'fk_')) {
                    $this->dropForeignKey($t_index['Key_name'], $this->table);
                }
            }

            $this->dropColumn($this->table, $field);
        }

        foreach ($this->addlist as $field => $type) {
            $this->addColumn($this->table, $field, $type);
        }

        foreach ($this->alterlist as $field => $type) {
            $this->alterColumn($this->table, $field, $type);
        }

        $this->createIndexes($this->table, $this->indexlist);
    }

    public function safeDown(): void
    {
        $this->removeIndexes($this->table, $this->indexlist);

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
