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
        $this->assertEquals(-1, $items[0]->sell_in);
        $this->assertEquals(0, $items[0]->quality);
    }

    /* Sulfuras must never be sold and keep a quality of 80*/
    public function testSulfuras(): void
    {
        $items = [new Item('Sulfuras, Hand of Ragnaros', 0, 80)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $this->assertSame('Sulfuras, Hand of Ragnaros', $items[0]->name);
        $this->assertEquals(0, $items[0]->sell_in);
        $this->assertEquals(80, $items[0]->quality);
    }
}
