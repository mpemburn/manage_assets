export default class Permissions {
    constructor(options) {
        this.csrf = $('[name="_token"]');
        this.bToken = $('[name="b_token"]');
        this.saveButton = $('#save_permission');
        this.editForm = $("#permission_edit_form");
        this.errorMessage = $("#permission_error");

        // Modal is added in app.js
        this.modal = options.modal;

        if (this.editForm.is('*')) {
            this.addEventListeners();
        }
    }

    addEventListeners() {
        let self = this;
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
                        .fadeOut(4000, function () {
                            $(this).addClass('opacity-0').show();
                        });
                }
            });
        });
    }
}
