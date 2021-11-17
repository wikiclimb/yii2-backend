<?php

namespace common\helpers;

use common\models\Node;

/**
 * Class NodeHelper
 * Static helpers for the Node model.
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class NodeHelper
{
    /**
     * Generate an array with the names of the given node's ancestors.
     * @param Node $node
     * @return array
     */
    public static function getNameBreadcrumbs(Node $node): array
    {
        $breadcrumbs = [];
        while (($node = $node->parent) !== null) {
            array_unshift($breadcrumbs, $node->name->toString());
        }
        return $breadcrumbs;
    }
}
