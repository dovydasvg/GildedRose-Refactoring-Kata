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

                // Should never be sold and stay at quality 80
                case $name === 'Sulfuras, Hand of Ragnaros':

                    // Prevents Sulfuras from being sold.
                    if ($sellin > 0) {
                        $item->sell_in = 0;
                    }
                    $item->quality = 80;
                    continue 2;

                // Should increase in quality over time
                case $name === 'Aged Brie':
                    $ChangeQuality = 1;
                    break;

                // Should increase in Quality.
                case str_contains($name, 'Backstage passes'):

                    /*
                    Default - Quality +1
                    If less than 11 days left - quality +2
                    If less than 6 days left - Quality +3
                    If 0 days left - Quality 0
                    The sellin gets lower so an additional 1 is added.
                     */

                    if ($sellin < 1) {
                        $item->quality = 0;
                        $ChangeQuality = 0;
                    } elseif ($sellin < 6) {
                        $ChangeQuality = 3;
                    } elseif ($sellin < 11) {
                        $ChangeQuality = 2;
                    } else {
                        $ChangeQuality = 1;
                    }

                    break;

                // Should decrease in Quality twice as fast
                case str_contains($name, 'Conjured'):
                    $ChangeQuality = -2;
                    break;

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
}
