<!DOCTYPE html>
<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel"
    xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta http-equiv="content-type" content="text/plain; charset=UTF-8" />
    <title>Report Asset</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        .no-wrap {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        td,
        th {
            text-align: left;
            border: 1px solid;
        }

        table {
            table-layout: auto !important;
            width: 100%;
            border-collapse: collapse;
        }

        table#tableTTD,
        table#tableTTD td {
            border: none;
        }
    </style>
    <script>
        window.print()
    </script>
</head>

<body>

    <!-- Begin Page Content -->
    <div class="container-fluid">
        <h3>Laporan Asset {{ $asset->asset_code }}</h3>
        <div class="col text-right">
            <span> <em>Tanggal Cetak : {{ now()->format('d F Y') }}</em>
        </div>
        </span>
        <div class="row mb-3">
            <div class="col">
                <h3>Asset Information</h3>
                <table class="table table-bordered">
                    <tr>
                        <th>Name</th>
                        <td>{{ $asset->asset_name }}</td>
                    </tr>
                    <tr>
                        <th>Code Acc</th>
                        <td>{{ $asset->asset_code ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>{{ $asset->group->asset_group_name }}</td>
                    </tr>
                    <tr>
                        <th>No. (Pol/Rumah/Seri)</th>
                        <td>{{ $asset->asset_no }}</td>
                    </tr>
                    <tr>
                        <th>SBU</th>
                        <td>{{ $asset->sbu->sbu_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td>{{ $asset->location }}</td>
                    </tr>
                    <tr>
                        <th>Condition</th>
                        <td>
                            {{ textCondition($asset->condition) }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            PIC
                        </th>
                        <td>{{ $asset->employee->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Purchase Date</th>
                        <td>{{ createDate($asset->pcs_date)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Purchase Value</th>
                        <td>{{ rupiah($asset->pcs_value) }}</td>
                    </tr>
                    <tr>
                        <th>Appraisal Date</th>
                        <td>
                            {{ $asset->appraisals()->exists() ? createDate($asset->appraisals->last()->apr_date)->format('d F Y') : null }}
                        </td>
                    </tr>
                    <tr>
                        <th>Appraisal Value</th>
                        <td>{{ rupiah($asset->appraisals->last()->apr_value ?? '') }}
                        </td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $asset->desc }}</td>
                    </tr>
                </table>
            </div>

            <div class="col">
                <div class="ml-3">
                    <img class=" mt-3" height="300px" width="auto"
                        src="{{ $asset->image ? route('image.displayImage', class_basename($asset->image)) : asset('/assets/img/empty-img.jpeg') }}">
                    <br> <small><em>Asset Image</em></small>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <h3>Asset Maintenance</h3>
                <table class="table table-bordered">
                    <tr>
                        <th>#</th>
                        <th>Code Acc</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>Due Date</th>
                        <th>Cost</th>
                        <th>Applicant</th>
                        <th>Approval</th>
                        <th>Cycle</th>
                        <th>Status</th>
                    </tr>
                    @foreach ($asset->trnMaintenance->whereNotNull('trn_value')->sortBy('trn_start_date') as $trn)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $trn->trn_no }}</td>
                            <td>{{ strip_tags($trn->trn_desc) }}</td>
                            <td>{{ createDate($trn->trn_start_date)->format('d/m/Y') }}</td>
                            <td>{{ createDate($trn->trn_date)->format('d/m/Y') }}</td>
                            <td class="no-wrap text-right">
                                {{ rupiah($trn->trn_value) }}</td>
                            <td>{{ $trn->pemohon }}</td>
                            <td>{{ $trn->penyetuju }}</td>
                            <td>{{ $trn->trn_type ? 'Routine' : 'Accidentally' }}</td>
                            <td>{{ $trn->trn_status ? 'Closed' : 'Open' }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-right" colspan="5">
                            <b>Total</b>
                        </td>
                        <td class="no-wrap text-right">
                            <b>{{ rupiah($asset->trnMaintenance->sum('trn_value')) }}</b>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h3>Asset Renewal</h3>
                <table class="table table-bordered">
                    <tr>
                        <th>#</th>
                        <th>Code Acc</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>Due Date</th>
                        <th>Cost</th>
                        <th>Applicant</th>
                        <th>Approval</th>
                        <th>Cycle</th>
                        <th>Status</th>
                    </tr>
                    @foreach ($asset->children as $doc)
                        @foreach ($doc->trnRenewal->whereNotNull('trn_value') as $trn)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $trn->trn_no }}</td>
                                <td>{{ strip_tags($trn->trn_desc) }}</td>
                                <td>{{ createDate($trn->trn_start_date)->format('d/m/Y') }}</td>
                                <td>{{ createDate($trn->trn_date)->format('d/m/Y') }}</td>
                                <td class="no-wrap text-right">{{ rupiah($trn->trn_value) }}</td>
                                <td>{{ $trn->pemohon }}</td>
                                <td>{{ $trn->penyetuju }}</td>
                                <td>{{ $trn->trn_type ? 'Routine' : 'Accidentally' }}</td>
                                <td>{{ $trn->trn_status ? 'Closed' : 'Open' }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr>
                        <td class="text-right" colspan="5">
                            <b>Total</b>
                        </td>
                        <td class="no-wrap text-right">
                            <b>{{ rupiah($asset->sumRenewal) }}</b>
                        </td>
                    </tr>
                </table>

                <strong> Total Cost Transaction (IDR) :
                    {{ rupiah($asset->sumRenewal + $asset->trnMaintenance->sum('trn_value')) }}</strong>
            </div>
        </div>
    </div>
    <br>
    <table id="tableTTD">
        <tr>
            <td style="text-align: center">
                Pembuat,
            </td>
            <td style="text-align: center">
                Mengetahui,
            </td>
            <td style="text-align: center">
                Menyetujui,
            </td>
        </tr>
        <tr>
            <td style="padding-top:60px; text-align:center;">
                (.............................)
            </td>
            <td style="padding-top:60px; text-align:center;">
                (.............................)
            </td>
            <td style="padding-top:60px; text-align:center;">
                (.............................)
            </td>
        </tr>
    </table>
</body>

</html>
