<x-layout title="Add Airplane" header="Add Airplane">
    <div class="mb-6">
        <a href="{{ route('admin.airplanes.index') }}" 
           class="text-cyan-600 hover:text-cyan-800 transition group flex items-center gap-2 mt-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span class="text-sm font-medium group-hover:underline">Back to Airplanes</span>
        </a>
    </div>

    <x-form.form-create
        title="Add New Airplane"
        subtitle="Enter the details of the new airplane"
        action="{{ route('admin.airplanes.store') }}"
        submitLabel="Create Airplane"
        cancelRoute="{{ route('admin.airplanes.index') }}"
        icon="fa-plus"
    >
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Model --}}
            <x-form.input 
                name="model"
                label="Model"
                placeholder="e.g. Boeing 737-800"
                icon="fa-plane"
                required
            />

            {{-- Manufacturer --}}
            <x-form.input 
                name="manufacturer"
                label="Manufacturer"
                placeholder="e.g. Boeing"
                icon="fa-building"
                required
            />

            {{-- Registration --}}
            <x-form.input 
                name="registration"
                label="Registration"
                placeholder="e.g. N737AA"
                icon="fa-tag"
                required
                helper="Unique tail number"
            />

            {{-- Capacity --}}
            <x-form.input 
                name="capacity"
                label="Capacity"
                type="number"
                placeholder="e.g. 189"
                icon="fa-users"
                required
                min="1"
            />

            {{-- Year --}}
            <x-form.input 
                name="year"
                label="Year"
                type="number"
                placeholder="e.g. 2018"
                icon="fa-calendar"
                required
                min="1950"
                max="{{ date('Y') }}"
            />

            {{-- Status --}}
            <x-form.select 
                name="status"
                label="Status"
                icon="fa-circle-check"
                :options="[
                    'active' => '🟢 Active',
                    'inactive' => '🔴 Inactive',
                    'maintenance' => '🟠 Maintenance',
                    'retired' => '⚫ Retired',
                ]"
                selected="active"
                required
            />
        </div>
    </x-form.form-create>
</x-layout>