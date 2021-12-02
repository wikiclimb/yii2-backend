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

    /**
     * Get the current rating of a node based on the average of user's votes.
     * Use this wrapper instead of directly querying the database to help
     * propagate any future changes.
     * @param Node $node
     * @return float
     */
    public static function getRating(Node $node): float
    {
        return round($node->getNodeRatings()->average('rating'), 1);
    }

    /**
     * Get the current number of user ratings for a given Node.
     * Use this wrapper instead of directly querying the database to help
     * propagate any future changes.
     * @param Node $node
     * @return int
     */
    public static function getRatingsCount(Node $node): int
    {
        return $node->getNodeRatings()->count();
    }
}
