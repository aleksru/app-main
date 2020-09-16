<?php

namespace App\Observers;

use App\Models\Realization;
use App\Product;

class RealizationObserver
{
    /**
     * @param Realization $realization
     */
    public function creating(Realization $realization)
    {
        if($productId = $realization->product_id){
            $realization->setPriceFromProduct(Product::findOrNew($productId));
        }
    }

    /**
     * @param Realization $realization
     */
    public function created(Realization $realization)
    {
        //
    }
    /**
     * @param Realization $realization
     */
    public function updating(Realization $realization)
    {
//        if($productId = $realization->product_id){
//            $realization->setPriceFromProduct(Product::findOrNew($productId));
//        }
    }

    /**
     * @param Realization $realization
     */
    public function updated(Realization $realization)
    {
        //
    }

    /**
     * @param Realization $realization
     */
    public function deleted(Realization $realization)
    {
        //
    }

    /**
     * @param Realization $realization
     */
    public function restored(Realization $realization)
    {
        //
    }

    /**
     * @param Realization $realization
     */
    public function forceDeleted(Realization $realization)
    {
        //
    }
}
