<?php

/*
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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\AnjunganMenu;
use App\Models\Artikel;
use Carbon\Carbon;

defined('BASEPATH') || exit('No direct script access allowed');

class Anjungan extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('web');
        if ($this->cek_anjungan['tipe'] != 1) {
            redirect('layanan-mandiri/beranda');
        }
    }

    public function index()
    {
        $menu = AnjunganMenu::where('status', 1)->get()->map(static function ($item) {
            $item->link = menu_slug($item->link);

            return $item;
        });

        $data = [
            'header'        => $this->header,
            'cek_anjungan'  => $this->cek_anjungan,
            'arsip_terkini' => Artikel::arsip()->orderBy('tgl_upload', 'DESC')->limit(4)->get(),
            'arsip_populer' => Artikel::arsip()->orderBy('hit', 'DESC')->limit(4)->get(),
            'tanggal'       => Carbon::now()->dayName . ', ' . date('d/m/Y'),
            'menu'          => $menu,
            'slides'        => count($menu) > 5 ? 5 : count($menu),
            'teks_berjalan' => setting('anjungan_teks_berjalan'),
        ];

        return view('layanan_mandiri.anjungan.index', $data);
    }
}
