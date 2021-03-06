@extends('Base.Dash')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-primary">Data Pertanyaan</h4>
                <br>
                @if (session('status'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    {{ session('status') }}
                </div>
                @endif
            </div>
            <div class="table-responsive p-3">
                <div id="dataTableHover_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table align-items-center table-flush table-hover dataTable" id="table_contoh"
                                role="grid" aria-describedby="dataTableHover_info">
                                <thead class="thead-light">
                                    <tr role="row">
                                        <th>No</th>
                                        <th>Point</th>
                                        <th>Questions</th>
                                        <th>Created at</th>
                                        <th>Updated at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $no = 1;
                                    @endphp
                                    @foreach ($data['all'] as $d)
                                    <tr role="row" class="odd">
                                        <td class="sorting_1">{{$no++}}</td>
                                        <td>{{$d->point}}</td>
                                        <td>{{$d->questions}}</td>
                                        <td>{{date('d-M-Y', strtotime($d->created_at))}}</td>
                                        <td>{{date('d-M-Y', strtotime($d->updated_at))}}</td>
                                        <td>
                                            <button data-id="{{$d->id}}" id="edit-data"
                                                class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></button>
                                            <button data-id="{{$d->id}}" id="del-data"
                                                class="btn btn-sm btn-secondary ml-1"><i
                                                    class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mt-5">
        <div class="card mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-primary">Formulir</h4>
            </div>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="card-body mt-2 mb-5">
                        <form autocomplete="off" action="{{route('question.create')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="contoh_input">Poin</label>
                                        <input type="number" name="point" class="form-control" id="contoh_input">
                                        <small id="contoh_input" class="form-text text-muted">Berikan nilai untuk
                                            pertanyaan.</small>
                                        <br>
                                        @error('point')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="contoh_input">Pertanyaan</label>
                                        <textarea class="form-control" name="questions" id="" rows="6"></textarea>
                                        <br>
                                        @error('questions')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mb-3">Kirim</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </div>
    @endsection
    @section('js')
    <script>
        $(document).ready(function()
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#table_contoh').DataTable();

        $(document).on('click','#edit-data',function(){
            let dataId = $(this).data('id');
            let url = "getspecdata/" + dataId;
            
            $.get(url, function(data){
                $('.modal-title').html('Perubahan Data');
                $('#edit_form').attr('action', `{{route('question.update')}}`);
                $('.modal-body').html('');
                $('.modal-body').append(`
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="contoh_input">Point</label>
                            <input type="hidden" name="id" value="`+ data.id +`">
                            <input type="text" value="`+ data.point +`" name="point" class="form-control" >
                            <small class="form-text text-muted">We'll never share your
                                email with anyone else.</small>
                        </div>
                        <div class="form-group">
                            <label for="contoh_input">Pertanyaan</label>
                            <textarea class="form-control" name="questions" id="questions" rows="6"></textarea>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
                `);
                $('#questions').val(data.questions);
                $('.modal-footer').html('');
                $('.modal-footer').append(`
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                `);
                $('#univModal').modal('show');
            })
        });

        $(document).on('click','#del-data',function(){
            let dataId = $(this).data('id');
            Swal.fire({
            title: 'Anda Yakin?',
            text: "Data ini mungkin terhubung ke tabel yang lain!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "deletespecdata/" + dataId,
                        type: 'delete',
                        success: function () {
                            Swal.fire({
                                title: 'Hapus!',
                                text: 'Data berhasl di hapus.',
                                icon: 'success',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Oke'
                            }).then((result) => {
                                location.reload();
                            });
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Ada yang salah!',
                            });
                        }
                    })
                }
            })
        });
    });
    </script>
    @endsection