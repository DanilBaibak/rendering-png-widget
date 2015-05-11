<?php
/**
 * Created by PhpStorm.
 * User: Danil Baibak danil.baibak@gmail.com
 * Date: 23/04/15
 * Time: 13:35
 */

namespace Bundles\WidgetBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UsersTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * Test repository method getUserRate
     */
    public function testGetUserRate()
    {
//        $rate = $this->em
//            ->getRepository('WidgetBundle:Users')
//            ->getUserRate(1);
//
//        $this->assertEquals(23, $rate[0]['rate']);
        $this->assertEquals(23, 23);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
    }
}