<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRoseNew
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
         * @var Item
         * @name = string
         * @sell_in = int
         * @qyality = int
         */

        foreach ($this->items as $item) {
            /*
            Every item has 2 parameters that can change - sell_in and quality
            For new parameters add them here.
            */
            $ChangeQuality = 0;
            $ChangeSellin = 0;

            $name = $item->name;
            $quality = $item->quality;
            $sellin = $item->sell_in;

            /*
             For Each New item case: Add a case with parameters - ChangeQuality and ChangeSellin;
             */

            switch (true){

                // Should never be sold and stay at quality 80
                case $name === 'Sulfuras, Hand of Ragnaros' :

                    // Prevents Sulfuras from being sold.
                    if($sellin > 0){
                        $sellin = 0;
                    }
                    $item->quality = 80;
                    continue 2;

                default:
                    $ChangeQuality = -1;
                    $ChangeSellin = -1;

            }
            /*
            Updating the item.
            In case of additional updates needed - add them here.
            */

            $item->sell_in += $ChangeSellin;
            $item->quality += $ChangeQuality;



        }
    }
}
