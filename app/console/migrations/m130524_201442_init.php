<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    private const TABLE_USER = '{{%user}}';
    private const TABLE_ROLE = '{{%role}}';
    private const TABLE_ORGANIZATION = '{{%organization}}';
    private const TABLE_ORG_TYPE = '{{%organization_type}}';

    private const IDX_EMAIL = 'idx_email';
    private const IDX_ORGANIZATION = 'idx_organization';
    private const IDX_USER_ROLE_ID = 'idx_user_role_id';
    private const IDX_USER_ACCESS_TOKEN = 'idx_user_access_token';

    /**
     * @throws \yii\db\Exception|\yii\base\Exception
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_USER, [
            'id'                    => $this->primaryKey(),
            'email'                 => $this->string(255)->null(),
            'phone'                 => $this->integer()->null(),
            'username'              => $this->string(255)->null(),
            'password'              => $this->string(255)->null(),
            'role_id'               => $this->integer()->notNull(),
            'organization_id'       => $this->integer()->null(),
            'language'              => $this->string(10)->null()->defaultValue('ru'),
            'status'                => $this->boolean()->null()->defaultValue(true),
            'auth_key'              => $this->string(255)->notNull(),
            'access_token'          => $this->string(255)->null(),
            'created_at'            => $this->timestamp()->notNull(),
            'updated_at'            => $this->timestamp()->null(),
            'banned_at'             => $this->timestamp()->null(),
            'banned_reason'         => $this->string(255)->null(),
            'sms_subscribe'         => $this->boolean()->null()->defaultValue(false),
            'email_subscribe'       => $this->boolean()->null()->defaultValue(false),
            'notice_time_from_time' => $this->timestamp()->null(),
            'notice_time_to_time'   => $this->timestamp()->null(),
        ],                 $tableOptions);

        $this->createIndex(self::IDX_EMAIL, self::TABLE_USER, 'email', true);
        $this->createIndex(self::IDX_ORGANIZATION, self::TABLE_USER, 'organization_id', false);
        $this->createIndex(self::IDX_USER_ROLE_ID, self::TABLE_USER, 'role_id', false);
        $this->createIndex(self::IDX_USER_ACCESS_TOKEN, self::TABLE_USER, 'access_token', false);

        $this->createTable(self::TABLE_ROLE, [
            'id'   => $this->primaryKey(),
            'name' => $this->string(20)->notNull(),
        ],                 $tableOptions);

        $this->createTable(self::TABLE_ORGANIZATION, [
            'id'                => $this->primaryKey(),
            'type_id'           => $this->integer()->notNull(),
            'name'              => $this->string(255)->null(),
            'phone'             => $this->integer()->null(),
            'email'             => $this->string(255)->null(),
            'created_at'        => $this->timestamp()->null()->defaultExpression("CURRENT_TIMESTAMP"),
            'updated_at'        => $this->timestamp()->null(),
            'logo'              => $this->string(255)->null(),
            'rating'            => $this->integer()->null()->defaultValue(0),
            'country'           => $this->string(255)->null(),
            'engineer_id'       => $this->integer()->null(),
            'gmt'               => $this->integer()->null(),
            'lang'              => $this->string(10)->null()->defaultValue('ru'),
            'additional_fields' => $this->json()->null(),
            'blacklist'         => $this->boolean()->null()->defaultValue(false),
            'is_deleted'        => $this->boolean()->null()->defaultValue(false),
        ],                 $tableOptions);

        $this->createTable(self::TABLE_ORG_TYPE, [
            'id'   => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
        ],                 $tableOptions);
    }

    public function safeDown()
    {
        $this->dropIndex(self::IDX_EMAIL, self::TABLE_USER);
        $this->dropIndex(self::IDX_ORGANIZATION, self::TABLE_USER);
        $this->dropIndex(self::IDX_USER_ROLE_ID, self::TABLE_USER);
        $this->dropIndex(self::IDX_USER_ACCESS_TOKEN, self::TABLE_USER);

        $this->dropTable(self::TABLE_USER);
        $this->dropTable(self::TABLE_ROLE);
        $this->dropTable(self::TABLE_ORGANIZATION);
        $this->dropTable(self::TABLE_ORG_TYPE);
    }
}
