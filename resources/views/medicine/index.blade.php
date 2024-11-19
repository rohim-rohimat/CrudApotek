@extends('layout/layout')

    @section('content')
    <div class="container">

        {{-- @if(Request::get('short_stock' == 'stock'))
        <input type="hidden" name="short_stock" value="stock">
        @endif
        <form class="me-2" role="Stock" action="{{route('obat.data')}}" method="GET">
            <input type="hidden" name="short_stock" value="stock">
            <button type="submit" class="btn btn-primary" >Urutkan Stok</button>
        </form> --}}

          {{-- </form>
          @if(Request::get('up_stock' == 'stock'))
          <input type="hidden" name="up_stock" value="stock">
          @endif
          <form class="me-2" role="Stock" action="{{route('obat.data')}}" method="GET">
              <input type="hidden" name="up_stock" value="stock">
              <button type="submit" class="btn btn-primary" >Urutkan Stok</button>
            </form> --}}

        <a href="{{ route('obat.tambah_obat.formulir') }}" class="btn btn-outline-success m-2">+ Tambah</a>
        @if(Session::get('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <a href="{{route('medicine.admin.export')}}" class="btn btn-success">Export Excel</a>
        <table class="table table-bordered table-stripped">
            <thead class="text-center">
                <tr>
                    <th>No</th>
                <th>Nama Obat</th>
                    <th>Tipe</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>aksi</th>
                </tr>
                <tbody>
                @if(count($medicines) < 1)
                    <tr>
                        <td colspan="6" class="text-center">
                            Data obat kosong
                        </td>
                    </tr>
                    @else
                    @foreach ($medicines as $index => $item)
                    <tr>
                        <td class="text-center">{{ ($medicines->currentpage()-1) * ($medicines->perPage()) + ($index+1) }}</td>
                        <td  class="text-center">{{ $item['name']}}</td>
                        <td class="text-center">{{ $item['type'] }}</td>
                        <td class="text-center">Rp. {{ number_format($item['price'], 0,',',',') }}</td>
                        <td class="{{ $item['stock'] <= 3 ? 'bg-danger text-white' : ''}} text-center" style="cursor: pointer;" onclick="showModalStock( '{{ $item->id}}' , '{{ $item->stock}}')">{{ $item['stock'] }}</td>
                        <td class="text-center">
                            <a href="{{ route('obat.edit' , $item['id']) }}" class="btn btn-primary m-2">Edit</a>
                            <button class="btn btn-danger m-2" onclick="showModal( '{{ $item->id}}' , '{{ $item->name}}')">Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                @endif
                </tbody>
            </thead>
        </table>
        <div class="d-flex justify-content-end">
            {{-- link : memunculkan button pagination --}}
            {{ $medicines->links() }}
        </div>
    </div>

    {{---Modal hapus---}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-delete-obat" method="POST">
                @csrf
                {{-- menimpa method="POST" diganti menjadi delete, sesuai dengan http
                method untul menghapus data---}}
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data Obat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus obat <span id="nama-obat"></span>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                        <button type="submit" class="btn btn-danger" id="confirm-delete">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{--Modal edit stock--}}
    <div class="modal fade" id="modal_edit_stock" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form_edit_stock" method="POST">
                @csrf
                {{-- menimpa method="POST" diganti menjadi delete, sesuai dengan http
                method untul menghapus data---}}
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example_modal_label">Edit Stock Obat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                       <div class="form-group">
                        <label for="stock_edit" class="form-label">Stok</label>
                        <input type="number" name="stock" id="stock_edit" class="form-control">
                        @if (Session::get('failed'))
                            <small class="text-danger">{{Session::get('failed') }}</small>
                        @endif
                       </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                        <button type="submit" class="btn btn-danger" id="confirm_edit">Edit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @endsection
    @push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
    <script>
        //function untuk hapus
        function showModal(id , name){
            // ini untuk url delete nya (route)
            let urlDelete = "{{ route('obat.delete', ':id')}}";
            urlDelete = urlDelete.replace(':id', id);
            // ini untuk action atributnya
            $('#form-delete-obat').attr('action' , urlDelete);
            //ini untuk show modalnya
            $('#exampleModal').modal('show');

            $('#nama-obat').text(name);
        }

        function showModalStock(id, stock){
            //mengisi stock yang dikirim ke input yang id nya stok_edit
            $("#stock_edit").val(stock);
            //ambil route patch stok
            let url = "{{route ('obat.edit.stock', ':id') }}";
            //isi path dinamis :id dengan id dari parameter ($item_>id)
            url = url.replace(':id', id);
            //url tadi kirim ke action
            $("#form_edit_stock").attr("action", url);
            //tampilkan modal
            $("#modal_edit_stock").modal("show");
        }

        @if (Session::get('failed'))

            $( document ).ready(function(){
                //id dari with failed 'id' controller redirect back
                let id = "{{ Session::get('id') }}";
                //stock dari with failed 'stock' controller redirect back
                let stock = "{{Session::get('stock') }}";
                //panggil func showModalStock dengan data id dan stock diatas
                showModalStock(id, stock);
            });
        @endif
    </script>
    @endpush
