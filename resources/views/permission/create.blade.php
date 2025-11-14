@extends('layouts.in')

@section('body')
    <div class="min-h-screen bg-gray-50 p-8">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-8">
                <div class="mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Create Role</h1>
                </div>

                @if(session('success'))
                    <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-100 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('permission.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>

                        <input
                            id="name"
                            name="name"
                            type="text"
                            required
                            value="{{ old('name') }}"
                            class="w-full h-12 rounded-lg border border-gray-200 bg-white px-4 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300"
                            placeholder="e.g. Add Cars"
                        >

                        @if(!empty($errors) && $errors->has('name'))
                            <p class="mt-2 text-sm text-red-600">
                                {{ $errors->first('name') }}
                            </p>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 opacity-0 pointer-events-none" aria-hidden="true">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                            <select class="w-full h-12 rounded-lg border border-gray-200 bg-white px-4">
                                <option>English</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Time Zone</label>
                            <select class="w-full h-12 rounded-lg border border-gray-200 bg-white px-4">
                                <option>UTC</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-2">
                        <a href="{{ url()->previous() }}" class="inline-block px-4 py-2 rounded-lg border border-gray-200 text-sm text-gray-700">
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center px-6 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
