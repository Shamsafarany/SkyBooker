<x-layout title="Edit Airport {{ $airport->code }}" header="Edit Airport">
    <div class="mb-6">
        <a href="{{ route('admin.airports.index') }}" 
           class="text-cyan-600 hover:text-cyan-800 transition group flex items-center gap-2 mt-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span class="text-sm font-medium group-hover:underline">Back to Airports</span>
        </a>
    </div>

    <x-form.form-create
        title="Edit Airport: {{ $airport->name  }}"
        subtitle="Edit airport info."
        action="{{ route('admin.airports.update', $airport) }}"
        method="PUT"
        submitLabel="Update"
        cancelRoute="{{ route('admin.airports.index') }}"
        
    >
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Airport Name --}}
            <div class="md:col-span-2">
                <x-form.input name="name" label="Airport Name" placeholder="e.g  JFK International Airport" icon="fa-building" required value="{{ $airport->name}}" />
            </div>

            {{-- IATA Code --}}
            <x-form.input name="code" label="IATA Code" placeholder="e.g  JFK" icon="fa-tag" helper="3 letters" required value="{{ $airport->code }}" />

            {{-- City --}}
            <x-form.input name="city" label="City" placeholder="e.g  New York" icon="fa-city" required value="{{ $airport->city }}"/>

            {{-- Country --}}
            <x-form.input name="country" label="Country" placeholder="e.g. USA" icon="fa-globe" required value="{{ $airport->country }}" />

            {{-- Terminals --}}
            <x-form.input name="terminals" label="Terminals" type="number" value="1" icon="fa-door-open" required value="{{ $airport->terminals }}" />
            {{-- Status --}}
            <x-form.select name="status" label="Status" icon="fa-circle-check" selected="{{ $airport->status }}"
                :options="['active' => '🟢 Active', 'inactive' => '🔴 Inactive', 'maintenance' => '🟠 Maintenance']" />
            </div>
        </div>
    </x-form.form-create>
</x-layout>