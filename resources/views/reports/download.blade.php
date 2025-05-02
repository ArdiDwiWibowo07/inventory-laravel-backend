<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan</title>
    <style>
        table {
            width: 100%;
            color: rgb(107 114 128);
            border-top-width: 1px;
            border-bottom-width: 0px;
            border-color: rgb(229 231 235);
            font-family: Arial, Helvetica, sans-serif;
        }

        thead {
            color: #374151;
            background-color: #d1d5db;
            text-transform: uppercase;
            font-size: 0.75rem;
            line-height: 1rem;
        }

        th {
            padding-left: 1rem;
            padding-right: 1rem;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        tbody {
            border-top-width: 1px;
            border-bottom-width: 0px;
            border-color: rgb(229 231 235);
        }

        td {
            padding-left: 1rem;
            padding-right: 1rem;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            white-space: nowrap;
            border-bottom: 1px solid rgb(229 231 235);
        }

        .text-center {
            text-align: center;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            font-weight: 500;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        Laporan Data Barang
        <br>
        <div style="margin-top:10px">
            {{ Carbon\Carbon::parse($from_date)->format('d M Y') }}
            -
            {{ Carbon\Carbon::parse($to_date)->format('d M Y') }}
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th width="10px">#</th>
                <th>Nama Produk</th>
                <th class="text-center">Tanggal Transaksi</th>
                <th class="text-center">Jenis Transaksi</th>
                <th class="text-center">Kuantitas</th>
                <th class="text-center">Stok</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_quantity = [];
                $total_stock = [];
            @endphp
            @forelse ($reports as $key => $report)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $report->name }}</td>
                    <td class="text-center">-</td>
                    <td class="text-center">Stok awal</td>
                    <td class="text-center">-</td>
                    <td class="text-center">
                        {{ count($first_stock) > 0 ? $first_stock[$report->id]['stok'] : 0 }}
                    </td>
                </tr>
                @php
                    $total_quantity[$report->product_id] = 0;
                    $total_stock[$report->product_id] = count($first_stock) > 0 ? $first_stock[$report->id]['stok'] : 0;
                @endphp
                @foreach ($report->stocks as $index => $stock)
                    @php
                        $stock->type == 'in'
                            ? ($total_quantity[$report->product_id] += $stock->quantity)
                            : ($total_quantity[$report->product_id] -= $stock->quantity);

                        $stock->type == 'in'
                            ? ($total_stock[$report->product_id] += $stock->quantity)
                            : ($total_stock[$report->product_id] -= $stock->quantity);
                    @endphp
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-center">{{$stock->created_at->format('d-m-Y')}}</td>
                        <td class="text-center">{{ ucfirst($stock->type) }}</td>
                        <td class="text-center {{ $stock->type == 'in' ? 'text-teal' : 'text-danger' }}">{{ $stock->quantity }}</td>
                        <td class="text-center">{{ $total_stock[$report->product_id] }}</td>
                    </tr>
                @endforeach
                <tr style="background-color: #FAFAFB ">
                    <td colspan="4"><b>Total Kuantitas</b></td>
                    <td></td>
                    <td class="text-center">{{ $total_stock[$report->product_id] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Data transaksi tidak ditemukan....</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>