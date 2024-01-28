<div x-data="{
    columns: false,
    filters: false,
    bulkActions: false,
    selectAll(state) {
        $el.querySelectorAll('table input[type=checkbox]').forEach((el) => {
            if (state) {
                if (!@this.ids.includes(el.value)) {
                    el.checked = state;
                    el.dispatchEvent(new Event('change'));
                }
            } else {
                el.checked = state;
                el.dispatchEvent(new Event('change'));
            }

        });
    }
}">
    @if ($headers)
        <div class="header relative flex flex-wrap gap-3 p-3">
            {{-- bluckactions --}}
            <div class="mr-auto w-full">
                @if (count($this->bulkActions()))

                    <button class= "flex rounded-md border p-2 shadow-sm" type="button"
                        x-on:click="bulkActions=!bulkActions" x-show="$wire.ids.length">
                        &#10247 <span class="hidden md:block">Bulk actions</span>
                    </button>
                    <div class="absolute left-0 right-0 top-full flex w-full gap-4 border bg-white shadow-md"
                        style="display: none" x-show="bulkActions" @click.outside="bulkActions=false" x-transition>
                        <div class="flex gap-4 overflow-x-auto p-3">
                            @foreach ($this->bulkActions() as $button)
                                <button
                                    class="{{ $button->name == 'delete' ? 'bg-red-500 text-white' : 'bg-gray-50' }} max-w-fit rounded-md border px-3 py-2"
                                    type="button" onclick="{!! $button->confirm
                                        ? 'return confirm(' . '\'' . $button->confirm . '\'' . ') || event . stopImmediatePropagation()'
                                        : '' !!}" wire:click="{{ $button->action }}">
                                    {{ $button->text ? $button->text : $button->name }}
                                </button>
                            @endforeach
                        </div>
                        <button class="ml-auto px-3" type="button" x-on:click="bulkActions=!bulkActions">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                @endif
            </div>
            {{-- seachbox and filters and columns --}}
            <div class="flex items-center gap-2 rounded-md border px-2 text-gray-300 shadow-sm focus-within:text-black focus-within:ring-2 focus-within:ring-black"
                tabindex="-1">
                <svg class="h-6 w-6 animate-spin fill-black text-gray-200 dark:text-gray-600" aria-hidden="true"
                    wire:loading viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>
                <svg class="h-6 w-6" wire:loading.remove xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input class="peer !border-none px-0 !outline-none !ring-0" type="text"
                    wire:model.live.debounce.500ms="search" placeholder="Search">
            </div>
            @if (count($this->filters()))
                @php
                    $filtercount = 0;

                    foreach ($filters as $filter) {
                        if (isset($filter) && !empty($filter)) {
                            $filtercount++;
                        }
                    }
                @endphp
                <button class="relative" type="button" x-on:click="filters=!filters">
                    <div class="absolute -right-2 top-0 z-[1] h-fit rounded-lg border bg-gray-50 px-[2px] text-xs">
                        {{ $filtercount }}
                    </div>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                    </svg>
                </button>
                <div class="absolute right-2 top-full flex w-full max-w-[250px] flex-col gap-4 rounded-md border bg-white p-5 shadow-md"
                    style="display: none" x-show="filters" x-transition @click.outside="filters=false">
                    <div class="flex">
                        <h2 class="text-base font-bold">Filters</h2>
                        <button class="ml-auto text-sm font-bold text-blue-400" wire:click="resetFilters">Reset</button>
                    </div>
                    @foreach ($this->filters() as $filter)
                        @php
                            $filter->name = $filter->label ? $filter->label : $filter->name;
                        @endphp
                        <label class="text-sm" for="{{ $filter->_filter_id }}" wire:key="{{ $filter->_filter_id }}">
                            @if ($filter->type == 'select')
                                <div class="relative flex w-full flex-col gap-2">
                                    <div class="font-semibold">
                                        {{ $filter->name }}
                                    </div>
                                    <select
                                        class="focus:shadow-outline block w-full appearance-none rounded border border-gray-300 bg-white py-2 pl-2 pr-8 leading-tight focus:outline-none"
                                        id="" name=""
                                        wire:model.live.debounce.500ms="filters.{{ $filter->_filter_id }}"
                                        wire:loading.attr="disabled">
                                        @foreach ($filter->options as $key => $value)
                                            <option class="mr-5" value="{{ $key }}">{{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="pointer-events-none absolute bottom-[12px] right-0 flex items-center px-2 text-gray-700">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            @elseif(in_array($filter->type, ['checkbox', 'radio']))
                                <div class="flex gap-3">
                                    <input class="rounded-md" id="{{ $filter->_filter_id }}"
                                        type="{{ $filter->type }}" value="{{ $filter->_filter_id }}"
                                        wire:model.live.debounce.500ms="filters.{{ $filter->_filter_id }}"
                                        wire:loading.attr="disabled">
                                    <div class="font-semibold">
                                        {{ $filter->name }}
                                    </div>
                                </div>
                            @else
                                <div class="flex w-full flex-col gap-2">
                                    <div class="font-semibold">
                                        {{ $filter->name }}
                                    </div>
                                    <input
                                        class="focus:shadow-outline block w-full appearance-none rounded border border-gray-300 bg-white py-2 pl-2 pr-8 leading-tight focus:outline-none"
                                        id="{{ $filter->_filter_id }}" type="{{ $filter->type }}"
                                        value="{{ $filter->_filter_id }}"
                                        wire:model.live.debounce.500ms="filters.{{ $filter->_filter_id }}"
                                        wire:loading.attr="disabled"
                                        placeholder="{{ $filter->placeholder ? $filter->placeholder : '' }}">
                                </div>
                            @endif
                        </label>
                    @endforeach
                </div>
            @endif

            @if (count($this->table()))
                <button type="button" x-on:click="columns=!columns">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 4.5v15m6-15v15m-10.875 0h15.75c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125H4.125C3.504 4.5 3 5.004 3 5.625v12.75c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </button>
                <div class="absolute right-2 top-full flex w-full max-w-[250px] flex-col gap-4 rounded-md border bg-white p-5 shadow-md"
                    style="display: none" x-show="columns" x-transition @click.outside="columns=false">
                    <h2 class="font-bold">Columns</h2>
                    @foreach ($this->table() as $field)
                        <label class="flex gap-3" for="{{ $field->_field_id }}" wire:key="{{ $field->_field_id }}">
                            <input id="{{ $field->_field_id }}" type="checkbox" value="{{ $field->_field_id }}"
                                wire:model.live="columns" wire:loading.attr="disabled"
                                {{ count($columns) < 4 && in_array($field->_field_id, $columns) ? 'disabled' : '' }}>
                            {{ $field->label ? $field->label : $this->get_field_name($field) }}
                        </label>
                    @endforeach
                </div>
            @endif
            @if ($exportable)
                <button class="mr-auto" type="button" wire:click="exportCsv">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path
                            d="M13.2 12L16 16H13.6L12 13.7143L10.4 16H8L10.8 12L8 8H10.4L12 10.2857L13.6 8H15V4H5V20H19V8H16L13.2 12ZM3 2.9918C3 2.44405 3.44749 2 3.9985 2H16L20.9997 7L21 20.9925C21 21.5489 20.5551 22 20.0066 22H3.9934C3.44476 22 3 21.5447 3 21.0082V2.9918Z">
                        </path>
                    </svg>
                </button>
            @endif
        </div>
    @endif
    <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-200 border">
            <thead>
                <tr class="divide-x">
                    @if (count($data))
                        @if ($checkbox)
                            <th class="w-fit items-center gap-3 whitespace-nowrap p-3 px-5 text-left">
                                <input type="checkbox" x-on:click="selectAll($event.target.checked)">
                            </th>
                        @endif
                        @foreach ($fields as $field)
                            <th class="items-center gap-3 whitespace-nowrap p-3 px-5 text-left">
                                <div class="flex items-center gap-2">
                                    @php
                                        $field->name = $this->get_field_name($field);
                                    @endphp
                                    {{ $field->label ? $field->label : $field->name }}
                                    @if (isset($field->sortable))
                                        <button class="flex flex-col items-center justify-center" type="button">
                                            <svg class="{{ isset($sortDirections[$field->name]) && $sortDirections[$field->name] === 'desc' ? 'text-black' : 'text-gray-300' }} h-2 w-2"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                wire:click="sortBy('{{ $field->name }}','desc')" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                            </svg>
                                            <svg class="{{ isset($sortDirections[$field->name]) && $sortDirections[$field->name] === 'asc' ? 'text-black' : 'text-gray-300' }} h-2 w-2"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                wire:click="sortBy('{{ $field->name }}','asc')" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>

                                        </button>
                                    @endif
                                </div>
                            </th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y">
                @php
                    $i = 0;
                    // dd($_all_ids);
                @endphp
                @forelse($data as $row)
                    <tr class="even:bg-gray-50">
                        @if ($checkbox)
                            <td class="w-fit items-center gap-3 whitespace-nowrap p-3 px-5 text-left">
                                <input type="checkbox" value="{{ $_all_ids[$i] }}" wire:key="{{ $_all_ids[$i] }}"
                                    wire:model="ids">
                            </td>
                        @endif
                        @foreach ($row as $column)
                            <td class="whitespace-nowrap p-5">{!! $column !!}</td>
                        @endforeach
                        @php
                            $i++;
                        @endphp
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-3 text-center">
                            <div class="flex h-[100px] flex-wrap items-center justify-center gap-4">
                                <div>
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    No data available
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }} --}}
    <div>
        <div class="pagination grid grid-cols-[auto_auto_auto] items-center justify-between px-5 py-2">
            <!-- PRevious Page mobile Link -->
            @if ($paginator->currentPage() > 1)
                <button class="mr-auto flex items-center justify-center gap-2 rounded-md border p-2 md:hidden"
                    wire:click="previousPage" rel="prev" wire:loading.attr="disabled">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg> Previous
                </button>
            @endif
            @if ($paginator->lastPage() > 1)
                <div class="hidden p-2 md:block"> Showing Page {{ $paginator->currentPage() }} of
                    {{ $paginator->lastPage() }}</div>
                <div class="mx-auto flex w-fit items-center justify-center divide-x overflow-hidden rounded-md border">
                    <label class="hidden p-2 sm:block" for="perppage---">Perpage</label>
                    <select class="block p-2" id="perppage---" name=""
                        wire:model.live.debounce.500ms="perpage" wire:loading.attr="disabled">
                        <option value="10">10</option>
                        <option value="25">50</option>
                        <option value="50">50</option>
                        <option value="9999999999">All</option>
                    </select>
                    <span class="w-[4px]"></span>
                </div>
                <ul
                    class="ml-auto hidden max-w-fit break-inside-avoid-column items-center divide-x rounded-md border md:flex">
                    <!-- Previous Page Link -->

                    @if ($paginator->currentPage() > 1)
                        <li class="p-2">
                            <button class="mt-1" wire:click="previousPage" rel="prev"
                                wire:loading.attr="disabled">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 19.5L8.25 12l7.5-7.5" />
                                </svg>
                            </button>
                        </li>
                    @endif
                    <li>
                        <input class="w-14 p-2" type="tel" placeholder="Jump"
                            wire:change="gotoPage( $event.target.value, 'page')" wire:loading.attr="disabled">
                    </li>
                    <!-- Jump to First Page -->
                    @if ($paginator->currentPage() > 4)
                        <li class="p-2 px-3"><button wire:click="gotoPage( 1, 'page')"
                                wire:loading.attr="disabled">1</button></li>
                        @if ($paginator->currentPage() > 5)
                            <li class="p-2 px-3"><span>...</span></li>
                        @endif
                    @endif

                    <!-- Page Numbers -->
                    @for ($i = max(1, $paginator->currentPage() - 3); $i <= min($paginator->lastPage(), $paginator->currentPage() + 3); $i++)
                        <li
                            class="{{ $i == $paginator->currentPage() ? 'active font-bold border-b-2 border-b-black' : '' }} p-2 px-3">
                            <button wire:click="gotoPage( {{ $i }}, 'page')"
                                wire:loading.attr="disabled">{{ $i }}</button>
                        </li>
                    @endfor

                    <!-- Jump to Last Page -->
                    @if ($paginator->currentPage() < $paginator->lastPage() - 3)
                        @if ($paginator->currentPage() < $paginator->lastPage() - 4)
                            <li class="p-2 px-3"><span>...</span></li>
                        @endif
                        <li class="p-2 px-3">
                            <button wire:click="gotoPage( {{ $paginator->lastPage() }}, 'page')"
                                wire:loading.attr="disabled">
                                {{ $paginator->lastPage() }}
                            </button>
                        </li>
                    @endif

                    <!-- Next Page Link -->
                    @if ($paginator->hasMorePages())
                        <li class="p-2">
                            <button class="mt-1" wire:click="nextPage" rel="next"
                                wire:loading.attr="disabled">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </li>
                    @endif

                </ul>
                <!-- Next Page mobile Link -->
                @if ($paginator->hasMorePages())
                    <button class="ml-auto flex items-center justify-center gap-2 rounded-md border p-2 md:hidden"
                        wire:click="nextPage" rel="next">
                        Next <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                @endif
            @endif
        </div>

    </div>
</div>
