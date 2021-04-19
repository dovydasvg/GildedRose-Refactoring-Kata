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
            Every item has 2 parameters that can change - sell_in and quality
            For new parameters add them here.
            */

            $ChangeQuality = -1;
            $ChangeSellin = -1;

            //Used for reading not for writing
            $name = $item->name;
            $sellin = $item->sell_in;

            /*
             For Each New item case: Add a case with parameters - ChangeQuality and ChangeSellin;
             */

            switch (true) {


                case $name === 'Sulfuras, Hand of Ragnaros':
                    $this->updateSulfuras($item);
                    continue 2;

                case $name === 'Aged Brie':
                    $this->updateBrie($item);
                    continue 2;

                // Should increase in Quality.
                case str_contains($name, 'Backstage passes'):
                    $this->updateBackstage($item);
                    continue 2;

                // Should decrease in Quality twice as fast
                case str_contains($name, 'Conjured'):
                    $this->updateConjured($item);
                    continue 2;

                default:
                    $ChangeQuality = -1;
                    $ChangeSellin = -1;

            }

            // Once the sell by date has passed, Quality degrades twice as fast
            if ($sellin < 1) {
                $ChangeQuality *= 2;
            }

            /*
            Updating the item.
            In case of additional updates needed - add them here.
            */

            $item->sell_in += $ChangeSellin;
            $item->quality += $ChangeQuality;

            // Quality must be between 0 and 50
            if ($item->quality > 50) {
                $item->quality = 50;
            } elseif ($item->quality < 0) {
                $item->quality = 0;
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
        $item->sell_in -= 1;

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

        $item->sell_in -= 1;

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

    private function updateConjured($item)
    {
        $item->sell_in -= 1;

        $this->expiredDoubles($item, -2);
        $this->checkQualityLimits($item);
    }


}
