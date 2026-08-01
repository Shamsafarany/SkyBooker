<x-layout title="Add Airport" header="Add Airport">
    <div class="mb-6 mt-2">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.airports.index') }}" 
               class="text-cyan-600 hover:text-cyan-800 transition group">
                <i class="fa-solid fa-arrow-left"></i>
                <span class="text-sm font-medium group-hover:underline">Back to Airports</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden">
        {{-- Header --}}
        <div class="px-8 py-6 bg-gradient from-cyan-50 to-cyan-100/50 border-b border-cyan-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-cyan-600 flex items-center justify-center shadow-lg shadow-cyan-600/30 hover:bg-cyan-500 hover:shadow-cyan-600/40 transition-all duration-200">
                    <i class="fa-solid fa-plus text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Add New Airport</h2>
                    <p class="text-sm text-gray-500">Enter the details of the new airport</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.airports.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            {{-- Two Column Layout --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Airport Name --}}
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Airport Name <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-building text-cyan-500 text-sm"></i>
                        </div>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name') }}"
                               placeholder="e.g. John F. Kennedy International Airport"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border-2 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-200 transition-all duration-200 @error('name') border-rose-500 ring-4 ring-rose-200 @enderror"
                               required>
                    </div>
                    @error('name')
                        <p class="mt-1.5 text-sm text-rose-600"><i class="fa-regular fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- IATA Code --}}
                <div>
                    <label for="code" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        IATA Code <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-tag text-cyan-500 text-sm"></i>
                        </div>
                        <input type="text" name="code" id="code" 
                               value="{{ old('code') }}"
                               placeholder="e.g. JFK"
                               maxlength="3"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border-2 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-200 transition-all duration-200 uppercase @error('code') border-rose-500 ring-4 ring-rose-200 @enderror"
                               required>
                    </div>
                    <p class="mt-1 text-xs text-gray-400">3-letter IATA airport code</p>
                    @error('code')
                        <p class="mt-1.5 text-sm text-rose-600"><i class="fa-regular fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- City --}}
                <div>
                    <label for="city" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        City <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-city text-cyan-500 text-sm"></i>
                        </div>
                        <input type="text" name="city" id="city" 
                               value="{{ old('city') }}"
                               placeholder="e.g. New York"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border-2 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-200 transition-all duration-200 @error('city') border-rose-500 ring-4 ring-rose-200 @enderror"
                               required>
                    </div>
                    @error('city')
                        <p class="mt-1.5 text-sm text-rose-600"><i class="fa-regular fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Country --}}
                <div>
                    <label for="country" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Country <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-globe text-cyan-500 text-sm"></i>
                        </div>
                        <input type="text" name="country" id="country" 
                               value="{{ old('country') }}"
                               placeholder="e.g. United States"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-200 transition-all duration-200 @error('country') border-rose-500 ring-4 ring-rose-200 @enderror"
                               required>
                    </div>
                    @error('country')
                        <p class="mt-1.5 text-sm text-rose-600"><i class="fa-regular fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Terminals --}}
                <div>
                    <label for="terminals" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Terminals <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-regular fa-door-open text-cyan-500 text-sm"></i>
                        </div>
                        <input type="number" name="terminals" id="terminals" 
                               value="{{ old('terminals', 1) }}"
                               min="1"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-200 transition-all duration-200 @error('terminals') border-rose-500 ring-4 ring-rose-200 @enderror"
                               required>
                    </div>
                    @error('terminals')
                        <p class="mt-1.5 text-sm text-rose-600"><i class="fa-regular fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Status <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-regular fa-circle-check text-cyan-500 text-sm"></i>
                        </div>
                        <select name="status" id="status" 
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-200 transition-all duration-200 appearance-none @error('status') border-rose-500 ring-4 ring-rose-200 @enderror">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>🟢 Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>🔴 Inactive</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>🟡 Maintenance</option>
                            <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>⚫ Closed</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-chevron-down text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    @error('status')
                        <p class="mt-1.5 text-sm text-rose-600"><i class="fa-regular fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-gray-200 pt-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <span>All fields marked with <span class="text-rose-500">*</span> are required</span>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.airports.index') }}" 
                           class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-8 py-2.5 text-sm font-semibold text-white bg-cyan-800 hover:bg-cyan-600 rounded-xl transition-all duration-200 shadow-lg shadow-cyan-600/30 hover:shadow-xl hover:shadow-cyan-600/40 flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i>
                            Create Airport
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layout>