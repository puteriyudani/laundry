<div class="modal fade" id="edit_harga" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Edit Harga Laundry - </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('edit-harga') }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Menentukan metode HTTP PUT -->
                    <input type="hidden" name="id_harga" id="id_harga">
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Jenis:</label>
                        <input type="text" name="jenis" id="jenis" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Status:</label>
                        <select name="status" id="status" class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Lama Hari:</label>
                        <input type="text" name="hari" id="hari" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Berat Per-Kg:</label>
                        <input type="text" name="kg" id="kg" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Harga Per-Kg:</label>
                        <input type="text" name="harga" id="harga" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Edit</button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
