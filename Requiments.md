Technical Assessment (Laravel + Livewire + Blade + JavaScript)
Overview
This assessment is designed for a Laravel developer using Livewire, Blade templates, and JavaScript. You are expected to demonstrate strong architectural thinking, clean code, and production-ready decision making.
UI polish is not important; structure, correctness, and maintainability are.
Time Expectation
Estimated time: 6–8 hours.
Please do not exceed this significantly. Focus on clarity and quality.

Project Brief
Build a service marketplace platform similar in complexity to hipages.com.au or escortsandbabes.com.au.
The platform allows:
•	Service providers to create listings
•	Customers to search and contact providers
•	Administrators to moderate content

Core Requirements
User Roles
•	Guest (browse only)
•	Customer
•	Provider
•	Administrator
Listings
Each listing must include:
•	Title, description
•	Category
•	Location (city and suburb)
•	Pricing (hourly or fixed)
•	Status: draft, pending approval, approved, suspended
Search & Discovery
•	Keyword search
•	Filters: category, location, price range
•	Sorting: relevance, newest, price
Contact Flow
•	Customers can send enquiries to providers
•	Providers can reply
•	No direct email exposure (messages should be internal to the system)

Backend Requirements (Laravel)
You must:
•	Design appropriate database schema and migrations
•	Apply correct indexing
•	Keep controllers thin (no business logic in controllers)
•	Use services/actions (or use-cases) for business logic
•	Implement Form Request validation
•	Implement authorization using policies
Provide RESTful APIs for:
•	Authentication
•	Listing CRUD
•	Search
•	Enquiries
Even though the interface is built with Blade/Livewire, still build clean APIs (useful for future mobile apps, admin tools, integrations).

Frontend Requirements (Livewire + Blade + JavaScript)
You must build the following pages using Blade templates + Livewire components (and JavaScript where useful):
1) Listing Search Page
•	Keyword search input
•	Filters (category, location, price range)
•	Sorting (relevance/newest/price)
•	Paginated results
•	Clear loading states (Livewire loading UI is fine)
•	Clear empty/error states
2) Listing Detail Page
•	Display listing details
•	Show provider info (without exposing email)
•	Call-to-action to send enquiry (for customers)
3) Contact / Enquiry Form
•	Implement as a Livewire component
•	Validation errors shown inline
•	On success: user feedback and stored enquiry
•	Prevent double-submit and handle loading states
JavaScript Expectations
Use JavaScript intentionally (not excessively). Examples:
•	Enhancing UX for filters (debounced search input)
•	Modal for enquiry form
•	Small progressive enhancements using Alpine.js or vanilla JavaScript is fine
Focus on component structure and correctness, not design.

System Design Questions (Written)
Answer briefly in your README:
1.	How would you scale search to millions of listings?
2.	How would you prevent spam and abuse?
3.	How would you design a moderation workflow?
4.	How would you prepare this system for multi-region support?

Optional Bonus (Choose One)
Pick one:
•	Add Redis caching for search
•	Background job for enquiry notifications
•	Basic admin moderation interface (Blade/Livewire is ideal here)
•	Automated tests for the service layer (unit tests + feature tests where relevant)

Submission Instructions
Provide:
•	A Git repository link
•	A README explaining architecture decisions
•	Setup instructions (local environment)
Partial completion with strong design is preferred over rushed completeness.

Evaluation Criteria
We evaluate:
•	Architecture and separation of concerns
•	Code quality and best practices
•	Data modeling and indexing
•	Clarity of communication and reasoning

