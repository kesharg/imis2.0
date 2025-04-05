<!-- Modal -->
@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Sewer Connection</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Existing Sanitation of Building will be updated as Sewer Network and corresponding containment
                connection (if any) will be removed !
                <br>

                <div style="margin-top:2%; font-weight:bold; text-align:center; font-size:15px">
                    Are you sure?
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="confirmYes">Yes</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var binValue;
        var sewer;
    $('#exampleModalCenter').on('show.bs.modal', function (event) {
       
        var button = $(event.relatedTarget); // Button that triggered the modal
        binValue = button.data('id'); // Extract bin from data-id attribute
        sewer = button.data('sewer-code'); // Extract sewer_code from data-sewer-code attribute
        // Update the modal's content with value from data-id and data-sewer-code attributes
        var modal = $(this);
        modal.find('.modal-body #bin').val(bin);
        modal.find('.modal-body #sewer_code').val(sewer);
    });

        // Fetch the bin value from wherever it's stored
      $('#confirmYes').on('click', function() {
    if (binValue) { // Check if binValue is not undefined or null
        // Construct the URL with the fetched bin value
        var url = '/sewerconnection/sewerconnection/data/' + binValue;

        // Fetch the value of sewer_code from wherever you get it
         // Replace with your actual value

        // Make an AJAX request to your backend URL with the bin and sewer_code parameters
        $.ajax({
            url: url,
            method: 'GET', 
            data: { sewer: sewer }, // Pass sewer_code data to the backend
            success: function(response) {
                // Handle success response
                if (response.status === 'success') {
                    // Display success swal if the response status is success
                    swal({
                        title: "Success!",
                        text: "Building updated successfully",
                        icon: "success",
                        closeOnClickOutside: false, // Prevent close on outside click
                    }).then((result) => {
                        $('#exampleModalCenter').modal('hide');
                        $('.modal-backdrop').remove();
                    });
                } 
                        },
            error: function(xhr, status, error) {
                // Handle error response
                swal({
                        title: "Warning!",
                        text: "Building not found",
                        icon: "warning",
                        closeOnClickOutside: false, // Prevent close on outside click
                    }).then((result) => {
                        $('#exampleModalCenter').modal('hide');
                        $('.modal-backdrop').remove();
                    });
                
            }
        });
    } else {
        console.error('Bin value is not available.');
    }
});
   

});

</script>
@endpush