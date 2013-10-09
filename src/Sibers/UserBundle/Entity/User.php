<?php

namespace Sibers\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Entity()
 * @ORM\Table(name="sibers_users")
 * 
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
class User extends BaseUser
{
    /** 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @var integer identificator
     */
    protected $id;
}
