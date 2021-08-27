<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Model untuk modul Kelompok
 *
 * donjo-app/models/Kelompok_master_model.php
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
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package OpenSID
 * @author Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html GPL V3
 * @link https://github.com/OpenSID/OpenSID
 */

class Kelompok_master_model extends MY_Model {

	protected $tipe = 'kelompok';

	public function __construct()
	{
		parent::__construct();
	}

	public function set_tipe(string $tipe)
	{
		$this->tipe = $tipe;

		return $this;
	}

	public function autocomplete()
	{
		return $this->autocomplete_str('kelompok', 'kelompok_master');
	}

	private function search_sql()
	{
		if ($search = $this->session->cari)
		{
			$this->db
				->group_start()
					->like('u.kelompok', $search)
					->or_like('u.deskripsi', $search)
				->group_end();
		}

		return $this->db;
	}

	public function paging($p = 1, $o = 0)
	{
		$this->db->select('COUNT(u.id) as jml');
		$this->list_data_sql();
		$jml_data = $this->db->get()->row()->jml;

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $this->session->per_page;
		$cfg['num_links'] = 10;
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_sql()
	{
		$this->db->from('kelompok_master u')
			->where('tipe', $this->tipe);

		$this->search_sql();

		return $this->db;
	}

	// $limit = 0 mengambil semua
	public function list_data($o = 0, $offset = 0, $limit = 0)
	{
		switch ($o)
		{
			case 1: $this->db->order_by('u.kelompok'); break;
			case 2: $this->db->order_by('u.kelompok', 'desc'); break;
			default: $this->db->order_by('u.kelompok'); break;
		}

		$this->list_data_sql();

		return $this->db
			->limit($limit, $offset)
			->get()
			->result_array();
	}

	public function insert()
	{
		$data = $this->validasi($this->input->post());
		$outp = $this->db->insert('kelompok_master', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function update($id = 0)
	{
		$data = $this->validasi($this->input->post());
		$this->db->where('id', $id);
		$outp = $this->db->update('kelompok_master', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	private function validasi($post)
	{
		if ($post['id']) $data['id'] = bilangan($post['id']);
		$data['kelompok'] = nama_terbatas($post['kelompok']);
		$data['deskripsi'] = htmlentities($post['deskripsi']);
		$data['tipe'] = $this->tipe;

		return $data;
	}

	public function delete($id = '', $semua = FALSE)
	{
		if ( ! $semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->where('tipe', $this->tipe)->delete('kelompok_master');

		status_sukses($outp, $gagal_saja = TRUE); //Tampilkan Pesan
	}

	public function delete_all()
	{
		$this->session->success = 1;

		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->delete($id, $semua=true);
		}
	}

	public function get_kelompok_master($id = 0)
	{
		return $this->db
			->where([
				'id' => $id,
				'tipe' => $this->tipe,
			])
			->get('kelompok_master')
			->row_array();
	}
}
