<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "node".
 *
 * @property int $id
 * @property int|null $node_type_id
 * @property int|null $parent_id
 * @property int $name_id
 * @property int|null $description_id
 * @property int|null $point_id
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property User $createdBy
 * @property I18nText $description
 * @property I18nString $name
 * @property NodeType $nodeType
 * @property Node[] $nodes
 * @property Node $parent
 * @property Point $point
 * @property User $updatedBy
 */
class Node extends ActiveRecord
{
    public const TYPE_AREA = 1;
    public const TYPE_LOCATION = 2;
    public const TYPE_ROUTE = 3;
    public const TYPE_PITCH = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'node';
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
            [['node_type_id', 'parent_id', 'name_id', 'description_id', 'point_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name_id'], 'required'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['description_id'], 'exist', 'skipOnError' => true, 'targetClass' => I18nText::class, 'targetAttribute' => ['description_id' => 'id']],
            [['name_id'], 'exist', 'skipOnError' => true, 'targetClass' => I18nString::class, 'targetAttribute' => ['name_id' => 'id']],
            [['node_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => NodeType::class, 'targetAttribute' => ['node_type_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Node::class, 'targetAttribute' => ['parent_id' => 'id']],
            [['point_id'], 'exist', 'skipOnError' => true, 'targetClass' => Point::class, 'targetAttribute' => ['point_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'node_type_id' => Yii::t('app', 'Node Type ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'name_id' => Yii::t('app', 'Name ID'),
            'description_id' => Yii::t('app', 'Description ID'),
            'point_id' => Yii::t('app', 'Point ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return ActiveQuery
     */
    public function getCreatedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Description]].
     *
     * @return ActiveQuery
     */
    public function getDescription(): ActiveQuery
    {
        return $this->hasOne(I18nText::class, ['id' => 'description_id']);
    }

    /**
     * Gets query for [[Name]].
     *
     * @return ActiveQuery
     */
    public function getName(): ActiveQuery
    {
        return $this->hasOne(I18nString::class, ['id' => 'name_id']);
    }

    /**
     * Gets query for [[NodeType]].
     *
     * @return ActiveQuery
     */
    public function getNodeType(): ActiveQuery
    {
        return $this->hasOne(NodeType::class, ['id' => 'node_type_id']);
    }

    /**
     * Gets query for [[Nodes]].
     *
     * @return ActiveQuery
     */
    public function getNodes(): ActiveQuery
    {
        return $this->hasMany(Node::class, ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return ActiveQuery
     */
    public function getParent(): ActiveQuery
    {
        return $this->hasOne(Node::class, ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[Point]].
     *
     * @return ActiveQuery
     */
    public function getPoint(): ActiveQuery
    {
        return $this->hasOne(Point::class, ['id' => 'point_id']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return ActiveQuery
     */
    public function getUpdatedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }
}

