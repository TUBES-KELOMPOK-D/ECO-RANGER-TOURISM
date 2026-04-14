<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Quiz;

class GreenAcademySeeder extends Seeder
{
    public function run(): void
    {
        // ─── Materi 1 ───────────────────────────────────────────────
        $m1 = Material::create([
            'title'       => 'Dasar Pengelolaan Sampah',
            'slug'        => 'dasar-pengelolaan-sampah',
            'description' => 'Pelajari cara memilah sampah organik dan anorganik.',
            'content'     => 'Sampah organik adalah sampah yang dapat terurai secara alami, '
                           . 'seperti sisa makanan dan dedaunan. Sampah anorganik adalah sampah '
                           . 'yang sulit terurai, seperti plastik, kaca, dan logam. '
                           . 'Memilah sampah dari rumah adalah langkah pertama untuk daur ulang yang efektif. '
                           . "\n\n"
                           . 'Dengan memilah sampah sejak dini, kita membantu mengurangi volume sampah '
                           . 'yang masuk ke Tempat Pembuangan Akhir (TPA) dan memperpanjang masa pakai TPA tersebut. '
                           . 'Sampah organik dapat diolah menjadi kompos yang bermanfaat bagi tanaman, '
                           . 'sedangkan sampah anorganik seperti kertas, plastik, dan logam dapat didaur ulang '
                           . 'menjadi produk baru.',
            'points'      => 20,
        ]);

        Quiz::create([
            'material_id'    => $m1->id,
            'question'       => 'Manakah yang termasuk sampah organik?',
            'option_a'       => 'Plastik',
            'option_b'       => 'Kaca',
            'option_c'       => 'Sisa Makanan',
            'option_d'       => 'Besi',
            'correct_answer' => 'c',
        ]);

        // ─── Materi 2 ───────────────────────────────────────────────
        $m2 = Material::create([
            'title'       => 'Dampak Plastik Sekali Pakai',
            'slug'        => 'dampak-plastik-sekali-pakai',
            'description' => 'Mengapa kita harus mengurangi penggunaan plastik sekali pakai?',
            'content'     => 'Plastik sekali pakai seperti sedotan, kantong belanja, dan botol minum '
                           . 'merupakan salah satu penyebab utama pencemaran lingkungan. Plastik membutuhkan '
                           . 'waktu hingga ratusan tahun untuk terurai dan seringkali berakhir di lautan, '
                           . 'mengancam kehidupan biota laut.'
                           . "\n\n"
                           . 'Setiap tahun, diperkirakan 8 juta ton plastik masuk ke lautan. '
                           . 'Mikroplastik yang berasal dari pecahan plastik bahkan telah ditemukan dalam tubuh '
                           . 'ikan, hewan laut, dan bahkan dalam air minum manusia. '
                           . 'Solusinya adalah beralih ke produk ramah lingkungan: gunakan tas kain, '
                           . 'botol minum isi ulang, dan sedotan bambu atau stainless.',
            'points'      => 30,
        ]);

        Quiz::create([
            'material_id'    => $m2->id,
            'question'       => 'Berapa lama plastik biasa membutuhkan waktu untuk terurai di alam?',
            'option_a'       => '10 tahun',
            'option_b'       => '50 tahun',
            'option_c'       => '100–450 tahun',
            'option_d'       => '2 tahun',
            'correct_answer' => 'c',
        ]);

        // ─── Materi 3 ───────────────────────────────────────────────
        $m3 = Material::create([
            'title'       => 'Perubahan Iklim 101',
            'slug'        => 'perubahan-iklim-101',
            'description' => 'Memahami penyebab dan dampak perubahan iklim global.',
            'content'     => 'Perubahan iklim adalah perubahan jangka panjang pada suhu dan pola cuaca bumi. '
                           . 'Penyebab utamanya adalah emisi gas rumah kaca (GRK) seperti karbon dioksida (CO₂) '
                           . 'dan metana (CH₄) yang berasal dari pembakaran bahan bakar fosil, deforestasi, '
                           . 'dan aktivitas industri.'
                           . "\n\n"
                           . 'Dampak perubahan iklim mencakup kenaikan permukaan laut, cuaca ekstrem yang '
                           . 'semakin sering terjadi, kekeringan, banjir, dan punahnya berbagai spesies. '
                           . 'Kita semua dapat berkontribusi untuk memitigasi perubahan iklim dengan mengurangi '
                           . 'konsumsi energi, beralih ke energi terbarukan, menanam pohon, dan mendukung '
                           . 'kebijakan lingkungan yang berkelanjutan.',
            'points'      => 50,
        ]);

        Quiz::create([
            'material_id'    => $m3->id,
            'question'       => 'Gas rumah kaca utama yang menyebabkan perubahan iklim adalah?',
            'option_a'       => 'Oksigen (O₂)',
            'option_b'       => 'Nitrogen (N₂)',
            'option_c'       => 'Karbon Dioksida (CO₂)',
            'option_d'       => 'Hidrogen (H₂)',
            'correct_answer' => 'c',
        ]);
    }
}
