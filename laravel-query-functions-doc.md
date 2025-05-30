# Laravel Query Functions Documentation

## Introduction

Laravel provides a fluent, expressive query builder that makes it easy to perform database operations. This document covers the most commonly used query functions in Laravel, including retrieval methods, filtering, ordering, and collection manipulation.

## Basic Query Builder Methods

### Retrieving Results

#### `get()`

Retrieves all records from the query as a collection:

```php
// Get all users
$users = DB::table('users')->get();

// With Eloquent
$users = User::all();
$users = User::where('active', 1)->get();
```

#### `first()`

Retrieves only the first record matching the query:

```php
// Get first active user
$user = DB::table('users')->where('active', 1)->first();

// With Eloquent
$user = User::where('active', 1)->first();
```

#### `find()`

Retrieves a record by its primary key:

```php
// Get user with ID 1
$user = User::find(1);

// Get multiple users by IDs
$users = User::find([1, 2, 3]);
```

#### `findOrFail()`

Retrieves a record by its primary key or throws a ModelNotFoundException:

```php
try {
    $user = User::findOrFail(1);
} catch (ModelNotFoundException $e) {
    // Handle not found
}
```

#### `value()`

Retrieves a single value from the first record:

```php
// Get the name of the first user
$name = DB::table('users')->where('id', 1)->value('name');

// With Eloquent
$name = User::where('id', 1)->value('name');
```

### Chunking Results

#### `chunk()`

Processes large result sets in chunks to conserve memory:

```php
// Process 100 users at a time
User::chunk(100, function ($users) {
    foreach ($users as $user) {
        // Process each user
    }
});
```

#### `chunkById()`

Similar to chunk() but uses ID for more reliable chunking with large datasets:

```php
User::chunkById(100, function ($users) {
    foreach ($users as $user) {
        // Process each user
    }
});
```

#### `lazy()`

Returns a LazyCollection to process large datasets with minimal memory usage:

```php
User::lazy()->each(function ($user) {
    // Process each user with minimal memory usage
});
```

### Aggregates

#### `count()`

Counts the number of records:

```php
$count = User::where('active', 1)->count();
```

#### `max()`, `min()`, `avg()`, `sum()`

Perform aggregate functions on columns:

```php
$maxAge = User::max('age');
$minAge = User::min('age');
$avgAge = User::where('active', 1)->avg('age');
$totalPoints = User::sum('points');
```

## Filtering and Ordering

### Basic Where Clauses

#### `where()`

Adds a basic where clause:

```php
// Basic where
$users = User::where('status', 'active')->get();

// With operator
$users = User::where('age', '>=', 18)->get();

// Multiple conditions
$users = User::where('status', 'active')
             ->where('age', '>=', 18)
             ->get();
```

#### `orWhere()`

Adds an "or where" clause:

```php
$users = User::where('status', 'active')
             ->orWhere('status', 'pending')
             ->get();
```

#### `whereBetween()`, `whereNotBetween()`

Filters records where a column's value is between two values:

```php
$users = User::whereBetween('age', [18, 65])->get();
$users = User::whereNotBetween('age', [18, 65])->get();
```

#### `whereIn()`, `whereNotIn()`

Filters records where a column's value is in an array:

```php
$users = User::whereIn('id', [1, 2, 3])->get();
$users = User::whereNotIn('id', [1, 2, 3])->get();
```

#### `whereNull()`, `whereNotNull()`

Filters records where a column is null or not null:

```php
$users = User::whereNull('deleted_at')->get();
$users = User::whereNotNull('email_verified_at')->get();
```

#### `whereDate()`, `whereMonth()`, `whereDay()`, `whereYear()`

Filters records by date components:

```php
$users = User::whereDate('created_at', '2023-01-01')->get();
$users = User::whereMonth('created_at', '1')->get(); // January
$users = User::whereDay('created_at', '1')->get();
$users = User::whereYear('created_at', '2023')->get();
```

### Advanced Where Clauses

#### `whereHas()`, `whereDoesntHave()`

Filters records based on relationship existence:

```php
// Users who have at least one post
$users = User::whereHas('posts')->get();

// Users who have at least 3 posts
$users = User::whereHas('posts', function ($query) {
    $query->where('active', 1);
}, '>=', 3)->get();

// Users who don't have any posts
$users = User::whereDoesntHave('posts')->get();
```

#### `whereExists()`

Filters records using a subquery:

```php
$users = User::whereExists(function ($query) {
    $query->select(DB::raw(1))
          ->from('posts')
          ->whereColumn('posts.user_id', 'users.id');
})->get();
```

#### `when()`

Conditionally applies a query constraint:

```php
$users = User::when($request->has('search'), function ($query) use ($request) {
    return $query->where('name', 'like', '%' . $request->search . '%');
})->get();
```

### Ordering Results

#### `orderBy()`

Orders the results by a column:

```php
// Ascending order (default)
$users = User::orderBy('name')->get();

// Descending order
$users = User::orderBy('name', 'desc')->get();

// Multiple columns
$users = User::orderBy('name')
             ->orderBy('created_at', 'desc')
             ->get();
```

#### `latest()`, `oldest()`

Orders by created_at timestamp:

```php
// Latest first (descending created_at)
$users = User::latest()->get();

// Oldest first (ascending created_at)
$users = User::oldest()->get();

// Using a different timestamp column
$users = User::latest('updated_at')->get();
```

#### `inRandomOrder()`

Orders the results randomly:

```php
$randomUser = User::inRandomOrder()->first();
```

## Collection Methods

After retrieving data with `get()`, Laravel returns a Collection object with many helpful methods:

### Data Extraction

#### `pluck()`

Extracts a list of a single column's values:

```php
// Get all user names
$names = User::pluck('name');

// Get names with IDs as keys
$names = User::pluck('name', 'id');
```

#### `keyBy()`

Keys the collection by a field:

```php
$users = User::all()->keyBy('id');
// Access: $users[1] for user with ID 1
```

### Mapping and Transformation

#### `map()`

Transforms each item in the collection:

```php
$userNames = User::all()->map(function ($user) {
    return $user->first_name . ' ' . $user->last_name;
});
```

#### `mapWithKeys()`

Maps a collection and returns key-value pairs:

```php
$userEmails = User::all()->mapWithKeys(function ($user) {
    return [$user->id => $user->email];
});
```

#### `flatMap()`

Maps and flattens the result:

```php
$allPhoneNumbers = User::all()->flatMap(function ($user) {
    return $user->phone_numbers;
});
```

### Filtering

#### `filter()`

Filters items in the collection:

```php
$activeUsers = User::all()->filter(function ($user) {
    return $user->active == 1;
});
```

#### `reject()`

Opposite of filter - removes items that pass the test:

```php
$inactiveUsers = User::all()->reject(function ($user) {
    return $user->active == 1;
});
```

#### `where()` (Collection method)

Filters collection by key/value:

```php
$activeUsers = User::all()->where('active', 1);
```

### Iteration

#### `each()`

Iterates over each item in the collection:

```php
User::all()->each(function ($user) {
    // Process each user
    echo $user->name;
});
```

#### `tap()`

Passes the collection to a callback without affecting the collection:

```php
$users = User::all()->tap(function ($collection) {
    Log::info('Collection count: ' . $collection->count());
})->filter(function ($user) {
    return $user->active == 1;
});
```

### Aggregation

#### `count()`, `sum()`, `avg()`, `max()`, `min()`

Performs calculations on the collection:

```php
$count = User::all()->count();
$totalAge = User::all()->sum('age');
$averageAge = User::all()->avg('age');
$maxAge = User::all()->max('age');
$minAge = User::all()->min('age');
```

#### `groupBy()`

Groups collection items by a key:

```php
$usersByRole = User::all()->groupBy('role');
```

#### `reduce()`

Reduces the collection to a single value:

```php
$totalAge = User::all()->reduce(function ($carry, $user) {
    return $carry + $user->age;
}, 0);
```

## Pagination

### `paginate()`

Paginates the results:

```php
// 15 users per page
$users = User::paginate(15);

// With additional constraints
$users = User::where('active', 1)->paginate(15);
```

### `simplePaginate()`

Simpler, faster pagination (no total count):

```php
$users = User::simplePaginate(15);
```

## Relationships

### Eager Loading

#### `with()`

Eager loads relationships to avoid N+1 query problems:

```php
// Load users with their posts
$users = User::with('posts')->get();

// Load multiple relationships
$users = User::with(['posts', 'profile'])->get();

// Nested relationships
$users = User::with('posts.comments')->get();
```

#### `withCount()`

Counts related models:

```php
// Add posts_count attribute
$users = User::withCount('posts')->get();
foreach ($users as $user) {
    echo $user->posts_count;
}

// With constraints
$users = User::withCount(['posts' => function ($query) {
    $query->where('active', 1);
}])->get();
```

## Best Practices

1. **Use Eloquent Models** when possible for cleaner, more maintainable code
2. **Eager Load Relationships** to avoid N+1 query problems
3. **Use Chunking** for processing large datasets
4. **Use Query Scopes** to encapsulate common query constraints
5. **Use When()** for conditional query parts
6. **Use Database Transactions** for operations that need to be atomic

## Example: Combining Multiple Methods

```php
// Complex query combining multiple methods
$users = User::with(['posts', 'profile'])
    ->withCount(['posts' => function ($query) {
        $query->where('active', 1);
    }])
    ->where('active', 1)
    ->when($request->has('search'), function ($query) use ($request) {
        return $query->where('name', 'like', '%' . $request->search . '%');
    })
    ->orderBy('name')
    ->paginate(15);

// Processing with collection methods
$formattedUsers = $users->map(function ($user) {
    return [
        'id' => $user->id,
        'full_name' => $user->first_name . ' ' . $user->last_name,
        'email' => $user->email,
        'active_posts_count' => $user->posts_count,
        'has_profile' => $user->profile ? true : false
    ];
});
```

## Resources

- [Laravel Documentation - Eloquent](https://laravel.com/docs/eloquent)
- [Laravel Documentation - Query Builder](https://laravel.com/docs/queries)
- [Laravel Documentation - Collections](https://laravel.com/docs/collections)
