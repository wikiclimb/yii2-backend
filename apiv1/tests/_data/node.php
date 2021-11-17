<?php

use common\models\Node;

return [
    [
        'id' => 1,
        'node_type_id' => Node::TYPE_AREA,
        'parent_id' => null,
        'name_id' => 101,
        'description_id' => 101,
        'point_id' => null,
    ],
    [
        'id' => 2,
        'node_type_id' => Node::TYPE_AREA,
        'parent_id' => 1,
        'name_id' => 102,
        'description_id' => 102,
        'point_id' => null,
    ],
    [
        'id' => 3,
        'node_type_id' => Node::TYPE_AREA,
        'parent_id' => 2,
        'name_id' => 103,
        'description_id' => 103,
        'point_id' => null,
    ],
    [
        'id' => 4,
        'node_type_id' => Node::TYPE_AREA,
        'parent_id' => 3,
        'name_id' => 104,
        'description_id' => 104,
        'point_id' => null,
    ],
];
