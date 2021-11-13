<?php

use yii\db\Migration;

class m130526_201547_insert_data extends Migration
{
    private const TABLE_ROLE = '{{%role}}';
    private const TABLE_ORGANIZATION = '{{%organization}}';

    public function safeUp()
    {
        $this->batchInsert(self::TABLE_ROLE, ['id', 'name'], [
                                               [1, 'Computer Technician'],
                                               [2, 'Security Officer'],
                                               [3, 'Medical Officer'],
                                               [4, 'Captain'],
                                               [5, 'Pilot'],
                                               [6, 'Engineer'],
                                               [7, 'Electrician'],
                                               [8, 'Hydrologian'],
                                               [9, 'Flight Engineer'],
                                               [10, 'Supplier'],
                                               [11, 'Gardener'],
                                               [12, 'Cook'],
                                               [13, 'Cleaner']
                                           ]
        );

        $this->batchInsert(self::TABLE_ORGANIZATION, ['id', 'name'], [
                                                       [1, 'Station'],
                                                       [2, 'Starship'],
                                                       [3, 'Military Starship'],
                                                       [4, 'Cargo Starship'],
                                                       [5, 'Generation Ship'],
                                                       [6, 'Rest Point'],
                                                       [7, 'Control Point']
                                                   ]
        );
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_ROLE, ['id' => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]);
        $this->delete(self::TABLE_ORGANIZATION, ['id' => 1, 2, 3, 4, 5, 6, 7]);
    }
}