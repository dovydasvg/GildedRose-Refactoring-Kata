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

            $ChangeQuality = -1;
            $ChangeSellin = -1;

            //Used for reading not for writing
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

                // Should increase in quality over time
                case $name === 'Aged Brie' :
                    $ChangeQuality = 1;
                    break;

                // Should increase in Quality.
                case str_contains($name,'Backstage passes'):

                    /*
                    Default - Quality +1
                    If less than 11 days left - quality +2
                    If less than 6 days left - Quality +3
                    If 0 days left - Quality 0
                    The sellin gets lower so an additional 1 is added.
                     */

                    if($sellin < 2 ){
                        $item->quality = 0;
                        $ChangeQuality = 0;
                    }elseif($sellin < 7){
                        $ChangeQuality = 3;
                    }elseif ($sellin < 12){
                        $ChangeQuality = 2;
                    }else{
                        $ChangeQuality = 1;
                    }

                    break;


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

            // Quality must be between 0 and 50
            if($quality > 50){
                $item->quality = 50;
            }elseif($quality < 0){
                $item->quality = 0;
            }



        }
    }
}
