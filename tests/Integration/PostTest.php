<?php

namespace App\Tests\Integration;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PostTest extends KernelTestCase
{

    public function getEntity()
    {
        return (new Post())->setTitle('Article #1')
            ->setContent('Contenu #1')
            ->setActive(true);
    }

    public function testEntityisValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();


        $post = $this->getEntity();

        $errors = $container->get('validator')->validate($post);
        $this->assertCount(0, $errors);
    }
}
