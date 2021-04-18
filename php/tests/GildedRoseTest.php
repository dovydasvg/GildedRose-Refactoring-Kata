<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    public function testFoo(): void
    {
        $items = [new Item('foo', 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $this->assertSame('foo', $items[0]->name);
        $this->assertSame(-1, $items[0]->sell_in);
        $this->assertSame(0, $items[0]->quality);
    }

    /* Default test */

    public function testDefault(): void
    {
        $items = [
            new Item('Random default item', 10, 40),
            new Item('Shitty default item', 5, 0),
            new Item('Fast degrading default item', -5, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(9, $items[0]->sell_in);
        $this->assertSame(39, $items[0]->quality);

        $this->assertSame(4, $items[1]->sell_in);
        $this->assertSame(0, $items[1]->quality);

        $this->assertSame(-6, $items[2]->sell_in);
        $this->assertSame(18, $items[2]->quality);
    }

    /* Sulfuras must never be sold and keep a quality of 80*/
    public function testSulfuras(): void
    {
        $items = [new Item('Sulfuras, Hand of Ragnaros', 0, 80)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $this->assertSame('Sulfuras, Hand of Ragnaros', $items[0]->name);
        $this->assertSame(0, $items[0]->sell_in);
        $this->assertSame(80, $items[0]->quality);
    }

    /* Aged Brie should increase in quality */
    public function testAgedBrie(): void
    {
        $items = [
            new Item('Aged Brie', 10, 15),
            new Item('Aged Brie', -5, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(9, $items[0]->sell_in);
        $this->assertSame(16, $items[0]->quality);

        $this->assertSame(-6, $items[1]->sell_in);
        $this->assertSame(22, $items[1]->quality);
    }

    /*
     Backstage passes should increase in Quality.
     If <11 days left - by 2; if <6 days by 3;
     If sellin time is <1 the quality should be 0;
     */
    public function testBackStage(): void
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20),
            new Item('Backstage passes to a Great concert', 10, 20),
            new Item('Backstage passes to a Great concert', 5, 49),
            new Item('Backstage passes to a Random concert', 1, 40),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $this->assertSame(14, $items[0]->sell_in);
        $this->assertSame(21, $items[0]->quality);

        $this->assertSame(9, $items[1]->sell_in);
        $this->assertSame(22, $items[1]->quality);

        $this->assertSame(4, $items[2]->sell_in);
        $this->assertSame(50, $items[2]->quality);

        $this->assertSame(0, $items[3]->sell_in);
        $this->assertSame(43, $items[3]->quality);
    }

    /*
    "Conjured" items degrade in Quality twice as fast as normal items
    */

    public function testConjured(): void
    {
        $items = [
            new Item('Conjured vase', 15, 20),
            new Item('Conjured silver sword', -2, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $this->assertSame(14, $items[0]->sell_in);
        $this->assertSame(18, $items[0]->quality);

        $this->assertSame(-3, $items[1]->sell_in);
        $this->assertSame(16, $items[1]->quality);
    }
}
