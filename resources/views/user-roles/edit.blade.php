<div class="" style="width: 90%;">
    <main class="container mx-auto max-w-screen-lg h-96">
        <!-- permission edit modal -->
        <article id="editor" aria-label="User Role Edit Modal" class="relative h-full flex flex-col bg-white shadow-xl rounded-md" >

            <!-- scroll area -->
            <section class="h-full overflow-auto p-8 w-full h-full flex flex-col">
                <form id="user_role_edit_form" action="{!! $action !!}">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <div id="user_name" class="text-lg font-bold"></div>
                        <div class="form-group font-bold overflow-scroll">
                            <ul>
                                @foreach($roles as $role)
                                <li><input data-type="role" type="checkbox" name="{!! $role->name !!}"> {!! $role->name !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </form>
            </section>

            <!-- sticky footer -->
            <footer class="flex justify-end px-8 pb-8 pt-4">
                <div id="permission_error" class="px-3 text-red-600 opacity-0">Error message</div>
                <button id="save_permission" class="rounded px-3 py-1 bg-blue-700 hover:bg-blue-500 text-white focus:shadow-outline focus:outline-none">
                    Save
                </button>
                <button id="cancel" class="modal-close ml-3 rounded px-3 py-1 bg-gray-300 hover:bg-gray-200 focus:shadow-outline focus:outline-none">
                    Cancel
                </button>
            </footer>
        </article>
    </main>
</div>
