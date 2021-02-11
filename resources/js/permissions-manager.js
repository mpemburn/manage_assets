export default class EntitysManager {
    /* NOTE: the context variable and variabls referring to 'entity'
        can be either 'role' or 'permission' as set in the acl_wrapper div
     */
    constructor(options) {
        let wrapper = $('#acl_wrapper');
        let context = '';
        if (wrapper.is('*')) {
            context = wrapper.attr('data-context');
        } else {
            return;
        }
        this.csrf = $('[name="_token"]');
        this.bToken = $('[name="b_token"]');
        this.editForm = $('#' + context + '_edit_form');
        this.saveButton = $('#save_' + context + '');
        this.updateButton = $('#update_' + context + '');
        this.deleteButtons = $('*[data-delete]');
        this.baseUrl = this.editForm.attr('action');
        this.currentOperation = '';
        this.editEntityIdField = $('input[name="' + context + '_id"]');
        this.editNameField = $('input[name="name"]');
        this.errorMessage = $('#' + context + '_error');

        // Modal is added in app.js
        if (options.modal) {
            this.modal = options.modal;
            this.resetModal();
        }

        if (options.dtManager) {
            options.dtManager.run(context + '-table', {
                pageLength: 25,
                lengthMenu: [10, 25, 50, 75, 100],
            });
        }

        if (this.editForm.is('*')) {
            this.addEventListeners();
        }
    }

    openForEdit(row) {
        let entityId = row.attr('id');
        let entityName = row.attr('data-name');

        this.editEntityIdField.val(entityId);
        this.editNameField.val(entityName);

        this.saveButton.hide();
        this.updateButton.show();
        this.modal.toggleModal();
    }

    callAjax(method, endpoint, data) {
        let self = this;
        let dataValue = data || this.editForm.serialize();
        this.currentOperation = endpoint;
        $.ajax({
            url: this.baseUrl + endpoint,
            type: method,
            datatype: 'json',
            data: dataValue,
            headers: {
                'X-CSRF-TOKEN': this.csrf.val(),
                'Authorization': 'Bearer ' + this.bToken.val()
            },
            success: function (response) {
                if (self.currentOperation !== 'delete') {
                    self.modal.toggleModal();
                }

                document.location.reload();
            },
            error: function (data) {
                self.errorMessage.html(data.responseJSON.error)
                    .removeClass('opacity-0')
                    .fadeOut(5000, function () {
                        $(this).addClass('opacity-0').show();
                    });
            }
        });
    }

    resetModal() {
        this.editNameField.val('');
        this.saveButton.show();
        this.updateButton.hide();
    }

    addEventListeners() {
        let self = this;
        // Get contents of row for editing
        $('.dataTable').on('click', 'tbody tr', function () {
            self.openForEdit($(this));
        });

        $(document).on('modalClosed', function (evt) {
            self.resetModal();
        });

        this.saveButton.on('click', function () {
            self.callAjax('POST','create');
        });

        this.updateButton.on('click', function () {
            self.callAjax('PUT','update');
        });

        this.deleteButtons.on('click', function (evt) {
            evt.stopPropagation(); // Prevent opening the modal

            let deleteId = $(this).attr('data-delete');
            let name = $(this).attr('data-name');
            if (confirm('Are you sure you want to delete "' + name + '"?')) {
                self.callAjax('DELETE','delete', 'entity_id=' + deleteId);
            }

        });
    }
}
