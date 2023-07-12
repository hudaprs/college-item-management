<div class="modal fade" id="modal-lg">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" id="btn-close-modal" class="btn btn-default pull-left">Close</button>
        <a href="" class="btn btn-danger btn-destroy" id="btn-destroy"><em class="fa fa-trash"></em>Delete</a>
        <button type="button" class="btn btn-primary save" id="btn-save">Save</button>
        <button type="button" class="btn btn-primary" id="btn-disabled" disabled>Processing</button>
      </div>
    </div>
  </div>
</div>

@push('script')
  <script>
    $(function () {
      $('#btn-disabled').hide()
      $('.btn-destroy').hide()
    }); 
  </script>
@endpush