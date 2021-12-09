<?php

namespace apiv1\controllers;

use api\controllers\ActiveBaseController;
use common\models\Point;

/**
 * Class PointController
 * @package apiv1\controllers
 * @author Raul Sauco <sauco.raul@gmail.com>
 */
class PointController extends ActiveBaseController
{
    public $modelClass = Point::class;
}
