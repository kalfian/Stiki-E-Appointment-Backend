<div class="modal fade" id="modalImportLecture" tabindex="-1" role="dialog" aria-labelledby="modalImportLecture" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('auth.import.user') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLectureLabel">Import Lecture</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Upload CSV </label>
                        <a href="{{ asset('assets/downloads/import_lecture.csv') }}" download="csv_import_lecture_{{ date('Ymdhis') }}.csv" type="button" class="btn btn-primary btn-sm float-end">Download Template</a>
                        <input type="file"  class="form-control" name="file">
                        <input type="hidden" name="role" value="lecture">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import Lecture</button>
                </div>
            </div>
        </form>
    </div>
</div>
