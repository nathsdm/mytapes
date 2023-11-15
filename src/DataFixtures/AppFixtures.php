<?php

namespace App\DataFixtures;

use App\Entity\Inventory;
use App\Entity\Tape;
use App\Entity\Member;
use App\Entity\Gallery;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\KernelInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    private const SEB_INVENTORY = 'seb-inventory';
    private const OLIVIER_INVENTORY = 'olivier-inventory';

    private $projectDir;

    public function __construct(KernelInterface $kernel)
        {
            $this->projectDir = $kernel->getProjectDir();
        }

    /**
     * Generates initialization data for members
     * @return \\Generator
     */
    private static function memberDataGenerator()
        {
            yield [
                'name' => 'Seb',
                'email' => 'seb@localhost',
                'reference' => self::SEB_INVENTORY,
                'creation' => new \DateTime('1980-01-01')
            ];
            yield [
                'name' => 'Olivier',
                'email' => 'olivier@localhost',
                'reference' => self::OLIVIER_INVENTORY,
                'creation' => new \DateTime('2000-03-01')
            ];
        }

    public function getDependencies()
        {
                return [
                        UserFixtures::class,
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
                'isPublic' => true,
                'likes' => 10,
                'image' => '\public\images\tapes\tape1.jpg'
            ];
            yield [
                'name' => 'The Wall',
                'artist' => 'Pink Floyd',
                'year' => 1979,
                'inventory' => self::SEB_INVENTORY,
                'isPublic' => true,
                'likes' => 5,
                'image' => '\public\images\tapes\tape2.jpg'
            ];
            yield [
                'name' => 'The Division Bell',
                'artist' => 'Pink Floyd',
                'year' => 1994,
                'inventory' => self::SEB_INVENTORY,
                'isPublic' => false,
                'likes' => 1,
                'image' => '\public\images\tapes\tape3.png'
            ];
            yield [
                'name' => 'エコーチャンバーパーティー',
                'artist' => 'Macroblank',
                'year' => 2021,
                'inventory' => self::OLIVIER_INVENTORY,
                'isPublic' => true,
                'likes' => 398,
                'image' => '\public\images\tapes\tape4.png'
            ];
        }

    /**
     * Generates initialization data for galleries
     * @return \\Generator
     */
    public static function galleriesGenerator()
        {
            yield [
                'name' => 'The best of Pink Floyd !',
                'description' => 'Pink Floyd\'s best albums ! Enjoy !',
                'published' => true,
                'member' => 'Seb',
                'tapes' => [
                    'The Dark Side of the Moon',
                    'The Wall',
                ],
            ];
            yield [
                'name' => 'Vaporwave !',
                'description' => 'My favorite vaporwave tapes',
                'published' => true,
                'member' => 'Olivier',
                'tapes' => [
                    'エコーチャンバーパーティー',
                ],
            ];
        }

    public function load(ObjectManager $manager)
        {
            $inventoryrepo = $manager->getRepository(Inventory::class);

            foreach (self::memberDataGenerator() as $memberData) {
                $member = new Member();
                if($memberData['email']) {
                    $user = $manager->getRepository(User::class)->findOneByEmail($memberData['email']);
                    $member->setUser($user);
                }
                $member->setName($memberData['name']);
                $member->setCreation($memberData['creation']);
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
                $tape->setIsPublic($tapeData['isPublic']);
                $tape->setLikes($tapeData['likes']);
                $tape->setInventory($this->getReference($tapeData['inventory']));
                if(isset($tapeData['image'])) {
                    $imagePath = $this->projectDir . $tapeData['image'];
                    $tape->setImageFile(new File($imagePath));
                    $tape->setImageName(basename($imagePath));
                    $tape->setImageSize(filesize($imagePath));
                    $tape->setContentType(mime_content_type($imagePath));
                }
                $manager->persist($tape);
            }
            $manager->flush();

            foreach (self::galleriesGenerator() as $galleryData) {
                $gallery = new Gallery();
                $gallery->setName($galleryData['name']);
                $gallery->setDescription($galleryData['description']);
                $gallery->setPublished($galleryData['published']);
                $gallery->setMember($this->getReference($galleryData['member']));
                foreach ($galleryData['tapes'] as $tapeName) {
                    $gallery->addTape($manager->getRepository(Tape::class)->findOneByName($tapeName));
                }
                $manager->persist($gallery);
            }
            $manager->flush();
        }
}
