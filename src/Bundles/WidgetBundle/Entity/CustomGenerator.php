<?php
/**
 * Created by PhpStorm.
 * User: Lamudi
 * Date: 13/05/15
 * Time: 14:10
 */

namespace Bundles\WidgetBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Doctrine\ORM\Query;

class CustomGenerator extends AbstractIdGenerator
{
    public function generate(EntityManager $em, $entity)
    {
        $randomHash = rand(0, 9999);
        $existingId = true;

        while (!empty($existingId)) {
            $existingId = $em->createNativeQuery(
                'SELECT id FROM Users WHERE hash = "' . $randomHash . '"',
                new Query\ResultSetMapping()
            )->getResult();
        }

        return $randomHash;
    }
}
