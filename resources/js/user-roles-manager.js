export default class UserRolesManager {
    constructor(options) {
        this.csrf = $('[name="_token"]');
        this.bToken = $('[name="b_token"]');
        this.editForm = $('#user_role_edit_form');
        this.editButtons = $('*[data-edit]');
        this.editUserName = $('#user_name');
        this.selectedUserId = null;
        this.selectedRoleName = null;
        this.selectedPermissionName = null;
        this.roleRows = null;
        this.editorRoleCheckboxes = $('[data-type="role"]');
        this.editorPermissionCheckboxes = $('[data-type="permission"]');

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
        });
        // Uncheck all "Permissions" checkboxes
        this.editorPermissionCheckboxes.each(function () {
            $(this).prop('checked', false);
        });
    }

    getRoleForUserId(userid, roleName) {
        let self = this;

        this.selectedUserId = userid;
        this.selectedRoleName = roleName;
        let found = self.roleRows = $('*[data-userid]')
            .filter(function () {
                return $(this).attr('data-userid') === self.selectedUserId
                    && $(this).attr('data-role-name') === self.selectedRoleName;
            });

        return found.length === 1 ? found : null;
    }

    getPermissionForUserId(userid, permissionName) {
        let self = this;

        this.selectedUserId = userid;
        this.selectedPermissionName = permissionName;
        let found = self.permissionRows = $('*[data-userid]')
            .filter(function () {
                return $(this).attr('data-userid') === self.selectedUserId
                    && $(this).attr('data-permission-name') === self.selectedPermissionName;
            });

        return found.length === 1 ? found : null;
    }

    checkDialogRole(roleName) {
        let self = this;

        if (typeof(roleName) !== "undefined") {
            this.roleName = roleName;
            this.editorRoleCheckboxes.each(function () {
                let thisRoleName = $(this).attr('name')
                if (thisRoleName === self.roleName) {
                    $(this).prop('checked', true);
                }
            });
        }
    }

    checkDialogPermission(permissionName) {
        let self = this;

        if (typeof(permissionName) !== "undefined") {
            this.permissionName = permissionName;
            this.editorPermissionCheckboxes.each(function () {
                let thisPermissioName = $(this).attr('name')
                if (thisPermissioName === self.permissionName) {
                    $(this).prop('checked', true);
                }
            });
        }
    }

    addEventListeners() {
        let self = this;

        this.editButtons.on('click', function (evt) {
            let name = $(this).attr('data-name');
            let dialogRoles = $('[data-type="role"]');
            let dialogPermissions = $('[data-type="permission"]');
            self.selectedUserId = $(this).attr('data-edit');
            self.resetModal();

            dialogRoles.each(function () {
                let roleName = $(this).attr('name');
                let found = self.getRoleForUserId(self.selectedUserId, roleName)
                if (found !== null) {
                    self.checkDialogRole(found.attr('data-role-name'));
                }
            });

            dialogPermissions.each(function () {
                let permissionName = $(this).attr('name');
                let found = self.getPermissionForUserId(self.selectedUserId, permissionName)
                if (found !== null) {
                    self.checkDialogPermission(found.attr('data-permission-name'));
                }
            });

            self.editUserName.html(name);

            self.modal.toggleModal();
        });
    }
}
