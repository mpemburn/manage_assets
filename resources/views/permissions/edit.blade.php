<div class="" style="width: 90%;">
    <main class="container mx-auto max-w-screen-lg h-full">
        <!-- permission edit modal -->
        <article id="editor" aria-label="Permission Edit Modal" class="relative h-full flex flex-col bg-white shadow-xl rounded-md" >

            <!-- scroll area -->
            <section class="h-full overflow-auto p-8 w-full h-full flex flex-col">
                <form id="permission_edit_form" action="{!! $ajaxUrl !!}">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <div class="form-group font-bold">
                            {{ Form::label('name', 'Permission Name:') }}
                            {{ Form::text('name', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group pt-2">
                            <span class="font-bold">Context: </span>
                            {{ Form::radio('context', 'web', ['class' => 'form-control']) }}
                            {{ Form::label('context', 'WEB') }}
                            {{ Form::radio('context', 'api', ['class' => 'form-control']) }}
                            {{ Form::label('context', 'API') }}
                            {{ Form::radio('context', 'both', ['class' => 'form-control']) }}
                            {{ Form::label('context', 'Both') }}
                        </div>
                    </div>
                </form>
            </section>

            <!-- sticky footer -->
            <footer class="flex justify-end px-8 pb-8 pt-4">
                <div id="permission_error" class="px-3 text-red-600 opacity-0">Error message</div>
                <button id="save_permission" class="rounded-sm px-3 py-1 bg-blue-700 hover:bg-blue-500 text-white focus:shadow-outline focus:outline-none">
                    Save
                </button>
                <button id="cancel" class="modal-close ml-3 rounded-sm px-3 py-1 hover:bg-gray-300 focus:shadow-outline focus:outline-none">
                    Cancel
                </button>
            </footer>
        </article>
    </main>
</div>
chmodT0Write
