export default class UserRolesManager {
    constructor(options) {
        this.csrf = $('[name="_token"]');
        this.bToken = $('[name="b_token"]');
        this.editForm = $('#user_role_edit_form');
        this.editButtons = $('*[data-edit]');
        this.editUserName = $('#user_name');

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
        // this.editNameField.val('');
        // this.saveButton.show();
        // this.updateButton.hide();ß
    }

    addEventListeners() {
        let self = this;

        this.editButtons.on('click', function (evt) {
            let userId = $(this).attr('data-edit');
            let name = $(this).attr('data-name');
            let userHasRoles = $('*[data-userid]');

            self.editUserName.text(name);

            self.modal.toggleModal();
        });
    }
}
