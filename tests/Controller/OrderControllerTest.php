<?php

namespace App\Test\Controller;

use App\Entity\Order;
use App\Repository\OrdersRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private OrdersRepository $repository;
    private string $path = '/order/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Order::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Order index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'order[reference]' => 'Testing',
            'order[fullName]' => 'Testing',
            'order[carrierName]' => 'Testing',
            'order[carrierPrice]' => 'Testing',
            'order[deliveryAddress]' => 'Testing',
            'order[isPaid]' => 'Testing',
            'order[moreInformations]' => 'Testing',
            'order[createdAt]' => 'Testing',
            'order[user]' => 'Testing',
        ]);

        self::assertResponseRedirects('/order/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Order();
        $fixture->setReference('My Title');
        $fixture->setFullName('My Title');
        $fixture->setCarrierName('My Title');
        $fixture->setCarrierPrice('My Title');
        $fixture->setDeliveryAddress('My Title');
        $fixture->setIsPaid('My Title');
        $fixture->setMoreInformations('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUser('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Order');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Order();
        $fixture->setReference('My Title');
        $fixture->setFullName('My Title');
        $fixture->setCarrierName('My Title');
        $fixture->setCarrierPrice('My Title');
        $fixture->setDeliveryAddress('My Title');
        $fixture->setIsPaid('My Title');
        $fixture->setMoreInformations('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUser('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'order[reference]' => 'Something New',
            'order[fullName]' => 'Something New',
            'order[carrierName]' => 'Something New',
            'order[carrierPrice]' => 'Something New',
            'order[deliveryAddress]' => 'Something New',
            'order[isPaid]' => 'Something New',
            'order[moreInformations]' => 'Something New',
            'order[createdAt]' => 'Something New',
            'order[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/order/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getReference());
        self::assertSame('Something New', $fixture[0]->getFullName());
        self::assertSame('Something New', $fixture[0]->getCarrierName());
        self::assertSame('Something New', $fixture[0]->getCarrierPrice());
        self::assertSame('Something New', $fixture[0]->getDeliveryAddress());
        self::assertSame('Something New', $fixture[0]->getIsPaid());
        self::assertSame('Something New', $fixture[0]->getMoreInformations());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Order();
        $fixture->setReference('My Title');
        $fixture->setFullName('My Title');
        $fixture->setCarrierName('My Title');
        $fixture->setCarrierPrice('My Title');
        $fixture->setDeliveryAddress('My Title');
        $fixture->setIsPaid('My Title');
        $fixture->setMoreInformations('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUser('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/order/');
    }
}
