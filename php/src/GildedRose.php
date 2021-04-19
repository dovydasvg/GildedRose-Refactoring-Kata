<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    /**
     * @var Item[]
     */
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }


    public function updateQuality(): void
    {
        /**
         * Item parameters:
         * name = string
         * sell_in = int
         * quality = int
         */

        foreach ($this->items as $item) {

            $this->updateSwitch($item);

        }
    }

    /**
     * @description method for Sulfuras Items.
     * Should never be sold and stay at quality 80
     * @param Item $item
     */
    private function updateSulfuras(Item $item): void
    {

        if ($item->sell_in > 0) {
            $item->sell_in = 0;
        }
        $item->quality = 80;
    }

    /**
     * @description method for Aged Brie Items.
     * Should increase in quality over time. Standart update rules.
     * @param Item $item
     */
    private function updateBrie(Item $item): void
    {
        $this->lowerSellin($item);

        $this->expiredDoubles($item, 1);

        $this->checkQualityLimits($item);
    }

    /**
     * @description method for Backstage passes Items.
     * Default - Quality +1
     * If less than 11 days left - quality +2
     * If less than 6 days left - Quality +3
     * If 0 days left - Quality 0
     * @param Item $item
     */
    private function updateBackstage(Item $item): void
    {

        /*

         */

        $this->lowerSellin($item);

        if ($item->sell_in < 0) {
            $item->quality = 0;
        } elseif ($item->sell_in < 5) {
            $item->quality += 3;
        } elseif ($item->sell_in < 10) {
            $item->quality += 2;
        } else {
            $item->quality += 1;
        }

        $this->checkQualityLimits($item);
    }

    /**
     * @description method for Conjured Items.
     * Quality gets worst twice faster
     * @param Item $item
     */
    private function updateConjured(Item $item): void
    {
        $this->lowerSellin($item);
        $this->expiredDoubles($item, -2);
        $this->checkQualityLimits($item);
    }

    /**
     * @description method for Default Items
     * @param Item $item
     */
    private function updateDefault(Item $item): void
    {
        $this->lowerSellin($item);
        $this->expiredDoubles($item, -1);
        $this->checkQualityLimits($item);
    }

    /**
     * @description Checks if an item's Quality is not above 50 or bellow 0
     * @param Item $item
     */
    private function checkQualityLimits(Item $item): void
    {
        if($item->quality > 50){
            $item->quality = 50;
        }elseif ($item->quality < 0){
            $item->quality = 0;
        }
    }


    /**
     * @description Doubles the rate of quality change if item expired
     * @param Item $item
     * @param int $qualityChange
     * @return void
     */
    private function expiredDoubles(Item $item,int $qualityChange): void
    {
        if($item->sell_in < 0){
            $item->quality += $qualityChange*2;
        }else{
            $item->quality += $qualityChange;
        }

    }


    /**
     * @param Item $item
     * @param int $lowerBy, default = 1
     */
    private function lowerSellin(Item $item, $lowerBy=1): void
    {
        $item->sell_in -= $lowerBy;
    }

    /**
     * @description Updated each item depending on a case.
     * Add new cases here.
     * @param Item $item
     */
    private function updateSwitch(Item $item)
    {
        switch (true) {

            case $item->name === 'Sulfuras, Hand of Ragnaros':
                $this->updateSulfuras($item);
                break;

            case $item->name === 'Aged Brie':
                $this->updateBrie($item);
                break;

            case str_contains($item->name, 'Backstage passes'):
                $this->updateBackstage($item);
                break;

            case str_contains($item->name, 'Conjured'):
                $this->updateConjured($item);
                break;

            default:
                $this->updateDefault($item);
        }
    }




}
