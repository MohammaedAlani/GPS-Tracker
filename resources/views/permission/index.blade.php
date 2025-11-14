@extends ('layouts.in')

@section ('body')

    <form method="get">
        <div class="sm:flex sm:space-x-4">
            <div class="flex-grow mt-2 sm:mt-0">
                <input type="search" class="form-control form-control-lg" placeholder="filter" data-table-search="#permission-list-table" />
            </div>

            <div class="sm:ml-4 mt-2 sm:mt-0 bg-white">
                <a href="{{ route('permission.create') }}" class="btn form-control-lg whitespace-nowrap">Create</a>
            </div>
        </div>
    </form>

    <div class="overflow-auto scroll-visible header-sticky">
        <table id="permission-list-table" class="table table-report sm:mt-2 font-medium font-semibold text-center whitespace-nowrap" data-table-pagination="permission-list-table-pagination" data-table-sort>
            <thead>
            <tr>
                <th class="text-left">name</th>
                <th>created_at</th>
                <th>updated_at</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($permissions as $row)

                @php ($link = route('permission.edit', $row->id))

                <tr>
                    <td class="text-left"><a href="{{ $link }}" class="block">{{ $row->name }}</a></td>
                    <td data-table-sort-value="{{ $row->created_at }}">{{$row->created_at}}</td>
                    <td data-table-sort-value="{{ $row->updated_at }}">{{ $row->updated_at }}</td>
                    <td class="w-1">
                        <a href="{{ route('permission.edit', $row->id) }}">@icon('key', 'w-4 h-4')</a>
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>

        <ul id="permission-list-table-pagination" class="pagination justify-end"></ul>
    </div>

@stop
