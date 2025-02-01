<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Mission;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class MissionTest extends TestCase
{
    public function testCreateAndDeleteMission(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $missionRepository = $this->createMock(EntityRepository::class);

        $missionRepository->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($missionRepository);

        $mission = new Mission();
        $mission->setDestination('Mars')
            ->setDescription('Mission scientifique sur Mars')
            ->setDate(new \DateTime('2030-06-01 08:00:00'))
            ->setSeatPrice(5000000)
            ->setImage('mars-mission.jpg')
            ->setDuration(new \DateTime('02:30:00'));

        $this->assertEquals('Mars', $mission->getDestination());
        $this->assertEquals('Mission scientifique sur Mars', $mission->getDescription());
        $this->assertEquals(5000000, $mission->getSeatPrice());
        $this->assertEquals('mars-mission.jpg', $mission->getImage());
        $this->assertInstanceOf(\DateTimeInterface::class, $mission->getDate());
        $this->assertInstanceOf(\DateTimeInterface::class, $mission->getDuration());

        $entityManager->remove($mission);
        $entityManager->flush();

        $deletedMission = $entityManager->getRepository(Mission::class)->find(1);
        $this->assertNull($deletedMission);
    }
}
