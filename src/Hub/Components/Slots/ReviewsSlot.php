<?php

namespace Dystcz\LunarReviews\Hub\Components\Slots;

use Dystcz\LunarReviews\Domain\Reviews\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;
use Lunar\Hub\Slots\AbstractSlot;
use Lunar\Hub\Slots\Traits\HubSlot;

class ReviewsSlot extends Component implements AbstractSlot
{
    use HubSlot;
    use WithPagination;

    public static function getName()
    {
        return 'lunar-reviews::reviews-slot';
    }

    public function getSlotHandle()
    {
        return 'reviews-slot';
    }

    public function getSlotInitialValue()
    {
    }

    public function getSlotPosition()
    {
        return 'bottom';
    }

    public function getSlotTitle()
    {
        return 'Reviews';
    }

    public function updateSlotModel()
    {
    }

    public function handleSlotSave($model, $data)
    {
        $this->slotModel = $model;
    }

    public function toggle(Review $review)
    {
        $review->update(['published_at' => $review->published_at ? null : now()]);

        $this->setPage($this->page);
    }

    public function render()
    {
        return view('lunar-reviews::livewire.reviews-slot', [
            'reviews' => $this->slotModel->reviews()->paginate(10),
        ]);
    }
}
