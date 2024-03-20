# Laravel Livewire Datatable
Laravel Livewire Datatable is a dynamic datatable component for Laravel applications using the Livewire library. It allows you to create interactive datatables with features like sorting, filtering, pagination, and data exporting.

## Requirements
- Livewire version 3.0 or greater
- Laravel version 10 or greater
- Alpine js v3
## Installation
To get started with Laravel Livewire Datatable, follow these steps:
1. Install the package using Composer:

```cmd
composer require joydeep-bhowmik/livewire-datatable
```

2. Add this service providers in your /bootstrap/providers.php (Laravel 11 +)
```PHP
 // datatable service provider
JoydeepBhowmik\LivewireDatatable\Providers\DataTableServiceProvider::class,
```

3. Publish the stylesheet :

```cmd
php artisan vendor:publish --tag=joydeep-bhowmik-livewire-datatable-css
```

4. Use the stylesheet :
   
```HTML
<link rel="stylesheet" href="{{ asset('joydeep-bhowmik/livewire-datatable/css/data-table.css') }}">
```
## Usage
1. ### Configuring the Datatable


```PHP


<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Carbon;
use JoydeepBhowmik\LivewireDatatable\Datatable;

class Table extends Datatable
{
    public $model = User::class;
    public function table()
    {
        //table method must return an array
        return [
            // id field
            $this->field('id')
                ->label('Id')
                ->sortable(),
                /* you can also run custom query for sort
                ->sortable(function($query,$direction){
                    $query->orderBy('id', $direction);
                })
                */
            // email field
            $this->field('email')
                ->label('Email')
                ->searchable(),
               /* you can also run custom query for search like
                ->searchable(function($query,$keyword){
                    $query->where('email', 'like', '%' . $keyword . '%')
                })
                */
            // created at
            $this->field('created_at')
                ->label('Created At')
                ->value(function ($row) {
                    return Carbon::createFromTimeStamp(strtotime($row->created_at))->diffForHumans();
                })
                /*
                You can also give it a text value like
                ->value('ok')
                */
                ->sortable(),
        ];
    }
}
```
2. ### Rendering the Datatable
```HTML
   <livewire:table />
```
### Custom Query
Sometime we need to use customer query in our datatable when using something like join
``` PHP
class Table extends Datatable
{
    //dont use model when using builder
    //public $model = Product::class;
 public function builder()
    {
        return Product::leftJoin('stocks', 'stocks.product_id', 'products.id');
    }
}
```
when using join u need modify your fields like this 

```PHP 
     public function table()
    {
        return [
            $this->field('id')
                ->label('Id')
                ->table('products')
                ->as('product_id')// this is optional add according to your query
                ->sortable()
                ->searchable()
            ,
            $this->field('id')
                ->label('Stock Id')
                ->table('stocks')
                ->as('stock_id')// this is optional add according to your query
                ->sortable()
                ->searchable()
                //add more here
        ];
    }           
```
### Filters

```PHP
// Example: Define filters
public function filters()
{
   return [
            //input type select
            $this->filter('visibility')
                ->label('Visibility')
                //this options are required for input type select
                ->options([
                    '' => 'Select',
                    'public' => 'Public',
                    'private' => 'private',
                ])
                ->type('select')
                ->query(function ($query, $value) {
                    $query->where('products.id',$value);
                }),
            $this->filter('stock_id')
                ->label('Stock id')
                ->type('text')
                ->placeholder('Enter text id')
                ->query(function ($query, $value) {
                    $query->where('products.id', $value);
                })
                /*
                ->value('some text') //optional
                */,
            $this->filter('stock')
                ->label('In stock')
                ->type('checkbox')
                ->query(function ($query, $value) {
                    $query->where('products.id', $value);
                }),
                //add other filters
        ];
}
```

### Bulk actions

Sometimes we need to delete some selected rows of data or performs mass action on them . 

```PHP
public $checkbox = true;
public $primaryKey = "id";
public function delete(){
    foreach($this->ids as $id){
        $product=Product::find($id);
        $product->delete();
    }
}
public function bulkActions()
    {
        return [
            $this->button('delete')
                ->text('Delete')
                ->action('delete')//this is a public method of your component
                ->confirm('Are u sure?')// optional,
        ];
    }

```

### Hide the header
if you need to hide all the search, filter etc button just add a public property $headers=false to the component class
```PHP 
class Table extends Datatable
{
public $headers=false;

}
```
