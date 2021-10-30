<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "node_type".
 *
 * @property int $id
 * @property int $name_id
 *
 * @property Node[] $nodes
 */
class NodeType extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'node_type';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name_id'], 'required'],
            [['name_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name_id' => Yii::t('app', 'Name ID'),
        ];
    }

    /**
     * Gets query for [[Nodes]].
     *
     * @return ActiveQuery
     */
    public function getNodes(): ActiveQuery
    {
        return $this->hasMany(Node::class, ['node_type_id' => 'id']);
    }
}

