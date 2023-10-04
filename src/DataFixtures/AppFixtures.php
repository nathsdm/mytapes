<?php

namespace App\DataFixtures;

use App\Entity\Inventory;
use App\Entity\Tape;
use App\Entity\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private const SEB_INVENTORY = 'seb-inventory';
    private const OLIVIER_INVENTORY = 'olivier-inventory';

    /**
     * Generates initialization data for members
     * @return \\Generator
     */
    private static function memberDataGenerator()
    {
        yield [
            'name' => 'Seb',
            'reference' => self::SEB_INVENTORY,
        ];
        yield [
            'name' => 'Olivier',
            'reference' => self::OLIVIER_INVENTORY,
        ];
    }

    /**
     * Generates initialization data for inventories
     * @return \\Generator
     */
    private static function inventoryDataGenerator()
    {
        yield [
            'name' => 'Seb\'s inventory',
            'reference' => self::SEB_INVENTORY,
            'member' => 'Seb',
        ];
        yield [
            'name' => 'Olivier\'s inventory',
            'reference' => self::OLIVIER_INVENTORY,
            'member' => 'Olivier',
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
        yield [
            'name' => 'エコーチャンバーパーティー',
            'artist' => 'Macroblank',
            'year' => 2021,
            'inventory' => self::OLIVIER_INVENTORY,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $inventoryrepo = $manager->getRepository(Inventory::class);

        foreach (self::memberDataGenerator() as $memberData) {
            $member = new Member();
            $member->setName($memberData['name']);
            $manager->persist($member);
            $manager->flush();
            $this->addReference($memberData['name'], $member);
        }

        foreach (self::inventoryDataGenerator() as $inventoryData) {
            $inventory = new Inventory();
            $inventory->setName($inventoryData['name']);
            $inventory->setMember($this->getReference($inventoryData['member']));
            $manager->persist($inventory);
            $manager->flush();
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
        $manager->flush();
    }
}
