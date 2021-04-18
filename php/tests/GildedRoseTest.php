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
        $items = [new Item('Random default item', 10, 40)];
        $gildedRose = new GildedRoseNew($items);
        $gildedRose->updateQuality();
        $this->assertSame('Random default item', $items[0]->name);
        $this->assertEquals(9, $items[0]->sell_in);
        $this->assertEquals(39, $items[0]->quality);
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

}
