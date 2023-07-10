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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Libraries\TinyMCE;

use App\Models\LogPenduduk;

class KodeIsianPeristiwa
{
    private $logPeristiwa;

    public function __construct($idPenduduk, $statusDasar)
    {
        $this->statusDasar  = $statusDasar;
        $this->logPeristiwa = LogPenduduk::where('id_pend', $idPenduduk)->latest()->first();
    }

    public function get()
    {
        switch ($this->statusDasar) {
            case LogPenduduk::MATI:
                $data = $this->getKematian($this->logPeristiwa);
                break;

            case LogPenduduk::HILANG:
                $data = $this->getHilang($this->logPeristiwa);
                break;

            default:
                $data = [];
        }

        $lainnya = $this->getLainnya($this->logPeristiwa);

        return array_merge($data, $lainnya);
    }

    private function getKematian($peristiwa)
    {
        return [
            [
                'judul' => 'Hari Kematian',
                'isian' => getFormatIsian('Hari_kematiaN'),
                'data'  => hari($peristiwa->tgl_peristiwa),
            ],
            [
                'judul' => 'Tanggal Kematian',
                'isian' => getFormatIsian('Tanggal_kematiaN'),
                'data'  => tgl_indo($peristiwa->tgl_peristiwa),
            ],
            [
                'judul' => 'Jam Kematian',
                'isian' => getFormatIsian('Jam_kematiaN'),
                'data'  => $peristiwa->jam_mati,
            ],
            [
                'judul' => 'Tempat Kematian',
                'isian' => getFormatIsian('Tempat_kematiaN'),
                'data'  => $peristiwa->meninggal_di,
            ],
            [
                'judul' => 'Penyebab Kematian',
                'isian' => getFormatIsian('Penyebab_kematiaN'),
                'data'  => $peristiwa->penyebab_kematian,
            ],
            [
                'judul' => 'Penolong Kematian',
                'isian' => getFormatIsian('Penolong_kematiaN'),
                'data'  => $peristiwa->yang_menerangkan,
            ],
        ];
    }

    private function getHilang($peristiwa)
    {
        return [
            [
                'judul' => 'Hari Hilang',
                'isian' => getFormatIsian('Hari_hilanG'),
                'data'  => hari($peristiwa->tgl_peristiwa),
            ],
            [
                'judul' => 'Tanggal Hilang',
                'isian' => getFormatIsian('Tanggal_hilanG'),
                'data'  => tgl_indo($peristiwa->tgl_peristiwa),
            ],
        ];
    }

    private function getLainnya($peristiwa)
    {
        return [
            [
                'judul' => 'Tanggal Lapor',
                'isian' => getFormatIsian('Tanggal_lapoR'),
                'data'  => tgl_indo($peristiwa->tgl_lapor),
            ],
            [
                'judul' => 'Catatan',
                'isian' => getFormatIsian('CatataN'),
                'data'  => $peristiwa->catatan,
            ],
        ];
    }
}
