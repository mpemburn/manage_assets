export default class Permissions {
    constructor(options) {
        this.csrf = $('[name="_token"]');
        this.bToken = $('[name="b_token"]');
        this.saveButton = $('#save_permission');
        this.updateButton = $('#update_permission');
        this.editForm = $("#permission_edit_form");
        this.editPermissionIdField = $('input[name="permission_id"]');
        this.editNameField = $('input[name="name"]');
        this.errorMessage = $("#permission_error");

        // Modal is added in app.js
        this.modal = options.modal;
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
        let guardName = row.attr('data-guard-name');

        this.editPermissionIdField.val(permissionId);
        this.editNameField.val(permissionName);
        this.checkContextRadioButton(guardName);

        this.saveButton.hide();
        this.updateButton.show();
        this.modal.toggleModal();
    }

    checkContextRadioButton(value) {
        $('input[name="context"][value="' + value + '"]').prop('checked', true);
    }

    addEventListeners() {
        let self = this;
        // Get contents of row for editing
        $('.dataTable').on('click', 'tbody tr', function () {
            self.openForEdit($(this));
        });

        this.saveButton.on('click', function () {
            $.ajax({
                url: self.editForm.attr('action'),
                type: 'POST',
                datatype: 'json',
                data: self.editForm.serialize(),
                headers: {
                    'X-CSRF-TOKEN': self.csrf.val(),
                    'Authorization': 'Bearer ' + self.bToken.val()
                },
                success: function (response) {
                    self.modal.toggleModal();

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
        });
    }
}
