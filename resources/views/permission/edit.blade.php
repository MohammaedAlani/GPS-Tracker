@extends('layouts.in')

@section('body')
    <div class="min-h-screen bg-gray-50 p-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-8">

                <div class="mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Edit Permission</h1>
                </div>

                @if(session('success'))
                    <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-100 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('permission.update', $permission)  }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Permission Name</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            required
                            value="{{ old('name', $permission->name) }}"
                            class="w-full h-12 rounded-lg border border-gray-200 bg-white px-4 placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300"
                            placeholder="e.g. Administrator"
                        >
                        @if(!empty($errors) && $errors->has('name'))
                            <p class="mt-2 text-sm text-red-600">{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-2">
                        <a href="{{ route('permission.index') }}"
                           class="inline-block px-4 py-2 rounded-lg border border-gray-200 text-sm text-gray-700">
                            Cancel
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-6 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                            Save Changes
                        </button>
                    </div>
                </form>

                <form method="POST" action="{{ route('permission.destroy', $permission) }}" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Are you sure you want to delete this permission?')"
                            class="inline-flex items-center px-6 py-2 rounded-lg bg-red-600 text-white text-sm font-medium shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-200">
                        Delete Permission
                    </button>
                </form>
            </div>
        </div>
    </div>
@stop
