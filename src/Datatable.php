<?php
namespace JoydeepBhowmik\LivewireDatatable;

use JoydeepBhowmik\LivewireDatatable\utils\Button;
use JoydeepBhowmik\LivewireDatatable\utils\Field;
use JoydeepBhowmik\LivewireDatatable\utils\Filter;
use Livewire\Component;
use Livewire\WithPagination;

class Datatable extends Component
{
    use WithPagination;
    public $model;
    public $search;
    public $sort;
    public $sortDirections = [];
    public $perpage = 10;
    public $columns = [];
    public $filters = [];
    public $checkbox;
    public $primaryKey = 'id';
    public $ids = [];
    protected $_all_ids = [];
    public $headers = true;
    public $exportable = true;
    protected $records;
    public $select = [];

    public function columns($array): array
    {
        return $array;
    }
    public function filters()
    {
        return [];
    }
    public function resetFilters()
    {
        $this->filters = [];
    }
    public function table()
    {
        return [];
    }
    public function bulkActions()
    {
        return [];
    }
    public function builder()
    {

    }

    function placeholder()
    {
        return  <<<HTML
        <div class="flex items-center justify-center p-5"> 
            <svg class="h-6 w-6 animate-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path
                d="M3.05469 13H5.07065C5.55588 16.3923 8.47329 19 11.9998 19C15.5262 19 18.4436 16.3923 18.9289 13H20.9448C20.4474 17.5 16.6323 21 11.9998 21C7.36721 21 3.55213 17.5 3.05469 13ZM3.05469 11C3.55213 6.50005 7.36721 3 11.9998 3C16.6323 3 20.4474 6.50005 20.9448 11H18.9289C18.4436 7.60771 15.5262 5 11.9998 5C8.47329 5 5.55588 7.60771 5.07065 11H3.05469Z">
            </path>
            </svg>
        </div>
        HTML;
    }
    /**
     * The `mount` function in PHP is used to assign values to object properties and then call the
     * `setup` method.
     *
     * @param properties An array of key-value pairs representing the properties to be set on the
     * object.
     */
    public function mount($properties = [])
    {
        foreach ($properties as $key => $value) {
            $this->{$key} = $value;
        }
        $this->setup();
    }

    /**
     * The setup function initializes the columns and filters of a class based on the table and filter
     * objects.
     */
    public function setup()
    {
        $fields = $this->table();
        foreach ($fields as $field) {
            array_push($this->columns, $field->_field_id);
        }
        foreach ($this->filters() as $filter) {
            $this->filters[$filter->_filter_id] = ($filter->value ? $filter->value : null);
        }
    }
    /**
     * The function "get_fields" returns an array of fields that match the specified column IDs.
     *
     * @return an array of fields that match the field IDs specified in the `` property.
     */
    public function get_fields()
    {
        $fields = $this->table();
        return array_filter($fields, function ($field) {
            return in_array($field->_field_id, $this->columns);
        });

    }

    /**
     * The function applies filters to a query based on the filters defined in the class.
     *
     * @param query The  parameter is the query object or statement that you want to apply the
     * filters to. It could be an instance of a database query builder or an SQL statement.
     */
    public function applyFilters($query)
    {
        foreach ($this->filters() as $filter) {
            if (isset($this->filters[$filter->_filter_id]) && $this->filters[$filter->_filter_id]) {
                if (is_callable($filter->query)) {
                    call_user_func($filter->query, $query, $this->filters[$filter->_filter_id]);
                }
            }
        }
    }
    public function field($str)
    {
        return new Field($str);

    }
    public function filter($str)
    {
        return new Filter($str);

    }
    public function button($str)
    {
        return new Button($str);
    }
    /**
     * The function sets the sort field and direction for sorting.
     *
     * @param field The field parameter is the name of the field or property that you want to sort the
     * data by. It could be a string representing the name of the field, such as "name" or "age".
     * @param direction The direction parameter determines the sorting order of the field. It can have
     * two possible values: "asc" for ascending order or "desc" for descending order.
     */
    public function sortBy($field, $direction)
    {
        $this->sort = $field;
        $this->sortDirections = [];
        $this->sortDirections[$field] = $direction;

    }
    /**
     * The function returns the name of a field, either the "as" value if it exists or the "name" value.
     *
     * @param field The parameter "field" is an object that represents a field. It may have two
     * properties: "as" and "name".
     *
     * @return the value of the "as" property of the  object if it is set, otherwise it returns
     * the value of the "name" property of the  object.
     */
    public function get_field_name($field)
    {
        return (isset($field->as) ? $field->as : $field->name);
    }

    /**
     * The function generates a query based on the given fields, including selecting specific columns,
     * applying search and sort conditions, and applying additional filters.
     *
     * @param fields An array of objects representing the fields to be included in the query. Each
     * object should have the following properties:
     *
     * @return the generated query.
     */
    public function generateQuery($fields)
    {
        $selectable = [];
        if (isset($this->model)) {
            $query = $this->model::query();
        } else {
            $query = $this->builder();

        }

        foreach ($fields as $field) {
            if (isset($field->table)) {
                //field as

                array_push($selectable, $field->table . '.' . $field->name . ' as ' . $this->get_field_name($field));
            }

            //searchable
            if ($this->search) {
                if (isset($field->searchable) and $field->searchable) {
                    if (is_callable($field->searchable)) {
                        call_user_func($field->searchable, $query, $this->search);
                    } else {
                        $query->orWhere(($field->table ? $field->table . '.' : '') . $field->name, 'like', '%' . $this->search . '%');
                    }
                }
            }
            // sortable
            if ($this->sort == $this->get_field_name($field)) {

                if (isset($field->sortable) and $field->sortable) {

                    if (is_callable($field->sortable)) {
                        call_user_func($field->sortable, $query, $this->sortDirections[$this->get_field_name($field)]);
                    } else {

                        $query->orderBy(($field->table ? $field->table . '.' : '') . $field->name, $this->sortDirections[$this->get_field_name($field)]);
                    }
                }
            }
        }
        $this->applyFilters($query);
        $selectable = [...$selectable, ...$this->select];
        $selectable = array_unique($selectable);
        $query->select(...$selectable);

        // dd($query->toSql());
        // dd($query->get()->toArray());
        return $query;
    }

    /**
     * The tabledata function generates a table of data based on the given fields and pagination
     * settings.
     *
     * @param fields An array of objects representing the fields to be displayed in the table. Each
     * object should have the following properties:
     * @param perpage The "perpage" parameter determines the number of records to be displayed per page
     * in the table.
     *
     * @return a table of data. Each row in the table represents a record from the database. The table
     * is an array of associative arrays, where each associative array represents a row in the table.
     * The keys of the associative array are the field names or labels, and the values are the
     * corresponding values for each field in the record.
     */
    public function tabledata($fields, $perpage)
    {
        $records = $this->generateQuery($fields)->paginate($perpage);
        $this->records = $records;
        $table = [];
        foreach ($records as $record) {

            $body = [];
            foreach ($fields as $field) {

                //reset value
                $value = null;
                //getting data accoring to field
                if ($record->{$this->get_field_name($field)}) {
                    $value = $record->{$this->get_field_name($field)};
                }
                //modifying field  data accoring to value
                if ($field->value) {

                    if (is_callable($field->value)) {
                        //formatting value
                        $value = call_user_func($field->value, $record);
                    } else {
                        //getting value as it is
                        $value = $field->value;
                    }
                }
                //if label is present then show label else who field name
                $body[$field->label ? $field->label : $field->name] = $value;

            }
            array_push($table, $body);
            array_push($this->_all_ids, isset($record->{$this->primaryKey}) ? $record->{$this->primaryKey} : null);
        }
        return $table;
    }

    /**
     * The function exports data from a table to a CSV file and triggers a download of the file.
     *
     * @return a response that triggers the download of a CSV file.
     */
    public function exportCsv()
    {
        $fields = $this->get_fields();
        $tablebody = $this->tabledata($fields, $this->perpage);
        $data = [];
        $fieldNamesArr = [];
        foreach ($fields as $field) {
            array_push($fieldNamesArr, $field->label ? $field->label : $this->get_field_name($field));
        }

        $data = [$fieldNamesArr, ...$tablebody];
        // dd($data);
        // Define the CSV file name
        $csvFileName = time() . '.csv';

        // Open the CSV file for writing
        $file = fopen($csvFileName, 'w');

        // Loop through the array and write each row to the CSV file
        foreach ($data as $row) {
            fputcsv($file, $row);
        }

        // Close the CSV file
        fclose($file);

        // Return a response to trigger the download
        return response()->stream(
            function () use ($csvFileName) {
                readfile($csvFileName);
                unlink($csvFileName); // Delete the CSV file after downloading
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
            ]
        );
    }

    public function render()
    {

        //dd($this->filters);
        // dd($this->applyFilters());
        if (count($this->table()) == 0) {
            $className = get_class($this);
            return <<<HTML
                <div>
                $className   datatable either does not have a table() method or is not returning any columns.
                </div>
        HTML;
        }

        $fields = $this->get_fields();
        $data = $this->tabledata($fields, $this->perpage);
        $_all_ids = $this->_all_ids;
        $paginator = $this->records;
        return view('joydeep-bhowmik/livewire-datatable::livewire.datatable', compact('data', 'fields', '_all_ids', 'paginator'));
    }
}
