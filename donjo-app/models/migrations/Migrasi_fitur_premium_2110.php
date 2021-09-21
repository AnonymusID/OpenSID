<?php

/**
 * File ini:
 *
 * Model untuk modul database
 *
 * donjo-app/models/migrations/Migrasi_fitur_premium_2110.php
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 */

class Migrasi_fitur_premium_2110 extends MY_Model
{
	public function up()
	{
		log_message('error', 'Jalankan ' . get_class($this));
		$hasil = true;

		$hasil = $hasil && $this->migrasi_2021090971($hasil);
		$hasil = $hasil && $this->migrasi_2021091771($hasil);
		$hasil = $hasil && $this->migrasi_2021091751($hasil);
		$hasil = $hasil && $this->migrasi_2021092071($hasil);

		status_sukses($hasil);
		return $hasil;
	}

	protected function migrasi_2021090971($hasil)
	{
		if (! $this->db->field_exists('waktu', 'pembangunan'))
		{
			$hasil = $hasil && $this->dbforge->add_column('pembangunan', ['waktu' => ['type' => 'INT', 'constraint' => 11, 'default' => 0]]);
		}

		if (! $this->db->field_exists('sifat_proyek', 'pembangunan'))
		{
			$hasil = $hasil && $this->dbforge->add_column('pembangunan', ['sifat_proyek' => ['type' => 'VARCHAR', 'constraint' => '100', 'default' => 'BARU']]);
		}

		$hasil = $hasil && $this->tambah_modul([
			'id'     => 329,
			'modul'  => 'Bumindes Kegiatan Pembangunan',
			'url'    => 'bumindes_kegiatan_pembangunan',
			'aktif'  => 1,
			'hidden' => 2,
			'parent' => 301
		]);

		// Tambah hak ases group operator
		$query = "
			INSERT INTO grup_akses (`id_grup`, `id_modul`, `akses`) VALUES
			-- Operator --
			(2,329,3) -- Bumindes Kegiatan Pembangunan --
			";

		return $hasil && $this->db->query($query);
	}

	protected function migrasi_2021091771($hasil)
	{
		$subjek = [
			'id' => 5,
			'subjek' => 'Desa',
		];
		$sql = $this->db->insert_string('analisis_ref_subjek', $subjek) . " ON DUPLICATE KEY UPDATE subjek = VALUES(subjek)";
		$hasil = $hasil && $this->db->query($sql);

		return $hasil;
	}

		protected function migrasi_2021091751($hasil)
	{
		$hasil = $hasil && $this->ubah_modul(101, ['modul'  => 'Status [Desa]']);

		$hasil = $hasil && $this->ubah_modul(301, ['modul'  => 'Buku Administrasi [Desa]']);

		$hasil = $hasil && $this->ubah_modul(311, ['modul'  => 'Buku Lembaran Dan Berita [Desa]']);

		$hasil = $hasil && $this->ubah_modul(319, ['modul'  => 'Buku Tanah Kas [Desa]']);

		$hasil = $hasil && $this->ubah_modul(320, ['modul'  => 'Buku Tanah di [Desa]']);

		$hasil = $hasil && $this->ubah_modul(322, ['modul'  => 'Buku Inventaris dan Kekayaan [Desa]']);

		return $hasil;
	}

	protected function migrasi_2021092071($hasil)
	{
		$hasil = $hasil && $this->tambah_modul_laporan_sinkronisasi($hasil);
		$hasil = $hasil && $this->hak_akses_sinkronisasi_opendk($hasil);
		$hasil = $hasil && $this->ubah_nama_tabel($hasil);

		return $hasil;
	}

	// Menu Laporan sinkronisasi
	protected function tambah_modul_laporan_sinkronisasi($hasil)
	{
		$fields = [
			'id' => 330,
			'modul' => 'Laporan penduduk',
			'url' => 'laporan_penduduk',
			'aktif' => 1,
			'ikon' => 'fa-file-text-o',
			'urut' => 5,
			'level' => 2,
			'hidden' => 1,
			'ikon_kecil' => 'fa-file-text-o',
			'parent' => 3
		];

		$hasil = $hasil && $this->tambah_modul($fields);

		// Hapus cache menu navigasi
		$this->load->driver('cache');
		$this->cache->hapus_cache_untuk_semua('_cache_modul');

		return $hasil;
	}

  private function hak_akses_sinkronisasi_opendk($hasil)
	{
		// Tambah hak ases group operator
		$query = "
			INSERT INTO grup_akses (`id_grup`, `id_modul`, `akses`) VALUES
			-- Operator --
			(2,325,3), -- Laporan APBDes --
			(2,326,3), -- Sinkronisasi --
      (2,330,3) -- Laporan Penduduk --
		";

		return $hasil && $this->db->query($query);
	}

  private function ubah_nama_tabel($hasil)
	{
		if (! $this->db->field_exists('tipe', 'laporan_apbdes'))
		{
			$fields = [
				'tipe' => [
					'type' => 'VARCHAR',
					'constraint' => 50,
					'after' => 'id',
				],
			];

			$hasil = $hasil && $this->dbforge->add_column('laporan_apbdes', $fields);

			// Ubah nama tabel
			$hasil = $hasil && $this->dbforge->rename_table('laporan_apbdes', 'laporan_sinkronisasi');

			// Default data yg sudah ada
			$hasil = $hasil && $this->db->where('tipe', NULL)->update('laporan_sinkronisasi', ['tipe' => 'laporan_apbdes']);
		}

		return $hasil;
	}
}