// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable({
        "order": [[0, "desc"]]
    });
});
$(document).ready(function() {
  $('#dataTableNoOrder').DataTable();
});
