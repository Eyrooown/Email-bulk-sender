<div class="flex flex-col min-h-0 flex-1">
    <div class="w-full bg-white overflow-hidden shadow-sm sm:rounded-lg flex-1 min-h-0 flex flex-col p-4 space-y-4">
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-lg font-semibold">Manage Accounts</h2>
            <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'add-account' }))"
                    class="btn btn-sm clr-bg-accent text-white rounded-lg px-4 py-2">
                + Add Account
            </button>
        </div>

        @if (session('status'))
            <div class="alert alert-success text-sm mb-2">{{ session('status') }}</div>
        @endif

        <div class="border border-base-300 rounded-xl overflow-hidden flex-1 min-h-0">
            <table class="table w-full">
                <thead class="bg-base-200">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->is_admin)
                                    <span class="badge badge-sm">Admin</span>
                                @else
                                    <span class="badge badge-sm">User</span>
                                @endif
                            </td>
                            <td class="text-xs text-gray-500">
                                {{ $user->created_at?->timezone('Asia/Manila')->format('M d, Y h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-400 italic py-4">No accounts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-2">
                {{ $users->links('livewire::tailwind') }}
            </div>
        </div>
    </div>

    {{-- Add Account Modal --}}
    <x-modal name="add-account" maxWidth="md">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Add Account</h3>
                <button type="button"
                        onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'add-account' }))"
                        class="btn btn-ghost btn-sm">
                    ✕
                </button>
            </div>

            <form wire:submit.prevent="createUser" class="space-y-4">
                <div class="grid gap-3 md:grid-cols-2">
                    <label class="form-control">
                        <span class="label-text text-xs text-gray-500">Name</span>
                        <input type="text" wire:model.defer="name" class="input input-sm input-bordered w-full" />
                        @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </label>
                    <label class="form-control">
                        <span class="label-text text-xs text-gray-500">Email</span>
                        <input type="email" wire:model.defer="email" class="input input-sm input-bordered w-full" />
                        @error('email') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </label>
                </div>
                <div class="grid gap-3 md:grid-cols-2">
                    <label class="form-control">
                        <span class="label-text text-xs text-gray-500">Password</span>
                        <input type="password" wire:model.defer="password" class="input input-sm input-bordered w-full" />
                        @error('password') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </label>
                    <label class="form-control">
                        <span class="label-text text-xs text-gray-500">Confirm Password</span>
                        <input type="password" wire:model.defer="password_confirmation" class="input input-sm input-bordered w-full" />
                        @error('password_confirmation') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </label>
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" wire:model.defer="is_admin" class="checkbox checkbox-sm" />
                        <span>Make this user an admin</span>
                    </label>
                    <div class="flex gap-2">
                        <button type="button"
                                onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'add-account' }))"
                                class="btn btn-sm btn-ghost">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-sm clr-bg-accent text-white rounded-lg px-4 py-2">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>
</div>

