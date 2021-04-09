<?php

declare(strict_types=1);

namespace Zpp\Migration\Templates;

use UnexpectedValueException;

class CreateTableTemplate extends BaseTemplate
{
    protected string $table;
    protected string $comment;
    protected bool $use_Id_as_PrimaryKey = true;

    protected array $addlist = [];
    protected array $indexlist = [];

    public function safeUp(): bool
    {
        if (!isset($this->table)) {
            throw new UnexpectedValueException('this->table must be set.');
        }
        if (!$this->comment) {
            throw new UnexpectedValueException('this->comment must be set.');
        }
        if (empty($this->addlist)) {
            throw new UnexpectedValueException('this->addlist must be set.');
        }

        $c = [];
        if ($this->use_Id_as_PrimaryKey) {
            $c['id'] = 'int NOT NULL AUTO_INCREMENT PRIMARY KEY';
        }

        $this->createTable(
            $this->table,
            array_merge($c, $this->addlist),
            "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci COMMENT='{$this->comment}'"
        );

        $this->resolveIndexes($this->table, $this->indexlist);

        return true;
    }

    public function safeDown(): bool
    {
        $this->dropTable($this->table);

        return true;
    }
}
