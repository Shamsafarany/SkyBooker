{{-- resources/views/admin/profile/edit.blade.php --}}

<x-layout title="Edit Profile" header="Edit Profile">
    <div class="max-w-4xl mx-auto mt-10">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            {{-- Header --}}
            <div class="px-6 py-5 bg-gradient from-cyan-50 to-cyan-100/50 border-b border-cyan-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fa-regular fa-pen-to-square text-cyan-700 text-xl"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Edit Profile</h3>
                        <p class="text-sm text-gray-500">Update your account information</p>
                    </div>
                </div>
                <a href="{{ route('admin.profiles.index') }}" 
                    class="text-sm text-gray-500 hover:text-cyan-600 transition flex items-center gap-1">
                    <i class="fa fa-arrow-left"></i>
                    Back to Profile
                </a>
            </div>

            {{-- Success Messages --}}
            @if(session('success'))
                <div class="m-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-600 text-sm flex items-center gap-2">
                    <i class="fa-regular fa-circle-check text-lg"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error Messages --}}
            @if(session('error'))
                <div class="m-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm flex items-center gap-2">
                    <i class="fa-regular fa-circle-exclamation text-lg"></i>
                    {{ session('error') }}
                </div>
            @endif

            <div class="p-6">
                {{-- Update Profile --}}
                <div>
                    <h3 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fa-regular fa-user text-cyan-600"></i>
                        Profile Information
                    </h3>

                    <form action="{{ route('admin.profiles.update', $user) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Username --}}
                            <div class="md:col-span-1">
                                <x-form.input 
                                    name="username"
                                    label="Username"
                                    icon="fa-regular fa-at"
                                    value="{{ old('username', $user->username) }}"
                                    helper="This is your unique identifier"
                                    required
                                    readonly
                                />
                            </div>
                            
                            {{-- First Name --}}
                            <x-form.input 
                                name="first_name"
                                label="First Name"
                                icon="fa-regular fa-user"
                                value="{{ old('first_name', $user->first_name) }}"
                                required
                            />

                            {{-- Last Name --}}
                            <x-form.input 
                                name="last_name"
                                label="Last Name"
                                icon="fa-regular fa-user"
                                value="{{ old('last_name', $user->last_name) }}"
                                required
                            />

                            {{-- Email --}}
                            <x-form.input 
                                name="email"
                                label="Email Address"
                                type="email"
                                icon="fa-regular fa-envelope"
                                value="{{ old('email', $user->email) }}"
                                required
                            />

                            {{-- Phone --}}
                            <x-form.input 
                                name="phone"
                                label="Phone Number"
                                icon="fa-regular fa-phone"
                                value="{{ old('phone', $user->phone) }}"
                                placeholder="+1-555-123-4567"
                            />
                        </div>

                        {{-- Form Actions --}}
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.profiles.index') }}" 
                                class="px-6 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition text-sm font-medium">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-cyan-600 text-white rounded-xl hover:bg-cyan-700 transition text-sm font-medium flex items-center gap-2">
                                <i class="fa-regular fa-save"></i>
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>

                {{--CHANGE PASSWORD FORM--}}
                <div id="password" class="mt-8 pt-6 border-t-2 border-gray-200">
                    <h3 class="text-md font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fa fa-key text-rose-600"></i>
                        Change Password
                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Optional</span>
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">Update your password to keep your account secure</p>

                    {{-- Password Form Errors --}}
                    @if($errors->hasBag('password'))
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">
                            <div class="flex items-start gap-2">
                                <i class="fa-regular fa-circle-exclamation mt-0.5"></i>
                                <div>
                                    <p class="font-medium">Please fix the following password errors:</p>
                                    <ul class="list-disc list-inside mt-1 space-y-0.5">
                                        @foreach($errors->getBag('password')->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('admin.profiles.password') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Current Password --}}
                            <div class="md:col-span-2">
                                <x-form.input 
                                    name="current_password"
                                    label="Current Password"
                                    type="password"
                                    icon="fa-regular fa-lock"
                                    placeholder="Enter your current password"
                                    required
                                />
                            </div>

                            {{-- New Password --}}
                            <x-form.input 
                                name="password"
                                label="New Password"
                                type="password"
                                icon="fa-regular fa-lock"
                                placeholder="••••••••"
                                helper="Minimum 8 characters"
                                required
                            />

                            {{-- Confirm Password --}}
                            <x-form.input 
                                name="password_confirmation"
                                label="Confirm Password"
                                type="password"
                                icon="fa-regular fa-lock"
                                placeholder="••••••••"
                                required
                            />
                        </div>

                        {{-- Form Actions --}}
                        <div class="flex justify-end gap-3 pt-2">
                            <button type="submit" 
                                    class="px-6 py-2 bg-rose-600 text-white rounded-xl hover:bg-rose-700 transition text-sm font-medium flex items-center gap-2">
                                <i class="fa fa-key"></i>
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

                {{--DELETE ACCOUNT SECTION (DANGER ZONE)--}}
                <div class="mt-8 pt-6 border-t-2 border-red-200">
                    <h3 class="text-md font-semibold text-red-700 mb-2 flex items-center gap-2">
                        <i class="fa fa-triangle-exclamation text-red-600"></i>
                        Danger Zone
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">Actions that cannot be undone</p>

                    <div class="bg-red-50 rounded-xl p-4 border border-red-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-red-800">Delete Account</p>
                                <p class="text-xs text-red-600">Permanently delete your account and all associated data</p>
                            </div>
                            <a href="{{ route('admin.profiles.delete.confirm') }}" 
                                class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition text-sm">
                                Delete Account
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layout>