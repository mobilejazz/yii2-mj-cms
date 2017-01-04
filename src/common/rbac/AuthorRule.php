<?php
/**
 * Created by IntelliJ IDEA.
 * User: polbatllo
 * Date: 19/01/16
 * Time: 12:37
 */

namespace mobilejazz\yii2\cms\common\rbac;

use yii\rbac\Item;
use yii\rbac\Rule;

class AuthorRule extends Rule
{

    public $name = 'isAuthor';


    /**
     * Executes the rule.
     *
     * @param string|integer $user   the user ID. This should be either an integer or a string representing
     *                               the unique identifier of a user. See [[\yii\web\User::id]].
     * @param Item           $item   the role or permission that this rule is associated with
     * @param array          $params parameters passed to [[ManagerInterface::checkAccess()]].
     *
     * @return boolean a value indicating whether the rule permits the auth item it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params[ 'post' ]) ? $params[ 'post' ]->author_id == $user : false;
    }
}