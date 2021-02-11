export default class Permissions {
    constructor(options) {
        this.csrf = $('[name="_token"]');
        this.bToken = $('[name="b_token"]');
        this.editForm = $("#permission_edit_form");
        this.saveButton = $('#save_permission');
        this.updateButton = $('#update_permission');
        this.deleteButtons = $('*[data-delete]');
        this.baseUrl = this.editForm.attr('action');
        this.currentOperation = '';
        this.editPermissionIdField = $('input[name="permission_id"]');
        this.editNameField = $('input[name="name"]');
        this.errorMessage = $("#permission_error");

        // Modal is added in app.js
        if (options.modal) {
            this.modal = options.modal;
            this.resetModal();
        }

        if (options.dtManager) {
            options.dtManager.run('permissions-table', {
                pageLength: 25,
                lengthMenu: [10, 25, 50, 75, 100],
            });
        }

        if (this.editForm.is('*')) {
            this.addEventListeners();
        }
    }

    openForEdit(row) {
        let permissionId = row.attr('id');
        let permissionName = row.attr('data-name');

        this.editPermissionIdField.val(permissionId);
        this.editNameField.val(permissionName);

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
                self.callAjax('DELETE','delete', 'permission_id=' + deleteId);
            }

        });
    }
}
