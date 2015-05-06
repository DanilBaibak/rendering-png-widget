<?php

namespace Bundles\WidgetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table("Users", uniqueConstraints={
 *  @ORM\UniqueConstraint(name="idx_hash", columns={"hash"})
 * }))
 * @ORM\Entity(repositoryClass="Bundles\WidgetBundle\Entity\UserRepository")
 */
class Users
{
    /**
     * Value for ENUM column 'status'
     */
    const STATUS_ACTIVE = 'active';

    /**
     * Value for ENUM column 'status'
     */
    const STATUS_INACTIVE = 'inactive';

    /**
     * Value for ENUM column 'status'
     */
    const STATUS_BLOCKED = 'blocked';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string", length=128)
     */
    private $userName;

    /**
     * @var integer
     *
     * @ORM\Column(name="hash", type="integer", length=255)
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="rate", type="string", length=32)
     */
    private $rate;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=false, columnDefinition="ENUM('active', 'inactive', 'blocked')")
     */
    private $status = self::STATUS_ACTIVE;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userName
     *
     * @param string $userName
     *
     * @return Users
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return Users
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set rate
     *
     * @param string $rate
     *
     * @return Users
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Users
     */
    public function setStatus($status)
    {
        $statusList = array(self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_BLOCKED);

        if (!in_array($status, $statusList)) {
            throw new \InvalidArgumentException("Invalid status");
        }

        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
