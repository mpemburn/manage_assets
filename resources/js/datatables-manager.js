export default class DatatablesManager {
    constructor() {
        this.table = $('#inventory-table');
        this.table.DataTable({
            pageLength: 250,
            lengthMenu: [ 10, 25, 50, 75, 100, 250 ]
        });

        $('[name="inventory-table_length"]').css({'width': '100px'});
    }
}
