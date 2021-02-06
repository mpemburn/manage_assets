export default class DatatablesManager {
    constructor() {
        this.table = $('#data-table');
        this.table.DataTable({
            pageLength: 250,
            lengthMenu: [ 10, 25, 50, 75, 100, 250 ]
        });

        $('[name="data-table_length"]').css({'width': '100px'});
    }
}
