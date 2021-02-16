export default class UserRolesManager {
    constructor(options) {
        this.csrf = $('[name="_token"]');
        this.bToken = $('[name="b_token"]');
        this.editForm = $('#user_role_edit_form');
        this.editButtons = $('*[data-edit]');
        this.editUserName = $('#user_name');
        this.editUserId = $('input[name="user_id"]');
        this.selectedRoleName = null;
        this.selectedPermissionName = null;
        this.roleRows = null;
        this.editorRoleCheckboxes = $('[data-type="role"]');
        this.editorPermissionCheckboxes = $('[data-type="permission"]');
        this.saveButton = $('#save_user_roles');
        this.apiAction = $('#modal_form').attr('action');
        this.errorMessage = $('#user_roles_error');

        if (options.modal) {
            this.modal = options.modal;
            this.resetModal();
        }

        if (options.dtManager) {
            options.dtManager.run('user_roles_table', {
                pageLength: 25,
                lengthMenu: [10, 25, 50, 75, 100],
            });
        }

        if (this.editForm.is('*')) {
            this.addEventListeners();
        }
    }

    resetModal() {
        // Uncheck all "Roles" checkboxes
        this.editorRoleCheckboxes.each(function () {
            $(this).prop('checked', false);
            $(this).prop('disabled', '');
        });
        // Uncheck all "Permissions" checkboxes
        this.editorPermissionCheckboxes.each(function () {
            $(this).prop('checked', false);
        });
    }

    readRowEntities(row, entityType) {
        let self = this;
        let collection = row.find('li[data-type="' + entityType + '"]');

        this.entityType = entityType;
        collection.each(function () {
            let entityName = $(this).data('entityName');
            self.selectDialogCheckboxes(self.entityType, entityName);
        });
    }

    selectDialogCheckboxes(entityType, entityName) {
        let self = this;
        // Reference the correct checkboxes for roles or permissions
        this.editorCheckboxes = $('input[data-type="' + entityType + '"]');

        if (typeof (entityName) !== "undefined") {
            this.entityName = entityName;
            this.entityType = entityType;
            this.editorCheckboxes.each(function () {
                if ($(this).val() === self.entityName) {
                    $(this).prop('checked', true);
                }
                if (self.entityType === 'role' && $(this).val() === 'Administrator') {
                    // If the current user is an Administrator, make so this can't be changed
                    self.disableAdminChangeForCurrentUser($(this));
                }
            });
        }
    }

    disableAdminChangeForCurrentUser(checkbox) {
        let currentUserId = $('input[name="current_user_id"]').val();
        let disabled = (currentUserId === this.editUserId.val()) ? 'disabled' : '';

        checkbox.prop('disabled', disabled);
    }

    addEventListeners() {
        let self = this;

        this.editButtons.on('click', function (evt) {
            let name = $(this).data('name');
            let userId = $(this).data('edit');
            let row = $(this).parent().parent();

            //Reset all elements in edit dialog
            self.resetModal();

            self.editUserName.html(name);
            self.editUserId.val(userId);
            // Find the lists of roles and permission in the row
            // and use this to check the appropriate boxes in the dialog
            self.readRowEntities(row, 'role')
            self.readRowEntities(row, 'permission')

            self.modal.toggleModal();
        });

        this.saveButton.on('click', function () {
            let dataValue = self.editForm.serialize();

            $.ajax({
                url: self.apiAction,
                type: 'POST',
                datatype: 'json',
                data: dataValue,
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
        })
    }
}
