<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\User;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[Group('functional')]
class UserFunctionTest extends KernelTestCase
{
    public function testCreateAndDeleteUser(): void
    {
        self::bootKernel();
        $entityManager = self::getContainer()->get('doctrine')->getManager();

        $user = new User();
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setEmail('john.doe@gmail.com');
        $user->setPassword('password');
        $user->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($user);
        $entityManager->flush();

        // Vérification de la persistance
        $savedUser = $entityManager->getRepository(User::class)->findOneBy(['email' => 'john.doe@gmail.com']);
        $this->assertNotNull($savedUser);
        $this->assertEquals('John', $savedUser->getFirstName());

        // Suppression
        $entityManager->remove($savedUser);
        $entityManager->flush();

        // Vérification de la suppression
        $deletedUser = $entityManager->getRepository(User::class)->findOneBy(['email' => 'john.doe@gmail.com']);
        $this->assertNull($deletedUser);
    }
}
