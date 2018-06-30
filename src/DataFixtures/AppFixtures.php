<?php
/**
 * Created by PhpStorm.
 * User: alery
 * Date: 30/06/2018
 * Time: 14:40
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\Topic;
use App\Entity\Message;
use App\Entity\Article;
use App\Entity\Partner;
use App\Entity\Event;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Create users
        $userNames = ["Avriel","Benji", "Colin", "Diederik"];

        foreach ($userNames as $name) {
            $u = new User();
            $u->setCeltName($name+"ix");
            $u->setFirstName($name);
            $u->setLastName($name+"on");
            $u->setEmail(strtolower($name)+"@gmail.com");
            $u->setAdmin(true);
            $u->setPassword("password");
            $u->getStatus("a");
            $manager->persist($u);
        }

        $manager->flush();
    }
}