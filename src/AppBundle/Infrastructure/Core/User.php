<?php

namespace AppBundle\Infrastructure\Core;


use Symfony\Component\Security\Core\User\UserInterface;

class User extends \Domain\Core\User implements UserInterface, \Serializable
{

}
