<?php
/**
 * Created by PhpStorm.
 * User: Danil Baibak danil.baibak@gmail.com
 * Date: 23/04/15
 * Time: 13:19
 */

namespace Bundles\WidgetBundle\Tests\Entity;

class UserFakeRepository
{
    public function getUserRate($userId) {
        return array();
    }
}