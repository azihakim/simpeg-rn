<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Rekap Absensi</title>
	<style>
		table {
			width: 100%;
			border-collapse: collapse;
		}

		th,
		td {
			border: 1px solid #ddd;
			padding: 8px;
			text-align: center;
		}

		th {
			background-color: #f4f4f4;
		}

		.left-align {
			text-align: left;
		}
	</style>
</head>

<body>
	<h3 style="text-align: center;">Rekap Absensi</h3>
	<p style="text-align: center;">Periode: {{ $tanggal_dari }} - {{ $tanggal_sampai }}</p>

	<table>
		<thead>
			<tr>
				<th>Nama Karyawan</th>
				@foreach ($tanggal_range as $tanggal)
					<th>{{ \Carbon\Carbon::parse($tanggal)->format('d') }}</th>
				@endforeach
				<th>Persentase</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($data_karyawan as $karyawan)
				<tr>
					<td class="left-align">{{ $karyawan['nama'] }}</td>
					@foreach ($tanggal_range as $tanggal)
						<td>
							@php
								$status = $karyawan['absensi'][$tanggal] ?? '-';
								echo $status === '-'
								    ? '-'
								    : (str_contains($status, 'Cuti')
								        ? 'C'
								        : (str_contains($status, 'Izin')
								            ? 'I'
								            : (str_contains($status, 'masuk') && str_contains($status, 'pulang')
								                ? 'MP'
								                : (str_contains($status, 'masuk')
								                    ? 'M'
								                    : 'P'))));
							@endphp
						</td>
					@endforeach
					<td>{{ number_format($karyawan['persentase'], 2) }}%</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</body>

</html>
