<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Booking;
use App\Entity\Mission;
use App\Entity\User;
use App\Enum\BookingStatus;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class BookingTest extends TestCase
{
    public function testCreateAndDeleteBooking(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $bookingRepository = $this->createMock(EntityRepository::class);

        $bookingRepository->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($bookingRepository);

        $user = new User();
        $user->setFirstName('Alice')
            ->setLastName('Smith')
            ->setEmail('alice.smith@example.com')
            ->setPassword('securepassword')
            ->setCreatedAt(new \DateTimeImmutable());

        $mission = new Mission();
        $mission->setDestination('Mars')
            ->setDescription('Mission scientifique')
            ->setDate(new \DateTime('2030-06-01 08:00:00'))
            ->setSeatPrice(5000000)
            ->setImage('mars-mission.jpg')
            ->setDuration(new \DateTime('02:30:00'));

        $booking = new Booking();
        $booking->setDestination('Mars')
            ->setSeatCount(2)
            ->setTotalPrice(10000000)
            ->setStatus(BookingStatus::CONFIRMED)
            ->setCustomer($user)
            ->setMission($mission);

        $this->assertEquals('Mars', $booking->getDestination());
        $this->assertEquals(2, $booking->getSeatCount());
        $this->assertEquals(10000000, $booking->getTotalPrice());
        $this->assertEquals(BookingStatus::CONFIRMED, $booking->getStatus());
        $this->assertInstanceOf(User::class, $booking->getCustomer());
        $this->assertInstanceOf(Mission::class, $booking->getMission());

        $entityManager->remove($booking);
        $entityManager->flush();

        $deletedBooking = $entityManager->getRepository(Booking::class)->find(1);
        $this->assertNull($deletedBooking);
    }
}
