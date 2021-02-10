<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Permissions') }}
        </h2>
    </x-slot>
    <div class="report">
        <div class="controls">
            <button id="edit_permission" class="modal-open rounded-sm px-3 py-1 bg-blue-700 hover:bg-blue-500 text-white focus:shadow-outline focus:outline-none">
                Add Permission
            </button>
        </div>
        <table>
            <thead>
                <th>
                    Permission Name
                </th>
                <th>
                    Context
                </th>
            </thead>
            @foreach ($permissions as $permission)
                <tr>
                    <td>
                        {!! $permission->name !!}
                    </td>
                    <td class="uppercase">
                        {!! $permission->guard_name !!}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="permission-modal">
        <!--Modal-->
        <div class="modal opacity-0 pointer-events-none w-full h-full top-0 left-0 flex items-center justify-center">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
            @include('components.token-form')
            @include('permissions.edit')
        </div>
    </div>

</x-app-layout>
