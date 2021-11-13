<?php

use yii\db\Migration;

class m150214_044831_add_foreign_key extends Migration
{
    private const TABLE_USER = '{{%user}}';
    private const TABLE_ROLE = '{{%role}}';
    private const TABLE_ORGANIZATION = '{{%organization}}';
    private const TABLE_ORG_TYPE = '{{%organization_type}}';

    private const FK_ORGANIZATION_TYPE = 'fk_organization_type';
    private const FK_ORGANIZATION_ID = 'fk_organization_id';
    private const FK_ROLE_ID = 'fk_role_id';

    private const REF_COLUMN = 'id';
    private const RESTRICT = 'RESTRICT';

    public function safeUp()
    {
        $this->addForeignKey(
            self::FK_ORGANIZATION_TYPE,
            self::TABLE_ORGANIZATION,
            'type_id',
            self::TABLE_ORG_TYPE,
            self::REF_COLUMN,
            self::RESTRICT
        );

        $this->addForeignKey(
            self::FK_ORGANIZATION_ID,
            self::TABLE_USER,
            'organization_id',
            self::TABLE_ORGANIZATION,
            self::REF_COLUMN,
            self::RESTRICT
        );

        $this->addForeignKey(
            self::FK_ROLE_ID,
            self::TABLE_USER,
            'role_id',
            self::TABLE_ROLE,
            self::REF_COLUMN,
            self::RESTRICT
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(self::FK_ORGANIZATION_TYPE, self::TABLE_ORGANIZATION);
        $this->dropForeignKey(self::FK_ORGANIZATION_ID, self::TABLE_USER);
        $this->dropForeignKey(self::FK_ROLE_ID, self::TABLE_USER);
    }
}