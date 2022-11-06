<?php

declare(strict_types=1);

namespace Zpp\Migration\Templates;

use UnexpectedValueException;

class CreateTableTemplate extends BaseTemplate
{
    protected string $table;
    protected string $comment;
    protected bool $use_Id_as_PrimaryKey = true;
    protected bool $use_bigint_for_Id = false;

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
            if ($this->use_bigint_for_Id) {
                $c['id'] = 'bigint NOT NULL AUTO_INCREMENT PRIMARY KEY';
            } else {
                $c['id'] = 'int NOT NULL AUTO_INCREMENT PRIMARY KEY';
            }
        }

        $this->createTable(
            $this->table,
            array_merge($c, $this->addlist),
            "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci COMMENT='{$this->comment}'"
        );

        $this->createIndexes($this->table, $this->indexlist);

        return true;
    }

    public function safeDown(): bool
    {
        $this->dropTable($this->table);

        return true;
    }

    protected function province_enum(): string
    {
        return 'ENUM("'
            . implode(
                '","',
                [
                    '北京',
                    '上海',
                    '天津',
                    '重庆',

                    '内蒙古',
                    '宁夏',
                    '西藏',
                    '新疆',
                    '广西',

                    '河北',
                    '山西',
                    '辽宁',
                    '吉林',
                    '黑龙江',
                    '江苏',
                    '浙江',
                    '安徽',
                    '福建',
                    '江西',
                    '山东',
                    '河南',
                    '湖北',
                    '湖南',
                    '广东',
                    '海南',
                    '四川',
                    '贵州',
                    '云南',
                    '陕西',
                    '甘肃',
                    '青海',
                ]
            )
            . '")';
    }
}
