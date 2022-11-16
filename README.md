# Laravel + PrimeVue Datatables

This is a simple, clean and fluent serve-side implementation of [PrimeVue Datatables](https://primefaces.org/primevue/showcase/#/datatable) in [Laravel](https://laravel.com/).

## Features
- Global Search including searching in relationships up to a depth of 3, e.g `author.user.name`
- Per-Column filtering out of the box
- Column Sorting with direction toggling
- Pagination with a dynamic `no. or records per page` setting
- Fully compatible with PrimeVue Datatable

## Installation

You can install the package via composer:

```bash
composer require tekrow/laravel-prime-datatables
```

## Usage

### Server Side
It is as simple as having this in your `index()` function of your controller:
```php
public function index(Request $request): JsonResponse
{
    $list = PrimevueDatatables::of(Book::query())->make();
    return response()->json([
        'success' => true,
        'payload' => $list,
    ]);
}
```
#### Required Query Parameters
The server-side implementation uses two parameters from your laravel request object to perform filtering, sorting and pagination:
You have to pass the following parameters as query params from the client:
1. Searchable Columns **(Passed as `searchable_columns`)** - Used to specify the columns that will be used to perform the global datatable search
2. Dt Params **(Passed as `dt_params`)** - This is the main Datatable event object as received from PrimeVue. See [Lazy Datatable](https://primefaces.org/primevue/showcase/#/datatable/lazy) documentation for more details
### Client Side:
Go through [PrimeVue's Lazy Datatable](https://primefaces.org/primevue/showcase/#/datatable/lazy) documentation for details on frontend implementation.

Here is an example of your `loadLazyData()` implementation:

```ts
const loadLazyData = async () => {
    loading.value = true;

    try {
        const res = await axios.get('/api/vehicles',{
            params: {
                dt_params: JSON.stringify(lazyParams.value),
                searchable_columns: JSON.stringify(['title','type.name','price']),
            },
        });

        records.value = res.data.payload.data;
        totalRecords.value = res.data.payload.total;
        loading.value = false;
    } catch (e) {
        records.value = [];
        totalRecords.value = 0;
        loading.value = false;
    }
};
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
