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
    [
        'id' => 5,
        'node_type_id' => Node::TYPE_AREA,
        'parent_id' => null,
        'name_id' => 105,
        'description_id' => 105,
        'point_id' => null,
    ],
    [
        'id' => 6,
        'node_type_id' => Node::TYPE_AREA,
        'parent_id' => null,
        'name_id' => 106,
        'description_id' => 106,
        'point_id' => 1,
    ],
    [
        'id' => 7,
        'node_type_id' => Node::TYPE_ROUTE,
        'parent_id' => null,
        'name_id' => 107,
        'description_id' => 107,
        'point_id' => 2,
    ],
];
