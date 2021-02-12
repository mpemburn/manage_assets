<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Roles') }}
        </h2>
    </x-slot>
    <div id="acl_wrapper" data-context="user" class="report">
        <table id="user-table" class="stripe">
            <thead>
            <th>
                User
            </th>
            <th>
                Roles
            </th>
            </thead>
            @foreach ($users as $user)
                <tr class="cursor-pointer" id="{!! $user->id !!}" data-name="{!! $user->name !!}">
                    <td>
                        {!! $user->name !!}
                    </td>
                    <td>
                        {{  $user->roles()->pluck('name')->implode(' ') }}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="user-modal">
        <!--Modal-->
        <div class="modal opacity-0 pointer-events-none w-full h-full top-0 left-0 flex items-center justify-center">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
            @include('components.token-form')
            @include('user-roles.edit')
        </div>
    </div>

</x-app-layout>
