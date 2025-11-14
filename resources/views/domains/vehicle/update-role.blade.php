@extends ('domains.vehicle.update-layout')

@section ('content')

    <input type="search"
           class="form-control form-control-lg mt-5"
           placeholder="filter"
           data-table-search="#vehicle-update-role-list-table" />

    <div class="overflow-auto scroll-visible header-sticky">
        <form method="post">
            <table id="vehicle-update-role-list-table"
                   class="table table-report sm:mt-2 font-medium font-semibold text-center whitespace-nowrap"
                   data-table-sort
                   data-table-pagination
                   data-table-pagination-limit="10">

                <thead>
                <tr>
                    <th class="text-left">name</th>
                    <th class="w-1"><input type="checkbox" data-checkall="#vehicle-update-role-list-table > tbody" /></th>
                </tr>
                </thead>

                <tbody>
                @foreach ($roles as $each)

                    @php ($link = route('role.update', $each->id))

                    <tr>
                        <td class="text-left">
                            <a href="{{ $link }}" class="block">{{ $each->name }}</a>
                        </td>

                        <td class="w-1">
                            <input type="checkbox"
                                   name="related[]"
                                   value="{{ $each->id }}"
                                {{ $row->roles->contains($each->id) ? 'checked' : '' }} />
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>

            <div class="box p-5 mt-5">
                <div class="text-right">
                    <button type="submit"
                            name="_action"
                            value="updateRole"
                            class="btn btn-primary">
                        Relate
                    </button>
                </div>
            </div>
        </form>
    </div>

@stop
