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

            /*
             For Each New item case: Add a case with separate method.
             */

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

    // Should never be sold and stay at quality 80
    private function updateSulfuras($item)
    {
        // Prevents Sulfuras from being sold.
        if ($item->sell_in > 0) {
            $item->sell_in = 0;
        }
        $item->quality = 80;
    }

    // Should increase in quality over time. Standart update rules.
    private function updateBrie($item)
    {
        $this->lowerSellin($item);

        $this->expiredDoubles($item, 1);

        $this->checkQualityLimits($item);
    }


    private function updateBackstage($item)
    {

        /*
        Default - Quality +1
        If less than 11 days left - quality +2
        If less than 6 days left - Quality +3
        If 0 days left - Quality 0
         */

        $this->lowerSellin();

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

    // Quality gets worst twice faster
    private function updateConjured($item)
    {
        $this->lowerSellin($item);
        $this->expiredDoubles($item, -2);
        $this->checkQualityLimits($item);
    }

    private function updateDefault($item)
    {
        $this->lowerSellin($item);
        $this->expiredDoubles($item, -1);
        $this->checkQualityLimits($item);
    }

    // Checks if an item's Quality is not above 50 or bellow 0
    private function checkQualityLimits($item)
    {
        if($item->quality > 50){
            $item->quality = 50;
        }elseif ($item->quality < 0){
            $item->quality = 0;
        }
    }


    /**
     * @description Doubles the rate of quality change if item expired
     * @param $item - item object
     * @param $qualityChange - int, which changes quality of item
     */
    private function expiredDoubles($item, $qualityChange)
    {
        if($item->sell_in < 0){
            $item->quality += $qualityChange*2;
        }else{
            $item->quality += $qualityChange;
        }

    }


    /**
     * @param $item - item object
     * @param int $lowerBy, default = 1
     */
    private function lowerSellin($item, $lowerBy=1)
    {
        $item->sell_in -= $lowerBy;
    }




}
