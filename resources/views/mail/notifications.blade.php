<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="https://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
	<title>Notifikasi</title>
</head>
<body style="-moz-box-sizing: border-box; -ms-text-size-adjust: 100%; -webkit-box-sizing: border-box; -webkit-text-size-adjust: 100%; Margin: 0; box-sizing: border-box; color: #343434; font-family: 'Open Sans', sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; min-width: 100%; padding: 0; text-align: left; width: 100% !important;">
	<div style="text-align: center">
		<img src="https://app.kotakarsip.com/assets/app/img/email/head.png" alt="Logo KotakArsip" />
		<div style="max-width: 450px; width: 100%; padding: 20px; margin: auto">
			<h2 style="color: #999; font-weight: normal;">Hi <b style="color: #0070ff">{{ $fullname }}</b>,</h2>
			<p style="color: #999; line-height: 24px; font-weight: normal"><b style="color: #0070ff">{{ $user_from }}</b> telah membagikan arsip kepada Anda.<br>Berikut detail arsipnya:</p>
			<br />
			<table style="text-align: left; width: 100%; border: none; border-spacing: 0">
				<tr style="border: none;">
					<td style="border: none; border-top: 1px solid #ccc; padding: 10px 0; color: #999; vertical-align: top; width: 115px">
						@if ($type == 'incoming_mail')
							Asal Surat
						@elseif ($type == 'outgoing_mail')
							Tujuan Surat
						@elseif ($type == 'file')
							Judul Arsip
						@endif
					</td>
					<td style="border: none; border-top: 1px solid #ccc; padding: 10px 0; color: #999; vertical-align: top"> : </td>
					<td style="border: none; border-top: 1px solid #ccc; padding: 10px 0; color: #666; vertical-align: top">{{ $archieve }}</td>
				</tr>
				<tr style="border: none;">
					<td style="border: none; border-top: 1px solid #ccc; padding: 10px 0; color: #999; vertical-align: top; width: 115px">Perihal</td>
					<td style="border: none; border-top: 1px solid #ccc; padding: 10px 0; color: #999; vertical-align: top"> : </td>
					<td style="border: none; border-top: 1px solid #ccc; padding: 10px 0; color: #666; vertical-align: top">{{ $subject }}</td>
				</tr>
				<tr style="border: none;">
					<td style="border: none; border-top: 1px solid #ccc; padding: 10px 0; color: #999; vertical-align: top; width: 115px">
						@if ($type == 'incoming_mail')
							Tanggal Masuk
						@elseif ($type == 'outgoing_mail')
							Tanggal Keluar
						@elseif ($type == 'file')
							Tanggal Unggah
						@endif
					</td>
					<td style="border: none; border-top: 1px solid #ccc; padding: 10px 0; color: #999; vertical-align: top"> : </td>
					<td style="border: none; border-top: 1px solid #ccc; padding: 10px 0; color: #666; vertical-align: top">{{ $date }}</td>
				</tr>
				<tr style="border: none;">
					<td style="border: none; border-top: 1px solid #ccc; padding: 10px 0; color: #999; vertical-align: top; width: 115px">Lampiran</td>
					<td style="border: none; border-top: 1px solid #ccc; padding: 10px 0; color: #999; vertical-align: top"> : </td>
					<td style="border: none; border-top: 1px solid #ccc; padding: 10px 0; color: #666; vertical-align: top">{{ $files }}</td>
				</tr>
				<tr style="border: none;">
					<td style="border: none; border-top: 1px solid #ccc; padding: 10px 0; color: #999; vertical-align: top; width: 115px">Pesan</td>
					<td style="border: none; border-top: 1px solid #ccc; padding: 10px 0; color: #999; vertical-align: top"> : </td>
					<td style="border: none; border-top: 1px solid #ccc; padding: 10px 0; color: #666; vertical-align: top">{{ $disposition_message }}</td>
				</tr>
			</table>
			<br />
			<br />
			@if ($type == 'incoming_mail')
				<?php $type_archieve = 'surat/masuk' ?>
			@elseif ($type == 'outgoing_mail')
				<?php $type_archieve = 'surat/keluar' ?>
			@elseif ($type == 'file')
				<?php $type_archieve = 'berkas' ?>
			@endif
			<a href="https://app.kotakarsip.com/{{ $type_archieve }}/detail/{{ $id }}?read_direct=true" style="background-color: #0070ff; color: #fff; padding: 15px 20px; text-align: center; display: inline-block; text-decoration: none; border-radius: 3px; font-weight: normal">Lihat di Aplikasi</a>
			<br />
			<br />
			<p style="color: #999; line-height: 24px; font-weight: normal">Dapatkan kemudahan memanajemen arsip pada perusahaan Anda di KotakArsip.com</p>
			<img src="https://app.kotakarsip.com/assets/app/img/email/thanks.png" alt="Logo KotakArsip" />
		</div>
	</div>
</body>
</html>