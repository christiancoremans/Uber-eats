<div wire:key="review-section-{{ $restaurant->id }}">
    <div class="restaurant-reviews-section">
        <!-- Reviews Header -->
        <div class="reviews-header">
            <h3>Beoordelingen</h3>
            @php
                $averageRating = $restaurant->reviewsReceived()->avg('rating') ?? 0;
                $reviewCount = $restaurant->reviewsReceived()->count();
            @endphp
            <div class="overall-rating">
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star {{ $i <= round($averageRating) ? 'filled' : '' }}">★</span>
                    @endfor
                    <span class="rating-number">{{ number_format($averageRating, 1) }}</span>
                    <span class="review-count">({{ $reviewCount }} beoordelingen)</span>
                </div>
            </div>
        </div>

        <!-- Add Review Button (for logged in users) -->
        @auth
            @if(!$restaurant->reviewsReceived()->where('customer_id', auth()->id())->exists())
                <button wire:click="toggleReviewForm" class="add-review-btn">
                    {{ $showReviewForm ? 'Annuleren' : 'Review Toevoegen' }}
                </button>
            @endif
        @else
            <p class="login-prompt">
                <a href="{{ route('login') }}">Log in</a> om een review te plaatsen
            </p>
        @endauth

        <!-- Review Form -->
        @if($showReviewForm)
            <div class="review-form">
                @if(session()->has('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="star-rating-input">
                    <p>Jouw beoordeling:</p>
                    <div class="stars-input">
                        @for($i = 1; $i <= 5; $i++)
                            <button
                                type="button"
                                wire:click="setRating({{ $i }})"
                                class="star-input {{ $i <= $rating ? 'selected' : '' }}"
                            >
                                ★
                            </button>
                        @endfor
                        <span class="rating-text">
                            @switch($rating)
                                @case(0) Kies een rating @break
                                @case(1) Zeer slecht @break
                                @case(2) Slecht @break
                                @case(3) Gemiddeld @break
                                @case(4) Goed @break
                                @case(5) Uitstekend @break
                            @endswitch
                        </span>
                    </div>
                    @error('rating') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <textarea
                        wire:model="comment"
                        placeholder="Vertel over je ervaring bij dit restaurant..."
                        rows="4"
                    ></textarea>
                    @error('comment') <span class="error">{{ $message }}</span> @enderror
                </div>

                <button wire:click="submitReview" class="submit-review-btn">
                    Review Plaatsen
                </button>
            </div>
        @endif

        <!-- Reviews List -->
        <div class="reviews-list">
            @if($reviews->count() > 0)
                @foreach($reviews as $review)
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">
                                    {{ substr($review->customer->name, 0, 1) }}
                                </div>
                                <div class="reviewer-details">
                                    <span class="reviewer-name">{{ $review->customer->name }}</span>
                                    <span class="review-date">
                                        {{ $review->created_at->format('d-m-Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="review-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                                @endfor
                            </div>
                        </div>
                        <div class="review-comment">
                            {{ $review->comment }}
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-reviews">
                    <p>Nog geen beoordelingen. Wees de eerste!</p>
                </div>
            @endif
        </div>
    </div>

    <style>
    .restaurant-reviews-section {
        margin-top: 3rem;
        padding: 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .reviews-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .reviews-header h3 {
        margin: 0;
        color: #333;
        font-size: 1.5rem;
    }

    .overall-rating .stars {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .overall-rating .star {
        color: #e0e0e0;
        font-size: 1.5rem;
    }

    .overall-rating .star.filled {
        color: #ffd700;
    }

    .overall-rating .rating-number {
        font-weight: 600;
        color: #333;
        margin-left: 0.5rem;
        font-size: 1.2rem;
    }

    .overall-rating .review-count {
        color: #666;
        font-size: 0.9rem;
        margin-left: 0.25rem;
    }

    .add-review-btn {
        padding: 0.75rem 1.5rem;
        background: linear-gradient(90deg, #ff4d4d, #ff8a00);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .add-review-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 77, 77, 0.3);
    }

    .login-prompt {
        color: #666;
        margin-bottom: 1.5rem;
    }

    .login-prompt a {
        color: #ff4d4d;
        text-decoration: none;
        font-weight: 600;
    }

    .login-prompt a:hover {
        text-decoration: underline;
    }

    /* Review Form */
    .review-form {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .alert {
        padding: 0.75rem;
        border-radius: 6px;
        margin-bottom: 1rem;
        font-weight: 500;
    }

    .alert-error {
        background: #fff5f5;
        color: #e53e3e;
        border: 1px solid #fed7d7;
    }

    .alert-success {
        background: #f0fff4;
        color: #38a169;
        border: 1px solid #c6f6d5;
    }

    .star-rating-input {
        margin-bottom: 1rem;
    }

    .star-rating-input p {
        margin: 0 0 0.5rem 0;
        color: #333;
        font-weight: 500;
    }

    .stars-input {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .star-input {
        background: none;
        border: none;
        font-size: 2rem;
        color: #e0e0e0;
        cursor: pointer;
        transition: color 0.2s ease;
        padding: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .star-input:hover {
        color: #ffd700;
    }

    .star-input.selected {
        color: #ffd700;
    }

    .rating-text {
        margin-left: 1rem;
        color: #666;
        font-weight: 500;
        min-width: 120px;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-family: inherit;
        font-size: 1rem;
        resize: vertical;
        transition: border-color 0.3s ease;
    }

    .form-group textarea:focus {
        outline: none;
        border-color: #ff4d4d;
    }

    .form-group .error {
        display: block;
        margin-top: 0.25rem;
        color: #e53e3e;
        font-size: 0.875rem;
    }

    .submit-review-btn {
        padding: 0.75rem 1.5rem;
        background: linear-gradient(90deg, #28a745, #20c997);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-right: 1rem;
    }

    .submit-review-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }

    .cancel-review-btn {
        padding: 0.75rem 1.5rem;
        background: #e0e0e0;
        color: #666;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .cancel-review-btn:hover {
        background: #d0d0d0;
    }

    /* Reviews List */
    .reviews-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .review-item {
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .reviewer-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #ff4d4d, #ff8a00);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .reviewer-details {
        display: flex;
        flex-direction: column;
    }

    .reviewer-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.25rem;
    }

    .review-date {
        color: #666;
        font-size: 0.85rem;
    }

    .review-rating {
        display: flex;
        gap: 0.1rem;
    }

    .review-rating .star {
        color: #e0e0e0;
        font-size: 1.1rem;
    }

    .review-rating .star.filled {
        color: #ffd700;
    }

    .review-comment {
        color: #333;
        line-height: 1.6;
        font-size: 0.95rem;
        white-space: pre-wrap;
    }

    .no-reviews {
        text-align: center;
        padding: 3rem 1rem;
        color: #999;
    }

    .no-reviews p {
        margin: 0;
        font-size: 1.1rem;
    }
    </style>
</div>
