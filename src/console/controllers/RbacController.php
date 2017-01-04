<?php

namespace mobilejazz\yii2\cms\console\controllers;

use mobilejazz\yii2\cms\common\rbac\AuthorRule;
use yii\console\Controller;
use yii\rbac\ManagerInterface;

class RbacController extends Controller
{
    public function actionInit()
    {
        /** @var ManagerInterface $auth */
        $auth = \Yii::$app->authManager;

        // ==============================
        // RULES
        // ==============================
        // Clear rules.
        $auth->removeAllRules();
        // Author rule.
        $author_rule = new AuthorRule;
        $auth->add($author_rule);

        // ==============================
        // PERMISSIONS
        // ==============================
        // Clear all permissions first.
        $auth->removeAllPermissions();

        // Add "createContent" permission
        $createContent              = $auth->createPermission('createContent');
        $createContent->description = 'Create a new Content';
        $auth->add($createContent);

        // Add "updateContent" permission
        $updateContent              = $auth->createPermission('updateContent');
        $updateContent->description = 'Update all the Content';
        $auth->add($updateContent);

        // Add "updateOwnContent" permission
        $updateOwnContent              = $auth->createPermission('updateOwnContent');
        $updateOwnContent->description = 'Update ones Own Content';
        $auth->add($updateOwnContent);
        $auth->addChild($updateOwnContent, $updateContent);

        // Add "uploadMedia" permission
        $addMedia              = $auth->createPermission('uploadMedia');
        $addMedia->description = 'Add Media';
        $auth->add($addMedia);

        // Add "removeMedia" permission
        $removeMedia              = $auth->createPermission('removeMedia');
        $removeMedia->description = 'Remove Media';
        $auth->add($removeMedia);

        // ==============================
        // ROLES
        // ==============================
        // EDITOR
        $editor = $auth->getRole('editor');
        if ( ! $editor)
        {
            $editor = $auth->createRole('editor');
            $auth->add($editor);
        }
        if ( ! $auth->hasChild($editor, $createContent))
        {
            $auth->addChild($editor, $createContent);
        }
        if ( ! $auth->hasChild($editor, $updateOwnContent))
        {
            $auth->addChild($editor, $updateOwnContent);
        }

        // ADMIN
        $admin = $auth->getRole('admin');
        if ( ! $admin)
        {
            $admin = $auth->createRole('admin');
            $auth->add($admin);
        }
        if ( ! $auth->hasChild($admin, $updateContent))
        {
            $auth->addChild($admin, $updateContent);
        }
        if ( ! $auth->hasChild($admin, $editor))
        {
            $auth->addChild($admin, $editor);
        }

        // ==============================
        // REVOKE AND ASSIGN ROLES.
        // ==============================
        $auth->revokeAll(1);
        $auth->assign($admin, 1);
    }
}