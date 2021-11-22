<?php

namespace console\controllers;

use common\rbac\AppUserIsUserRule;
use Exception;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class RbacController
 * Methods to help manage the application rbac module.
 */
class RbacController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionInit(): void
    {
        $databaseName = Yii::$app->db->dsn;
        Console::output(Console::renderColoredString(
            "%G» Initializing RBAC tables on $databaseName%n"));
        $auth = Yii::$app->authManager;
        Console::output(Console::renderColoredString(
            "%G» Cleaning tables%n"));
        $auth->removeAll();
        Console::output(Console::renderColoredString(
            "%G» Adding roles%n"));
        $adminRole = $auth->createRole('admin');
        $auth->add($adminRole);
        $userRole = $auth->createRole('user');
        $auth->add($userRole);
        $guestRole = $auth->createRole('guest');
        $auth->add($guestRole);
        $auth->addChild($adminRole, $userRole);
        $auth->addChild($userRole, $guestRole);

        Console::output(Console::renderColoredString(
            "%G» Adding rules%n"));

        Console::output(Console::renderColoredString(
            "%G» Adding permissions%n"));

        /* ********************************************************************
         * ****************************** Node ********************************
         * ********************************************************************/
        Console::output('  » Node');
        $createNode = $auth->createPermission('createNode');
        $createNode->description = 'Create a node';
        $auth->add($createNode);
        $auth->addChild($userRole, $createNode);

        $updateNode = $auth->createPermission('updateNode');
        $updateNode->description = 'Update a node';
        $auth->add($updateNode);
        $auth->addChild($userRole, $updateNode);

        $viewNode = $auth->createPermission('viewNode');
        $viewNode->description = 'View a single node details';
        $auth->add($viewNode);
        $auth->addChild($guestRole, $viewNode);

        $listNodes = $auth->createPermission('listNodes');
        $listNodes->description = 'View a list of nodes';
        $auth->add($listNodes);
        $auth->addChild($guestRole, $listNodes);

        $deleteNode = $auth->createPermission('deleteNode');
        $deleteNode->description = 'Delete a node';
        $auth->add($deleteNode);
        $auth->addChild($userRole, $deleteNode);

        /* ********************************************************************
         * ****************************** User ********************************
         * ********************************************************************/
        Console::output('  » User');
        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Create a user';
        $auth->add($createUser);
        $auth->addChild($adminRole, $createUser);

        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Update a user';
        $auth->add($updateUser);
        $auth->addChild($adminRole, $updateUser);

        $viewUser = $auth->createPermission('viewUser');
        $viewUser->description = 'View a single user details';
        $auth->add($viewUser);
        $auth->addChild($adminRole, $viewUser);

        // START // Add app user is user rule
        $appUserIsUserRule = new AppUserIsUserRule();
        $auth->add($appUserIsUserRule);
        $manageSelfProfile = $auth->createPermission('Manage self profile');
        $manageSelfProfile->description = 'Allows an User to view and update their own profile information.';
        $manageSelfProfile->ruleName = $appUserIsUserRule->name;
        $auth->add($manageSelfProfile);

        // Allow an user to view their profile and modify it
        $auth->addChild($manageSelfProfile, $viewUser);
        $auth->addChild($manageSelfProfile, $updateUser);

        $auth->addChild($userRole, $manageSelfProfile);

        // END // Add app user is user rule

        $listUsers = $auth->createPermission('listUsers');
        $listUsers->description = 'View a list of users';
        $auth->add($listUsers);
        $auth->addChild($adminRole, $listUsers);

        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Delete a user';
        $auth->add($deleteUser);
        $auth->addChild($adminRole, $deleteUser);
    }
}
