<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class RestaurantReview extends Component
{
    public User $restaurant;
    public $reviews;

    public $rating = 0;
    public $comment = '';
    public $showReviewForm = false;

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|min:10|max:500'
    ];

    public function mount($restaurantId)
    {
        $this->restaurant = User::findOrFail($restaurantId);
        $this->loadReviews();
    }

    public function loadReviews()
    {
        $this->reviews = Review::where('restaurant_id', $this->restaurant->id)
            ->with('customer')
            ->latest()
            ->take(10)
            ->get();
    }

    public function toggleReviewForm()
    {
        $this->showReviewForm = !$this->showReviewForm;

        if (!$this->showReviewForm) {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->rating = 0;
        $this->comment = '';
    }

    public function submitReview()
    {
        $this->validate();

        if (!Auth::check()) {
            session()->flash('error', 'Je moet ingelogd zijn om een review te plaatsen.');
            return;
        }

        // Check if user has already reviewed this restaurant
        $existingReview = Review::where('customer_id', Auth::id())
            ->where('restaurant_id', $this->restaurant->id)
            ->first();

        if ($existingReview) {
            session()->flash('error', 'Je hebt al een review geplaatst voor dit restaurant.');
            return;
        }

        Review::create([
            'customer_id' => Auth::id(),
            'restaurant_id' => $this->restaurant->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
        ]);

        session()->flash('success', 'Bedankt voor je review!');
        $this->resetForm();
        $this->showReviewForm = false;
        $this->loadReviews();
    }

    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    public function render()
    {
        return view('livewire.restaurant-review');
    }
}
