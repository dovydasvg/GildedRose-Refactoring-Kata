<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

use GildedRose\GildedRoseNew;

class GildedRoseTest extends TestCase
{
    public function testFoo(): void
    {
        $items = [new Item('foo', 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $this->assertSame('foo', $items[0]->name);
        $this->assertEquals(-1, $items[0]->sell_in);
        $this->assertEquals(0, $items[0]->quality);
    }

    /* Default test */

    public function testDefault(): void
    {
        $items = array(
            new Item('Random default item', 10, 40),
            new Item('Random default item', 5, 0),
        );
        $gildedRose = new GildedRoseNew($items);
        $gildedRose->updateQuality();

        $this->assertEquals(9, $items[0]->sell_in);
        $this->assertEquals(39, $items[0]->quality);

        $this->assertEquals(4, $items[1]->sell_in);
        $this->assertEquals(0, $items[1]->quality);
    }

    /* Sulfuras must never be sold and keep a quality of 80*/
    public function testSulfuras(): void
    {
        $items = [new Item('Sulfuras, Hand of Ragnaros', 0, 80)];
        $gildedRose = new GildedRoseNew($items);
        $gildedRose->updateQuality();
        $this->assertSame('Sulfuras, Hand of Ragnaros', $items[0]->name);
        $this->assertEquals(0, $items[0]->sell_in);
        $this->assertEquals(80, $items[0]->quality);
    }

    /* Aged Brie should increase in quality */
    public function testAgedBrie(): void
    {
        $items = [new Item('Aged Brie', 10, 15)];
        $gildedRose = new GildedRoseNew($items);
        $gildedRose->updateQuality();
        $this->assertSame('Aged Brie', $items[0]->name);
        $this->assertEquals(9, $items[0]->sell_in);
        $this->assertEquals(16, $items[0]->quality);
    }

    /*
     Backstage passes should increase in Quality.
     If <11 days left - by 2; if <6 days by 3;
     If sellin time is <1 the quality should be 0;
     */
    public function testBackStage(): void
    {
        $items = array(
            new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20),
            new Item('Backstage passes to a Great concert', 10, 20),
            new Item('Backstage passes to a Great concert', 5, 49),
            new Item('Backstage passes to a Random concert', 1, 40),
        );
        $gildedRose = new GildedRoseNew($items);
        $gildedRose->updateQuality();
        $this->assertEquals(14, $items[0]->sell_in);
        $this->assertEquals(21, $items[0]->quality);

        $this->assertEquals(9, $items[1]->sell_in);
        $this->assertEquals(22, $items[1]->quality);

        $this->assertEquals(4, $items[2]->sell_in);
        $this->assertEquals(50, $items[2]->quality);

        $this->assertEquals(0, $items[3]->sell_in);
        $this->assertEquals(0, $items[3]->quality);
    }

}
