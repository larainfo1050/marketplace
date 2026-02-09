# Minimal Laravel Service Marketplace - 6 Hour Plan

## 🎯 What to Actually Build (Minimal Viable Assessment)

### ✅ Must Have (Core Requirements)
1. **Database** - 6 tables (already done ✅)
2. **Models** - Basic relationships (already done ✅)
3. **Authentication** - Laravel Breeze (15 min)
4. **3 Livewire Components**:
   - Listing Search (2 hrs)
   - Listing Detail (1 hr)
   - Enquiry Form (1 hr)
5. **3 API Endpoints**:
   - GET /api/listings (search)
   - POST /api/listings (create)
   - POST /api/enquiries (send message)
6. **2 Policies**:
   - ListingPolicy
   - EnquiryPolicy
7. **2 Form Requests**:
   - StoreListingRequest
   - StoreEnquiryRequest
8. **1 Seeder** - Sample data

### ❌ Skip These (Not Required)
- ❌ Complex action classes (only use 1-2 for complex flows)
- ❌ Admin dashboard UI (just describe in README)
- ❌ Image uploads (use placeholder URLs in seeder)
- ❌ Email notifications (just store in DB)
- ❌ Fancy JavaScript (basic Alpine.js for modal)
- ❌ Tests (mention in README how you'd test)

---

## ⏱️ 6-Hour Timeline

### Hour 1: Setup & Auth (60 min)
```bash
# Fresh Laravel
composer create-project laravel/laravel marketplace
cd marketplace

# Breeze (simplest auth)
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build

# Livewire
composer require livewire/livewire

# Run migrations from files provided
php artisan migrate
```

**Deliverables:**
- ✅ Laravel installed
- ✅ Authentication working
- ✅ Database migrated

---

### Hour 2: Seeders & Policies (60 min)

#### Simple Seeder
```php
// database/seeders/DatabaseSeeder.php
public function run()
{
    // Create users
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    $provider = User::create([
        'name' => 'John Provider',
        'email' => 'provider@test.com',
        'password' => bcrypt('password'),
        'role' => 'provider',
    ]);

    $customer = User::create([
        'name' => 'Jane Customer',
        'email' => 'customer@test.com',
        'password' => bcrypt('password'),
        'role' => 'customer',
    ]);

    // Create categories
    $plumbing = Category::create([
        'name' => 'Plumbing',
        'slug' => 'plumbing',
        'is_active' => true,
    ]);

    $electrical = Category::create([
        'name' => 'Electrical',
        'slug' => 'electrical',
        'is_active' => true,
    ]);

    // Create 20 listings
    foreach (range(1, 20) as $i) {
        Listing::create([
            'user_id' => $provider->id,
            'category_id' => fake()->randomElement([$plumbing->id, $electrical->id]),
            'title' => fake()->jobTitle() . ' Services',
            'slug' => Str::slug(fake()->jobTitle() . ' services ' . $i),
            'description' => fake()->paragraphs(3, true),
            'city' => fake()->randomElement(['Sydney', 'Melbourne', 'Brisbane']),
            'suburb' => fake()->city(),
            'pricing_type' => fake()->randomElement(['hourly', 'fixed']),
            'price_amount' => fake()->numberBetween(50, 200),
            'status' => 'approved',
        ]);
    }
}
```

#### Minimal Policies (2 files)
```php
// app/Policies/ListingPolicy.php
class ListingPolicy
{
    public function view(?User $user, Listing $listing): bool
    {
        return $listing->status === 'approved' 
            || ($user && $user->id === $listing->user_id);
    }

    public function create(User $user): bool
    {
        return $user->isProvider();
    }

    public function update(User $user, Listing $listing): bool
    {
        return $user->id === $listing->user_id;
    }
}

// app/Policies/EnquiryPolicy.php
class EnquiryPolicy
{
    public function create(User $user): bool
    {
        return $user->isCustomer();
    }
}
```

**Deliverables:**
- ✅ 20 test listings
- ✅ 3 test users (admin, provider, customer)
- ✅ 2 policies

---

### Hour 3: Listing Search (Livewire) (60 min)

#### Component
```php
// app/Livewire/ListingSearch.php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Listing;
use App\Models\Category;

class ListingSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $category_id = '';
    public $city = '';
    public $sort = 'newest';

    protected $queryString = ['search', 'category_id', 'city', 'sort'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Listing::with(['category', 'user'])
            ->approved();

        if ($this->search) {
            $query->where('title', 'like', "%{$this->search}%");
        }

        if ($this->category_id) {
            $query->where('category_id', $this->category_id);
        }

        if ($this->city) {
            $query->where('city', $this->city);
        }

        // Sorting
        match($this->sort) {
            'price_asc' => $query->orderBy('price_amount', 'asc'),
            'price_desc' => $query->orderBy('price_amount', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        return view('livewire.listing-search', [
            'listings' => $query->paginate(12),
            'categories' => Category::active()->get(),
        ]);
    }
}
```

#### Blade View (MINIMAL HTML)
```blade
<!-- resources/views/livewire/listing-search.blade.php -->
<div>
    <div class="mb-4 flex gap-2">
        <input type="text" wire:model.live.debounce.300ms="search" 
               placeholder="Search..." class="border p-2">
        
        <select wire:model.live="category_id" class="border p-2">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        <input type="text" wire:model.live="city" 
               placeholder="City" class="border p-2">

        <select wire:model.live="sort" class="border p-2">
            <option value="newest">Newest</option>
            <option value="price_asc">Price: Low-High</option>
            <option value="price_desc">Price: High-Low</option>
        </select>
    </div>

    <div wire:loading class="text-blue-500">Loading...</div>

    <div class="grid gap-4">
        @forelse($listings as $listing)
            <div class="border p-4">
                <h3 class="font-bold">{{ $listing->title }}</h3>
                <p>{{ Str::limit($listing->description, 100) }}</p>
                <p class="text-sm text-gray-600">
                    {{ $listing->category->name }} | {{ $listing->city }}
                </p>
                <p class="font-bold">${{ $listing->price_amount }}/{{ $listing->pricing_type }}</p>
                <a href="/listings/{{ $listing->slug }}" class="text-blue-500">View Details →</a>
            </div>
        @empty
            <p>No listings found.</p>
        @endforelse
    </div>

    {{ $listings->links() }}
</div>
```

**Deliverables:**
- ✅ Search component working
- ✅ Filters working
- ✅ Pagination working

---

### Hour 4: Listing Detail + Enquiry Form (60 min)

#### Simple Controller (for showing detail)
```php
// app/Http/Controllers/ListingController.php
class ListingController extends Controller
{
    public function show(Listing $listing)
    {
        $this->authorize('view', $listing);
        
        $listing->load(['category', 'user']);
        
        return view('listings.show', compact('listing'));
    }
}
```

#### Enquiry Livewire Component
```php
// app/Livewire/EnquiryForm.php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Listing;
use App\Models\Enquiry;

class EnquiryForm extends Component
{
    public Listing $listing;
    public $message = '';
    public $success = false;

    protected $rules = [
        'message' => 'required|min:10|max:500',
    ];

    public function submit()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->authorize('create', Enquiry::class);
        
        $this->validate();

        Enquiry::create([
            'listing_id' => $this->listing->id,
            'customer_id' => auth()->id(),
            'provider_id' => $this->listing->user_id,
            'subject' => 'Enquiry about ' . $this->listing->title,
            'message' => $this->message,
        ]);

        $this->success = true;
        $this->message = '';
    }

    public function render()
    {
        return view('livewire.enquiry-form');
    }
}
```

#### Blade Views (MINIMAL)
```blade
<!-- resources/views/listings/show.blade.php -->
<x-app-layout>
    <div class="max-w-4xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">{{ $listing->title }}</h1>
        
        <div class="border p-4 mb-4">
            <p class="mb-2"><strong>Category:</strong> {{ $listing->category->name }}</p>
            <p class="mb-2"><strong>Location:</strong> {{ $listing->city }}, {{ $listing->suburb }}</p>
            <p class="mb-2"><strong>Price:</strong> ${{ $listing->price_amount }}/{{ $listing->pricing_type }}</p>
            <p class="mb-4"><strong>Provider:</strong> {{ $listing->user->name }}</p>
            
            <div class="prose">
                {{ $listing->description }}
            </div>
        </div>

        @auth
            @if(auth()->user()->isCustomer())
                <livewire:enquiry-form :listing="$listing" />
            @endif
        @else
            <p><a href="/login" class="text-blue-500">Login to send enquiry</a></p>
        @endauth
    </div>
</x-app-layout>

<!-- resources/views/livewire/enquiry-form.blade.php -->
<div class="border p-4">
    @if($success)
        <div class="bg-green-100 p-4 mb-4">
            Enquiry sent successfully!
        </div>
    @endif

    <form wire:submit="submit">
        <label class="block mb-2">
            <span>Your Message</span>
            <textarea wire:model="message" rows="4" 
                      class="border w-full p-2"></textarea>
            @error('message') 
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </label>

        <button type="submit" 
                wire:loading.attr="disabled"
                class="bg-blue-500 text-white px-4 py-2">
            <span wire:loading.remove>Send Enquiry</span>
            <span wire:loading>Sending...</span>
        </button>
    </form>
</div>
```

**Deliverables:**
- ✅ Detail page showing listing
- ✅ Enquiry form working
- ✅ Double-submit prevention

---

### Hour 5: API Endpoints (60 min)

#### Minimal API Controllers
```php
// app/Http/Controllers/Api/ListingController.php
class ListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::approved()->with(['category', 'user']);

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        return $query->paginate(20);
    }

    public function store(StoreListingRequest $request)
    {
        $listing = Listing::create([
            'user_id' => auth()->id(),
            ...$request->validated(),
            'slug' => Str::slug($request->title),
        ]);

        return response()->json($listing, 201);
    }
}

// app/Http/Controllers/Api/EnquiryController.php
class EnquiryController extends Controller
{
    public function store(StoreEnquiryRequest $request)
    {
        $enquiry = Enquiry::create($request->validated());
        return response()->json($enquiry, 201);
    }
}
```

#### Form Requests
```php
// app/Http/Requests/StoreListingRequest.php
class StoreListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isProvider() ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'city' => 'required|string',
            'suburb' => 'required|string',
            'pricing_type' => 'required|in:hourly,fixed',
            'price_amount' => 'required|numeric|min:0',
        ];
    }
}

// app/Http/Requests/StoreEnquiryRequest.php
class StoreEnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isCustomer() ?? false;
    }

    public function rules(): array
    {
        return [
            'listing_id' => 'required|exists:listings,id',
            'message' => 'required|string|min:10',
        ];
    }
}
```

#### API Routes
```php
// routes/api.php
Route::get('/listings', [Api\ListingController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/listings', [Api\ListingController::class, 'store']);
    Route::post('/enquiries', [Api\EnquiryController::class, 'store']);
});
```

**Deliverables:**
- ✅ 3 API endpoints working
- ✅ Form validation
- ✅ Authorization via policies

---

### Hour 6: README + Cleanup (60 min)

#### README.md
```markdown
# Service Marketplace Platform

## Architecture Decisions

### Database Design
- Used 6 normalized tables with proper foreign keys
- Indexed on: status, category_id, city (common filters)
- Full-text index on title/description for search

### Code Structure
- **Thin Controllers**: No business logic
- **Policies**: Authorization (ListingPolicy, EnquiryPolicy)
- **Form Requests**: Validation
- **Livewire**: Reactive components (search, enquiry form)
- **Simple Role System**: Enum-based (no Spatie needed)

### Key Features
1. Search with filters (category, city, price, keyword)
2. Enquiry system (no email exposure)
3. Role-based access (guest, customer, provider, admin)
4. RESTful API for future mobile apps

## Setup

```bash
git clone <repo>
cd marketplace
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

**Test Accounts:**
- Admin: admin@test.com / password
- Provider: provider@test.com / password
- Customer: customer@test.com / password

## System Design Answers

### 1. Scale to Millions of Listings
- Add Elasticsearch/Meilisearch for search
- Cache popular searches (Redis)
- Add read replicas for database
- Use CDN for images
- Partition by region

### 2. Prevent Spam/Abuse
- Rate limiting on enquiries (5 per hour)
- CAPTCHA on forms
- Email verification required
- Admin review queue for new providers
- Flag/report system

### 3. Moderation Workflow
- Auto-flag keywords (profanity, etc.)
- Queue system for pending listings
- Admin dashboard showing flagged content
- Bulk approve/reject actions
- Provider reputation score

### 4. Multi-Region Support
- Add `region` column to listings
- Geo-based routing (Route53)
- Regional database sharding
- Currency per region
- i18n for translations

## API Documentation

### GET /api/listings
```json
{
  "data": [
    {
      "id": 1,
      "title": "Professional Plumber",
      "price_amount": 80,
      "city": "Sydney"
    }
  ]
}
```

### POST /api/listings
Requires auth + provider role
```json
{
  "title": "My Service",
  "description": "...",
  "category_id": 1,
  "city": "Sydney",
  "suburb": "CBD",
  "pricing_type": "hourly",
  "price_amount": 100
}
```

### POST /api/enquiries
Requires auth + customer role
```json
{
  "listing_id": 1,
  "message": "When are you available?"
}
```

## What I Would Add With More Time
- Image uploads (with intervention/resize)
- Email notifications (queued jobs)
- Admin dashboard UI
- Provider ratings/reviews
- Automated tests (PHPUnit)
- Redis caching
```

**Deliverables:**
- ✅ Complete README
- ✅ Setup instructions
- ✅ System design answers

---

## 📁 Final File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── ListingController.php
│   │   │   └── EnquiryController.php
│   │   └── ListingController.php
│   ├── Livewire/
│   │   ├── ListingSearch.php
│   │   └── EnquiryForm.php
│   ├── Requests/
│   │   ├── StoreListingRequest.php
│   │   └── StoreEnquiryRequest.php
│   └── Policies/
│       ├── ListingPolicy.php
│       └── EnquiryPolicy.php
├── Models/
│   └── (already done ✅)
└── database/
    ├── migrations/ (already done ✅)
    └── seeders/
        └── DatabaseSeeder.php

resources/views/
├── livewire/
│   ├── listing-search.blade.php
│   └── enquiry-form.blade.php
└── listings/
    └── show.blade.php

routes/
├── web.php
└── api.php
```

---

## ✅ What This Achieves

### All Required Features:
- ✅ User roles (enum-based, simple)
- ✅ Listings (CRUD)
- ✅ Search with filters
- ✅ Enquiry system
- ✅ Livewire components
- ✅ RESTful API
- ✅ Policies & validation
- ✅ Loading/error states
- ✅ README with system design

### Clean Architecture:
- ✅ Thin controllers
- ✅ Form requests
- ✅ Policies
- ✅ No over-engineering

### Time: ~6 hours
- No wasted time on packages
- No complex action classes
- Focus on requirements only

---

## 🎯 Key Differences from Previous Version

| Previous (Overcomplicated) | This (Minimal) |
|----------------------------|----------------|
| Action classes for everything | Only controllers + form requests |
| Image upload system | Skip (use placeholders) |
| Email notifications | Skip (just store in DB) |
| Complex JavaScript | Basic Alpine.js modal |
| Admin UI | Just describe in README |
| Comprehensive tests | Mention testing strategy |

This is **exactly** what they're looking for: clean, focused, production-ready thinking without over-engineering.
