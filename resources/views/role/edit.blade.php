@extends('layouts.in')

@section('body')
    <div class="min-h-screen bg-gray-50 p-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-8">

                <div class="mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Edit Role</h1>
                </div>

                @if(session('success'))
                    <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-100 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Role Edit Form -->
                <form method="POST" action="{{ route('role.update', $role) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Role Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Role Name</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            required
                            value="{{ old('name', $role->name) }}"
                            class="w-full h-12 rounded-lg border border-gray-200 bg-white px-4 placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300"
                            placeholder="e.g. Administrator"
                        >
                        @if(!empty($errors) && $errors->has('name'))
                            <p class="mt-2 text-sm text-red-600">{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <!-- Permissions -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($permissions as $permission)
                                <label class="inline-flex items-center space-x-2">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}>
                                    <span class="text-gray-700">{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @if(!empty($errors) && $errors->has('permissions'))
                            <p class="mt-2 text-sm text-red-600">{{ $errors->first('permissions') }}</p>
                        @endif
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-2">
                        <a href="{{ route('role.index') }}"
                           class="inline-block px-4 py-2 rounded-lg border border-gray-200 text-sm text-gray-700">
                            Cancel
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-6 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                            Save Changes
                        </button>
                    </div>
                </form>

                <!-- Delete Role -->
                <form method="POST" action="{{ route('role.destroy', $role) }}" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Are you sure you want to delete this role?')"
                            class="inline-flex items-center px-6 py-2 rounded-lg bg-red-600 text-white text-sm font-medium shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-200">
                        Delete Role
                    </button>
                </form>
            </div>
        </div>
    </div>
@stop
