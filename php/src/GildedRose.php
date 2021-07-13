<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    /**
     * @var Item[]
     */
    private $items;
    /**
     * @var int
     */
    public $quality;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function adjustQuality(Item $item, int $quantity): void
    {
        if ($item->quality < 50) {
            $item->quality = $item->quality + $quantity;
        }
    }

    public function adjustSellInByOne(Item $item ): void
    {

        $item->sell_in = $item->sell_in - 1;
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            switch ($item->name) {
                case  ( 'Sulfuras, Hand of Ragnaros') :
                    {
                        $item->quality = 80;
                    break;
                }

                case ('Conjured') :
                {
                    $this->adjustQuality($item,-2);
                    break;
                }

                case ('Aged Brie') :
                {
                    $item->quality = $item->quality + 1;
                    $this->adjustSellInByOne($item);
                    if ($item->sell_in < 0) {
                        $this->adjustQuality($item,1);
                    }
                    break;
                }

                case ('Backstage passes to a TAFKAL80ETC concert') :
                {
                    if ($item->quality < 50) {
                        $item->quality = $item->quality + 1;

                        if ($item->sell_in < 11) {
                            $this->adjustQuality($item,1);
                        }
                        if ($item->sell_in < 6) {
                            $this->adjustQuality($item,1);
                            }
                    }
                    $this->adjustSellInByOne($item);
                    if ($item->sell_in < 0) {
                        $item->quality = $item->quality - $item->quality;
                    }
                    break;
                }

                default :
                {
                   if ($item->quality > 0) {
                       $item->quality = $item->quality -1;
                    }
                    $this->adjustSellInByOne($item);
                    if (($item->sell_in < 0) && ($item->quality > 0)) {
                            $item->quality = $item->quality -1;
                        }


                    break;
                }

            }
        }
    }
}
// what effect does changing anything have?  do we want to improve readability or reduce duplicity ?
// Deal with exceptional things first
