@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
<h1>
    Data Terpadu Kesejahteran Sosial {{ \App\Enums\Dtks\DtksEnum::VERSION_LIST[\App\Enums\Dtks\DtksEnum::VERSION_CODE] }}
</h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ route('hom_sid') }}"><i class="fa fa-home"></i> Home</a></li>
    <li class="active">DTKS</li>
@endsection

@section('content')

    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ route('rtm') }}" class="btn btn-social btn-default btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-reply'></i>Kelola Rumah Tangga</a>
            <a href="#" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modal-survey"><i class="fa fa-plus"></i> Data Baru</a>
            <a href="#" id="cetak_terpilih" disabled class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" ><i class="fa fa-print "></i> Cetak Prelist Terpilih</a>
            <a href="{{ route('dtks/ekspor?versi=' . \App\Enums\Dtks\DtksEnum::VERSION_CODE) }}" class="btn btn-social btn-sm bg-navy visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-file"></i> Ekspor ke excel</a>
        </div>
        <div class="box-body">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover nowrap" id="tabeldata">
                        <thead class="bg-gray disabled color-palette">
                            <tr>
                                <th rowspan="2"><input type="checkbox" id="checkall" /></th>
                                <th rowspan="2">No</th>
                                <th rowspan="2" class="padat">Aksi</th>
                                <th colspan="2" class="padat" kolom="3 & 4">Kepala Keluarga</th>
                                <th rowspan="2" kolom="5">{{ ucwords($setting->sebutan_dusun) }}</th>
                                <th rowspan="2">RW</th>
                                <th rowspan="2">RT</th>
                                <th rowspan="2" class="padat">Jumlah <br>ART</th>
                                <th rowspan="2">Jumlah Keluarga</th>
                                <th rowspan="2">Petugas</th>
                                <th rowspan="2">Responden</th>
                                <th rowspan="2">Versi Kuisioner</th>
                                <th rowspan="2">Terakhir diubah</th>
                            </tr>
                            <tr>
                                <th>NIK</th>
                                <th nowrap>Nama</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </form>

        </div>
    </div>
    <div class="modal fade" id="modal-survey" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-blue">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Data Baru</h4>
                </div>
                <form data-action="{{ route('dtks.new') }}" id="form-new-dtks" method="POST">
                    <div class="modal-body">
                        <div class="col-sm-12">
                            <div class="box" style="border-top:none">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="id_rtm">NIK / Nama Kepala Rumah Tangga</label>
                                        <select class="form-control input-sm select2 required"  id="id_rtm" name="id_rtm" style="width:100%;">
                                            <option option value="">-- Silakan Cari NIK / Nama Kepala Rumah Tangga--</option>
                                            @foreach ($rtm as $data)
                                                <option value="{{ $data->id }}">NIK :{{ $data->kepalaKeluarga->nik  . ' - ' . $data->kepalaKeluarga->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
                                </div>
                                <div>
                                    @include('admin.dtks.info_new_dtks')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-social btn-danger btn-sm pull-left" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-confirm-delete-dtks" style="overflow: scroll;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! form_open('', 'class="" id="form-delete-dtks"') !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle text-red"></i> Konfirmasi</h4>
                    </div>
                    <div class="modal-body btn-info">
                        Apakah Anda yakin ingin menghapus data ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
                        <button type="submit" class="btn btn-social btn-danger btn-sm" id="okdelete"><i class="fa fa-trash-o"></i> Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-cetak-multi-dtks" style="overflow: scroll;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Proses Cetak</h4>
                </div>
                {!! form_open(route('dtks/cetak2'), 'method="POST"') !!}
                    <div class="modal-body">
                        <p class="alert alert-info">
                            Proses cetak dapat memakan waktu cukup lama dan memerlukan halaman ini untuk tetap terbuka
                        </p>

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <td>NIK</td>
                                    <td>Status</td>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="download_zip" class="btn btn-sm btn-social btn-primary"><i class="fa fa-check"></i> Hanya cetak file yang sudah siap</button>
                        <button type="button" id="batal_cetak" class="btn btn-danger btn-sm" data-dismiss="modal">Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-ekspor" style="overflow: scroll;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Proses Cetak</h4>
                </div>
                {!! form_open(route('dtks/ekspor'), 'method="GET"') !!}
                    <div class="modal-body">
                        <select name="versi" class="form-control">
                            @foreach(App\Enums\Dtks\DtksEnum::VERSION_LIST as $key => $value)
                                <option value="{{ $key }}" {{ $key == 1 ? 'disabled' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batalkan</button>
                        <button type="submit" class="btn btn-sm btn-social btn-primary"><i class="fa fa-check"></i> Ekspor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-impor" style="overflow: scroll;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Proses Impor</h4>
                </div>
                {!! form_open(route('dtks/impor'), 'method="GET"') !!}
                    <div class="modal-body">
                        <select name="versi" class="form-control">
                            @foreach(App\Enums\Dtks\DtksEnum::VERSION_LIST as $key => $value)
                                <option value="{{ $key }}" {{ $key == 1 ? 'disabled' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        <div id="impor_info"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batalkan</button>
                        <button type="submit" class="btn btn-sm btn-social btn-primary"><i class="fa fa-check"></i> Impor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@include('admin.layouts.components.ajax_dtks')
<script>
    $(document).ready(function () {
        let batal_cetak = false;

        $.fn.modal.Constructor.prototype.enforceFocus = function() {};
        // Select2 dengan fitur pencarian karena tidak ngeload /js/custom.select2.js
        $('.select2').select2({
            width: '100%',
            dropdownAutoWidth : true
        });

        var TableData = $('#tabeldata').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('dtks.datatables') }}",
            columns: [
                { data: 'ceklist', class: 'padat', searchable: false, orderable: false },
                { data: 'DT_RowIndex', class: 'padat', searchable: false, orderable: false },
                { data: 'aksi', class: 'aksi', searchable: false, orderable: false},
                { data: 'nik', name: 'nik', searchable: true, orderable: true },
                { data: 'nama', name: 'nama', searchable: true, orderable: true },
                { data: 'dusun', name: 'dusun', searchable: true, orderable: true },
                { data: 'rw', name: 'rw', searchable: true, orderable: true },
                { data: 'rt', name: 'rt', searchable: true, orderable: true },
                { data: 'anggota_count', name: 'anggota_count', searchable: false, orderable: true },
                { data: 'keluarga_count', name: 'keluarga_count', searchable: false, orderable: true },
                { data: 'petugas', name: 'nama_petugas_pencacahan', searchable: true, orderable: true },
                { data: 'responden', name: 'nama_responden', searchable: true, orderable: true },
                { data: 'versi_kuisioner', name: 'versi_kuisioner', searchable: false, orderable: true },
                { data: 'updated_at', name: 'updated_at', searchable: true, orderable: true },
            ],
            order: [[ 3, 'asc' ]],
            language: {
                'url': "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}"
            }
        });

        if (hapus == 0) {
            TableData.column(0).visible(false);
        }

        if (ubah == 0) {
            TableData.column(2).visible(false);
        }
        $('#form-new-dtks').one('submit', function(ev){
            ev.preventDefault();
            let id_rtm = $('#id_rtm').val();
            $('#form-new-dtks').attr('action', $('#form-new-dtks').data('action') + '/' + id_rtm);
            $(this).submit();
        });

        let dtks_id = null;
        $(document).on('click', '.btn-hapus', function(){
            dtks_id = $(this).data('id');
        });
        $('#form-delete-dtks').on('submit', function(ev){
            ev.preventDefault();

            let form = $('#form-delete-dtks').serializeArray();
            ajax_save_dtks("{{ route('dtks.delete') }}" + "/" + dtks_id,
                callback_success = function(data){
                    location.reload();
                },
                callback_fail = function(xhr){
                }
            );
            $('#modal-confirm-delete-dtks').modal('hide');
            $('#tabeldata').DataTable().ajax.reload();

        });

        $('#batal_cetak').on('click', function(){
            batal_cetak = true;
            batal_cetak = true;
        });
        $(document).on('click', 'input[type=checkbox]', function(){
            let checked = [];
            $('input[type=checkbox]:checked').each(function(index, el){
                if(el.value != 'on'){
                    checked.push(el.value);

                    let nik = $(el).parentsUntil('tr').parent().find('td:eq(3)').text();
                    $('#modal-cetak-multi-dtks tbody').append('<tr><td>' + nik + '</td><td id="status_' + el.value + '">Menunggu</td></tr>')
                }
            });

            $('#cetak_terpilih').prop('disabled', checked.length == 0);
            $('#cetak_terpilih').attr('disabled', checked.length == 0);
        });

        $('#cetak_terpilih').on('click', function(ev_cetak_terpilih){
            let checked = [];
            $('#modal-cetak-multi-dtks tbody').empty();
            $('input[type=checkbox]:checked').each(function(index, el){
                if(el.value != 'on'){
                    checked.push(el.value);

                    let nik = $(el).parentsUntil('tr').parent().find('td:eq(3)').text();
                    $('#modal-cetak-multi-dtks tbody').append('<tr><td>' + nik + '</td><td id="status_' + el.value + '">Menunggu</td></tr>')
                }
            });
            if(checked.length == 0) {
                return;
            }

            $('#modal-cetak-multi-dtks').modal();

            function ubah_status_file(list){
                list.forEach(element => {
                    if(element.status_file == 0){
                        $(document).find('#status_' + element.id).text('Menunggu');
                    }else{
                        $(document).find('#status_' + element.id).html('<input type="hidden" name="id[]" value="' + element.id + '">Selesai')
                    }
                });
            }

            let callback_fail = function(xhr){
            };
            let callback_success = function(data){
                if(data.message == 'Mengunduh 1 data'){
                    window.open(data.href, '_blank');
                    $(this).prop('disabled', false);
                    $('#modal-cetak-multi-dtks').modal('hide');
                }else if(data.message == 'Proses Data' && ! batal_cetak){
                    ubah_status_file(data.list);
                    ajax_save_dtks("{{ route('dtks/cetak2') }}", {id : checked},
                        callback_success,
                        callback_fail
                    );
                }else if(! batal_cetak) {
                    ubah_status_file(data.list);
                    $('#download_zip').trigger('click');
                }
            };

            batal_cetak = false;
            ajax_save_dtks("{{ route('dtks/cetak2') }}", {id : checked},
                callback_success,
                callback_fail
            );
            //
        });
        $('#modal-impor').on('show.bs.modal', function(){
            $('#impor_info').empty();
            $('#impor_info').load("<?=route('dtks/loadRecentImpor')?>");
        });
    });
</script>
@endpush
