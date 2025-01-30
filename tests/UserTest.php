<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testCreateAndDeleteUser(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $userRepository = $this->createMock(EntityRepository::class);

        $userRepository->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $user = new User();
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setEmail('john.doe@gmail.com');
        $user->setPassword('password');
        $user->setCreatedAt(new \DateTimeImmutable());

        $this->assertEquals('John', $user->getFirstName());
        $this->assertEquals('Doe', $user->getLastName());
        $this->assertEquals('john.doe@gmail.com', $user->getEmail());
        $this->assertEquals('password', $user->getPassword());
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getCreatedAt());

        $entityManager->remove($user);
        $entityManager->flush();

        $deletedUser = $entityManager->getRepository(User::class)->find(1);
        $this->assertNull($deletedUser);
    }
}
