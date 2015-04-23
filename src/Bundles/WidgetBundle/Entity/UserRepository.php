<?php
/**
 * Created by PhpStorm.
 * User: Danil Baibak danil.baibak@gmail.com
 * Date: 21/04/15
 * Time: 16:41
 */

namespace Bundles\WidgetBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{

    /**
     * Get user's rate by user id
     *
     * @param $userId
     * @return array
     */
    public function getUserRate($userId)
    {
        $rate = $this->createQueryBuilder('u')
            ->select('u.rate')
            ->where('u.status = :status')
            ->andWhere('u.id = :userId')
            ->setParameters(array(
                'status' => 'active',
                'userId' => $userId
            ))
            ->getQuery()
            ->getResult();

        return $rate;
    }
}