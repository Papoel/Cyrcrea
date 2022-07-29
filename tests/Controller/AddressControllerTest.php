<?php

namespace App\Test\Controller;

use App\Entity\Address;
use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddressControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private AddressRepository $repository;
    private string $path = '/address/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Address::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Address index');

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
            'address[name]' => 'Testing',
            'address[company]' => 'Testing',
            'address[address]' => 'Testing',
            'address[complement]' => 'Testing',
            'address[city]' => 'Testing',
            'address[postal]' => 'Testing',
            'address[country]' => 'Testing',
            'address[phone]' => 'Testing',
            'address[user]' => 'Testing',
        ]);

        self::assertResponseRedirects('/address/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Address();
        $fixture->setName('My Title');
        $fixture->setCompany('My Title');
        $fixture->setAddress('My Title');
        $fixture->setComplement('My Title');
        $fixture->setCity('My Title');
        $fixture->setPostal('My Title');
        $fixture->setCountry('My Title');
        $fixture->setPhone('My Title');
        $fixture->setUser('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Address');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Address();
        $fixture->setName('My Title');
        $fixture->setCompany('My Title');
        $fixture->setAddress('My Title');
        $fixture->setComplement('My Title');
        $fixture->setCity('My Title');
        $fixture->setPostal('My Title');
        $fixture->setCountry('My Title');
        $fixture->setPhone('My Title');
        $fixture->setUser('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'address[name]' => 'Something New',
            'address[company]' => 'Something New',
            'address[address]' => 'Something New',
            'address[complement]' => 'Something New',
            'address[city]' => 'Something New',
            'address[postal]' => 'Something New',
            'address[country]' => 'Something New',
            'address[phone]' => 'Something New',
            'address[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/address/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getCompany());
        self::assertSame('Something New', $fixture[0]->getAddress());
        self::assertSame('Something New', $fixture[0]->getComplement());
        self::assertSame('Something New', $fixture[0]->getCity());
        self::assertSame('Something New', $fixture[0]->getPostal());
        self::assertSame('Something New', $fixture[0]->getCountry());
        self::assertSame('Something New', $fixture[0]->getPhone());
        self::assertSame('Something New', $fixture[0]->getUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Address();
        $fixture->setName('My Title');
        $fixture->setCompany('My Title');
        $fixture->setAddress('My Title');
        $fixture->setComplement('My Title');
        $fixture->setCity('My Title');
        $fixture->setPostal('My Title');
        $fixture->setCountry('My Title');
        $fixture->setPhone('My Title');
        $fixture->setUser('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/address/');
    }
}
