<?php

namespace App\DataFixtures;

use App\Entity\Inventory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private const SEB_INVENTORY = 'seb-inventory';
    private const OLIVIER_INVENTORY = 'olivier-inventory';

    /**
     * Generates initialization data for inventories
     * @return \\Generator
     */
    private static function inventoryDataGenerator()
    {
        yield [
            'name' => 'Seb\'s inventory',
            'reference' => self::SEB_INVENTORY,
        ];
        yield [
            'name' => 'Olivier\'s inventory',
            'reference' => self::OLIVIER_INVENTORY,
        ];
    }

    /**
     * Generates initialization data for tapes
     * @return \\Generator
     */
    private static function tapesGenerator()
    {
        yield [
            'name' => 'The Dark Side of the Moon',
            'artist' => 'Pink Floyd',
            'year' => 1973,
            'inventory' => self::SEB_INVENTORY,
        ];
        yield [
            'name' => 'The Wall',
            'artist' => 'Pink Floyd',
            'year' => 1979,
            'inventory' => self::SEB_INVENTORY,
        ];
        yield [
            'name' => 'The Division Bell',
            'artist' => 'Pink Floyd',
            'year' => 1994,
            'inventory' => self::SEB_INVENTORY,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $inventoryrepo = $manager->getRepository(Inventory::class);

        foreach (self::inventoryDataGenerator() as $inventoryData) {
            $inventory = new Inventory();
            $inventory->setName($inventoryData['name']);
            $manager->persist($inventory);
            $this->addReference($inventoryData['reference'], $inventory);
        }

        foreach (self::tapesGenerator() as $tapeData) {
            $tape = new Tape();
            $tape->setName($tapeData['name']);
            $tape->setArtist($tapeData['artist']);
            $tape->setYear($tapeData['year']);
            $tape->setInventory($this->getReference($tapeData['inventory']));
            $manager->persist($tape);
        }
    }
}
