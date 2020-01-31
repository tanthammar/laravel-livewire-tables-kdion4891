<div>
    <div class="row justify-content-between">
        <div class="col-auto order-last order-md-first">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                </div>
                <input type="search" class="form-control" placeholder="{{ __('Search') }}" wire:model="search">
            </div>
        </div>
        @if($header_view)
            <div class="col-md-auto mb-3">
                @include($header_view)
            </div>
        @endif
    </div>

    <div class="card overflow-auto mb-3">
        @if($rows->isEmpty())
            <div class="card-body">
                {{ __('No results to display.') }}
            </div>
        @else
            <div class="card-body p-0">
                <table class="table {{ $table_class }} mb-0">
                    <thead class="{{ $thead_class }}">
                    <tr>
                        @if($checkbox)
                            <th class="align-middle text-nowrap border-top-0">
                                <input type="checkbox" wire:model="checkbox_all">
                            </th>
                        @endif

                        @foreach($columns as $column)
                            <th class="align-middle text-nowrap border-top-0">
                                @if($column->sortable)
                                    <span style="cursor: pointer;" wire:click="sort('{{ $column->attribute }}')">
                                        {{ $column->heading }}

                                        @if($sort_attribute == $column->attribute)
                                            <i class="fa fa-sort-amount-{{ $sort_direction == 'asc' ? 'up-alt' : 'down' }}"></i>
                                        @else
                                            <i class="fa fa-sort-amount-up-alt" style="opacity: .35;"></i>
                                        @endif
                                    </span>
                                @else
                                    {{ $column->heading }}
                                @endif
                            </th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $row)
                        <tr class="{{ TableComponent::trClass($row) }}">
                            @if($checkbox)
                                <td class="align-middle">
                                    <input type="checkbox" wire:model="checkbox_values" value="{{ $row->{$checkbox_attribute} }}">
                                </td>
                            @endif

                            @foreach($columns as $column)
                                <td class="align-middle {{ TableComponent::tdClass($row, $column) }}">
                                    @if($column->view)
                                        @include($column->view)
                                    @else
                                        {{ TableComponent::value($row, $column) }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="row justify-content-between">
        <div class="col-auto">
            {{ $rows->links() }}
        </div>
        @if($footer_view)
            <div class="col-md-auto">
                @include($footer_view)
            </div>
        @endif
    </div>
</div>
