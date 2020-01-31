# Laravel Livewire Tables

![Laravel Livewire Tables](https://i.imgur.com/Jg5WPOa.gif)

A dynamic, responsive Laravel Livewire table component with searching, sorting, checkboxes, and pagination.

- [Support](https://github.com/kdion4891/laravel-livewire-tables/issues)
- [Contributions](https://github.com/kdion4891/laravel-livewire-tables/pulls)
- [Buy me a coffee](https://paypal.me/kjjdion)

# Installation

This package was designed to work well with [Laravel frontend scaffolding](https://laravel.com/docs/master/frontend). Make sure you have already done frontend scaffolding before using this package.

Installing via composer:

    composer require kdion4891/laravel-livewire-tables
    
This package uses [Font Awesome](https://fontawesome.com) for icons. If you don't already have it installed, it's as simple as:

    npm i @fortawesome/fontawesome-free
    
Then add this to `resources/sass/app.scss`:
    
    @import '~@fortawesome/fontawesome-free/css/all.min.css';
    
And don't forget to `npm run dev`!

# Making Table Components

Using the `make` command:

    php artisan make:table UserTable --model=User

This creates your new table component in the `app/Http/Livewire` folder.

After making a component, you may want to edit the `query` and `column` methods:

    class UserTable extends TableComponent
    {
        public function query()
        {
            return User::query();
        }
    
        public function columns()
        {
            return [
                Column::make('ID')->searchable()->sortable(),
                Column::make('Created At')->searchable()->sortable(),
                Column::make('Updated At')->searchable()->sortable(),
            ];
        }
    }
    
You don't have to use the `render()` method in your table component or worry about a component view, because the package handles that automatically.

# Using Table Components

You use table components in views just like any other Livewire component:

    @livewire('user-table')

Now all you have to do is update your table component class!

# Table Component Properties

### `$table_class`

Sets the CSS class names to use on the `<table>`. Defaults to `table-hover`.

Example:

    public $table_class = 'table-hover table-striped';
    
Or, via `.env` to apply globally:

    TABLE_CLASS="table-hover table-striped"

### `$thead_class`

Sets the CSS class names to use on the `<thead>`. Defaults to `thead-light`.

Example:

    public $thead_class = 'thead-dark';
    
Or, via `.env` to apply globally:

    TABLE_THEAD_CLASS="thead-dark"
    
### `$header_view`

Sets a custom view to use for the table header (displayed next to the search).

Example:

    public $header_view = 'users.table-header';
    
**Protip: any view you reference in your table component can use Livewire actions, triggers, etc!**
    
    {{-- resources/views/users/table-header.blade.php --}}
    <button class="btn btn-primary" wire:click="createUser">Create User</button>
    
### `$footer_view`

Sets a custom view to use for the table footer (displayed next to the pagination).

Example:

    public $footer_view = 'users.table-footer';
    
### `$checkbox`

Boolean for if the table should use checkboxes or not. Defaults to `true`.

Example:

    public $checkbox = false;
    
### `$checkbox_attribute`

Sets the attribute name to use for `$checkbox_values`. Defaults to `id`. I recommend keeping this as `id`.

Example:

    public $checkbox_attribute = 'id';
    
### `$checkbox_values`

Contains an array of checked values. For example, if `$checkbox_attribute` is set to `id`, this will contain an array of checked model `id`s.
Then you can use those `id`s to do whatever you want in your component.

### `$sort_attribute`

Sets the default attribute to sort by. Defaults to `id`. This also works with counts and relationships.

Example:

    public $sort_attribute = 'created_at';
    
Count example (if you added `->withCount('relations')` to the `query()` method):

    public $sort_attribute = 'relations_count';
    
Relationship example (if you added `->with('relation')` to the `query()` method):

    public $sort_attribute = 'relation.name';
    
Notice the use of the dot notation. You use this when declaring column relationship attributes as well.

### `$sort_direction`

Sets the default direction to sort by. Accepts `asc` or `desc`. Defaults to `desc`.

Example:

    public $sort_direction = 'asc';
    
### `$per_page`

Sets the amount of rows to display per page. Defaults to `15`.

Example:

    public $per_page = 25;
    
Or, via `.env` to apply globally:

    TABLE_PER_PAGE=25

# Table Component Methods

### `query()`

This method returns an [Eloquent](https://laravel.com/docs/master/eloquent) model query to be used by the table.

Example:

    public function query()
    {
        return Car::with('brand')->withCount('accidents');
    }

### `columns()`

This method returns an array of `Column`s to use in the table.

Example:

    public function columns()
    {
        return [
            Column::make('ID')->searchable()->sortable(),
            Column::make('Brand Name', 'brand.name')->searchable()->sortable(),
            Column::make('Name')->searchable()->sortable(),
            Column::make('Color')->view('cars.table.color')->searchable()->sortable(),
            Column::make('Accidents', 'accidents_count')->sortable(),
            Column::make('')->view('cars.table.actions'),
        ];
    }

Declaring `Column`s is similar to declaring Laravel Nova fields. [Jump to the column declaration section](#table-column-declaration) to learn more.

### `mount()`

This method sets the initial table properties. If you have to override it, be sure to call `$this->setTableProperties()`.

Example:

    public function mount()
    {
        $this->setTableProperties();
        
        // my custom code
    }

### `render()`

This method renders the table component view. If you have to override it, be sure to `return $this->tableView()`.

Example:

    public function render()
    {
        // my custom code
        
        return $this->tableView();
    }
    
# Table Column Declaration

The `Column` class is used to declare your table columns.

    public function columns()
    {
        return [
            Column::make('ID')->searchable()->sortable(),
            Column::make('Created At')->searchable()->sortable(),
            Column::make('Updated At')->searchable()->sortable(),
        ];
    }

### `make($heading, $attribute = null)`

##### `$heading`

The heading to use for the table column, e.g. `Created At`.

##### `$attribute`

The attribute to use for the table column value. If null, it will use a snake cased `$heading`.

You can also specify `_count`s and relationship attributes with a dot notation.

For counts, let's say I added `withCount()` to my `query()`:

    public function query()
    {
        return Car::withCount('accidents');
    }

Now I can create a column using this count like so:

    Column::make('Accidents', 'accidents_count')->sortable(),
    
For relationships, let's say I added `with()` to my `query()`:

    public function query()
    {
        return Car::with('brand');
    }
    
Now I can create a column using any of the relationship attributes like so:

    Column::make('Brand ID', 'brand.id')->searchable()->sortable(),
    Column::make('Brand Name', 'brand.name')->searchable()->sortable(),

### `searchable()`

Sets the column to be searchable.

### `sortable()`

Sets the column to be sortable.

### `view($view)`

Sets a custom view to use for the column.

Example:

    Column::make('Paint Color')->searchable()->sortable()->view('cars.table-paint-color'),
    
Notice how the column is still `searchable()` and `sortable()`, because the `Car` model contains a `paint_color` attribute!

If you're making a view-only column (for action buttons, etc), just don't make it searchable or sortable:

    Column::make('Actions')->view('cars.table-actions'),

**Custom column views are passed `$row` and `$column` objects, as well as variables passed from the table component.**

For the `Paint Color` example, we can use the `paint_color` attribute from the model like so:

    {{-- resources/views/cars/table-paint-color.blade.php --}}
    <i class="fa fa-circle" style="color: {{ $row->paint_color }};"></i>

For the action buttons example, we can use the `id` attribute from the model like so:

    {{-- resources/views/cars/table-actions.blade.php --}}
    <button class="btn btn-primary" wire:click="showCar({{ $row->id }})">Show</button>
    <button class="btn btn-primary" wire:click="editCar({{ $row->id }})">Edit</button>

Using a custom view for a relationship column? No problem:

    {{-- resources/views/cars/table-brand-name.blade.php --}}
    {{ $row->brand->name }}

Think of `$row` as an instance of the model, because that's exactly what it is.

# Computing `<tr>` & `<td>` CSS Classes

Sometimes it's useful to be able to compute dynamic CSS classes for your table rows & columns. This package makes that easy.

All you have to do is create a couple of new methods in your model class to make it work. This is the model you use in the table component `query()` method.

Example:

    class Car extends Model
    {
        // my other model code here
        
        public function trClass()
        {
            if ($this->name == 'Silverado') return 'table-secondary';
            if ($this->accidents_count > 8) return 'table-danger';
            if ($this->brand->name == 'Ford') return 'table-primary';
    
            return null;
        }
    
        public function tdClass($attribute, $value)
        {
            if ($attribute == 'name' && $value == 'Silverado') return 'table-secondary';
            if ($attribute == 'accidents_count' && $value < 2) return 'table-success';
            if ($attribute == 'brand.name' && $value == 'Ford') return 'table-primary';
    
            return null;
        }
    }

### `trClass()`

This method is used to compute the `<tr>` CSS class for the model row. Notice the use of `$this` in the example. It also works great with counts and relationships!

### `tdClass($attribute, $value)`

This method is used to compute the `<td>` CSS class for the model column.

##### `$attribute`

The column attribute.

##### `$value`

The column value.

# Publishing Files

Publishing files is optional.

Publishing the table view file:

    php artisan vendor:publish --tag=table-views

Publishing the config file:

    php artisan vendor:publish --tag=table-config
    
