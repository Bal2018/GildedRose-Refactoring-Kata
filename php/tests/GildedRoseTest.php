<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    // Function to run the UpdateQuality method
    public function setUpQuality(GildedRose $gildedRose, int $times): void
    {
        for ($x = 0; $x < $times; $x++) {
            $gildedRose->updateQuality();
        }
    }

    //Initial set up test
    public function testInitial(): void
    {
        $items = [new Item('foo', 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $this->assertSame('foo', $items[0]->name);
    }

    //General Item test
    public function testItem_sell_date_is_decreased_at_end_of_day(): void
    {
        $items = [new Item('ItemA', 10, 10)];
        $gildedRose = new GildedRose($items);
        $this->setUpQuality($gildedRose, 2);
        $this->assertEquals(8, $items[0]->quality);
        $this->assertEquals(8, $items[0]->sell_in);
    }

    public function testQuality_degrades_twice_as_fast_once_sellby_date_passed(): void
    {
        $items = [new Item('ItemA', 1, 10)];
        $gildedRose = new GildedRose($items);
        $this->setUpQuality($gildedRose, 2);
        $this->assertEquals(7, $items[0]->quality);
    }

    public function testQuality_is_never_negative(): void
    {
        $items = [new Item('ItemA', 1, 1)];
        $gildedRose = new GildedRose($items);
        $this->setUpQuality($gildedRose, 2);
        $this->assertEquals(0, $items[0]->quality);
    }

    //Aged Brie tests
    public function test_AgedBrie_Quality_increases_theOlder_it_gets(): void
    {
        $items = [new Item('Aged Brie', 10, 10)];
        $gildedRose = new GildedRose($items);
        $this->setUpQuality($gildedRose, 1);
        $this->assertEquals(9, $items[0]->sell_in);
        $this->assertEquals(11, $items[0]->quality);
    }

    //Sulfuras tests
    public function test_Sulfuras_quality_doesnt_change(): void
    {
        $items = [new Item('Sulfuras, Hand of Ragnaros', 10, 80)];
        $gildedRose = new GildedRose($items);
        $this->setUpQuality($gildedRose, 10);
        $this->assertEquals(80, $items[0]->quality);
    }

    //Backstage passes to a TAFKAL80ETC concert tests
    public function test_Quality_of_item_Backstage_passes_Quality_is_never_negative(): void
    {
        $items = [new Item('Backstage passes to a TAFKAL80ETC concert', 10, 10)];
        $gildedRose = new GildedRose($items);
        $this->setUpQuality($gildedRose, 11);
        $this->assertEquals(-1, $items[0]->sell_in);
        $this->assertEquals(0, $items[0]->quality);
    }

    /**
     * @dataProvider qualityProvider
     */
    public function testQualityIncreasesBackstagePasses(int $quality, int $sellIn, int $expected, int $days): void
    {
        $items = [new Item('Backstage passes to a TAFKAL80ETC concert', $sellIn, $quality)];
        $gildedRose = new GildedRose($items);
        $this->setUpQuality($gildedRose, $days);
        $this->assertEquals($expected, $items[0]->quality);
    }

    public function qualityProvider()
    {
        return [
           "10 days sellin" => [8, 10, 10,1],
            "more than 10 days"=>[8,11,9,1],
            "less than 10 days"=>[8,9,10,1],
            "less than 5 days" => [4, 2,7,1],
           "concert passed" => [11,1,0,2],

        ];
    }

    /**
     * @name conjured item
     */
    public function test_Conjured_item(): void
    {
        $items = [new Item('Conjured', 10, 10)];
        $gildedRose = new GildedRose($items);
        $this->setUpQuality($gildedRose, 2);
        $this->assertEquals(6, $items[0]->quality);

        $items = [new Item('Conjured', 10, 80)];
        $gildedRose = new GildedRose($items);
        $this->setUpQuality($gildedRose, 2);
        $this->assertEquals(80, $items[0]->quality);
    }

}