<?php

namespace Database\Seeders;

use App\Models\Artikel;
use App\Models\Kuis;
use Illuminate\Database\Seeder;

class GreenAcademySeeder extends Seeder
{
    public function run(): void
    {
        $materials = [
            [
                'title' => 'Dasar Pengelolaan Sampah',
                'content' => "Pengantar\nSetiap hari kita menghasilkan sampah dari aktivitas yang terlihat biasa: makan, berbelanja, membersihkan rumah, atau membuka kemasan produk. Masalahnya, sampah tidak selesai ketika dibuang. Jika tidak dikelola dengan benar, sampah dapat menumpuk, mencemari lingkungan, mengganggu kesehatan, dan menambah beban tempat pembuangan akhir. Karena itu, pengelolaan sampah perlu dipahami sebagai bagian dari kebiasaan hidup yang bertanggung jawab, bukan sekadar rutinitas rumah tangga.\n\nMemahami Jenis Sampah\nLangkah pertama dalam pengelolaan sampah adalah mengenali jenisnya. Sampah organik meliputi sisa makanan, daun, kulit buah, dan bahan lain yang mudah terurai secara alami. Sampah ini dapat diolah menjadi kompos atau pupuk. Sementara itu, sampah anorganik seperti plastik, kaca, logam, dan kemasan multilapis memerlukan penanganan khusus karena tidak cepat terurai. Ada pula sampah residu, yaitu sampah yang sulit dimanfaatkan kembali, seperti tisu kotor atau popok sekali pakai, serta sampah berbahaya seperti baterai dan lampu yang tidak boleh dibuang sembarangan.\n\nMengapa Pemilahan Sangat Penting?\nPemilahan sampah sejak dari sumber adalah kunci utama. Ketika sampah bercampur, bahan yang sebenarnya masih dapat dimanfaatkan menjadi kotor dan sulit diproses ulang. Sampah organik yang tercampur dengan plastik akan membusuk dan menimbulkan bau, sementara sampah anorganik kehilangan nilai daur ulangnya jika terkontaminasi. Pemilahan membantu memperpanjang umur tempat pembuangan akhir, menekan pencemaran, dan membuka peluang pengolahan yang lebih efisien.\n\nDampak Nyata Jika Sampah Tidak Dikelola\nSampah yang dibiarkan menumpuk dapat menyumbat saluran air, memperbesar risiko banjir, dan menjadi sumber penyakit. Pembakaran sampah sembarangan melepaskan polutan berbahaya ke udara. Di sisi lain, sampah yang dibuang ke sungai atau lahan kosong dapat merusak kualitas tanah dan air. Artinya, pengelolaan sampah tidak hanya memengaruhi kebersihan lingkungan, tetapi juga berkaitan langsung dengan kesehatan masyarakat dan kualitas hidup sehari-hari.\n\nStrategi Pengelolaan yang Efektif\nPengelolaan sampah yang baik mengikuti prinsip mengurangi, menggunakan kembali, dan mendaur ulang. Kurangi pembelian produk yang menghasilkan sampah berlebih. Gunakan kembali wadah atau kemasan yang masih layak pakai. Pisahkan sampah yang dapat didaur ulang agar tidak tercampur dengan limbah lain. Untuk sampah organik, pengomposan adalah langkah yang sangat efektif karena mengubah limbah rumah tangga menjadi sumber daya yang bermanfaat.\n\nContoh Penerapan dalam Kehidupan Sehari-hari\nDi rumah, sediakan minimal tiga wadah: organik, anorganik, dan residu. Biasakan membilas botol atau kemasan sebelum disimpan untuk daur ulang. Simpan minyak jelantah, baterai, dan lampu bekas secara terpisah agar tidak mencemari sampah lain. Di sekolah atau tempat kerja, kebiasaan sederhana seperti membawa botol minum sendiri, mengurangi tisu sekali pakai, dan memilah sampah setelah makan dapat memberi dampak yang nyata jika dilakukan bersama-sama.\n\nRefleksi\nPengelolaan sampah yang baik bukan hanya soal membuang pada tempatnya, melainkan soal memahami konsekuensi dari apa yang kita konsumsi. Semakin sadar kita terhadap sampah yang dihasilkan, semakin mudah kita membentuk kebiasaan yang lebih hemat, bersih, dan bertanggung jawab.\n\nPesan Utama\nPerubahan besar dalam pengelolaan sampah sering kali dimulai dari keputusan-keputusan kecil yang dilakukan secara konsisten. Memilah, mengurangi, dan mengelola sampah dengan benar adalah bentuk kepedulian yang sederhana, tetapi sangat penting untuk menjaga lingkungan tetap sehat dan berkelanjutan.",
                'points' => 20,
                'duration' => '5 menit',
                'quiz_title' => 'Kuis: Dasar Pengelolaan Sampah',
                'questions' => [
                    [
                        'question' => 'Sebuah rumah tangga ingin mulai memilah sampah. Kelompok mana yang paling tepat dikategorikan sebagai sampah organik?',
                        'options' => ['Botol kaca, kaleng, dan plastik bening', 'Sisa makanan, daun kering, dan kulit buah', 'Tisu kotor, popok, dan baterai bekas', 'Kabel, lampu, dan kemasan deterjen'],
                        'answer' => 'Sisa makanan, daun kering, dan kulit buah',
                    ],
                    [
                        'question' => 'Mengapa botol plastik bekas minuman sebaiknya dibersihkan sebelum dimasukkan ke wadah daur ulang?',
                        'options' => ['Agar botol menjadi lebih berat', 'Agar tidak tercampur dengan sampah organik dan tetap layak diproses', 'Agar cepat terurai secara alami', 'Karena semua sampah harus dicuci terlebih dahulu'],
                        'answer' => 'Agar tidak tercampur dengan sampah organik dan tetap layak diproses',
                    ],
                    [
                        'question' => 'Jika sampah organik dan anorganik terus dicampur dalam satu tempat, risiko yang paling mungkin terjadi adalah...',
                        'options' => ['Proses daur ulang menjadi lebih mudah', 'Sampah lebih cepat habis dengan sendirinya', 'Bahan yang masih bisa dimanfaatkan menjadi tercemar dan sulit diolah', 'Semua jenis sampah akan otomatis berubah menjadi kompos'],
                        'answer' => 'Bahan yang masih bisa dimanfaatkan menjadi tercemar dan sulit diolah',
                    ],
                    [
                        'question' => 'Manakah tindakan yang paling sesuai dengan prinsip mengurangi sampah dari sumbernya?',
                        'options' => ['Membuang semua kemasan ke satu tempat besar', 'Membeli produk sekali pakai karena lebih praktis', 'Memilih produk isi ulang dan membawa wadah pakai ulang', 'Membakar sampah rumah tangga setiap akhir pekan'],
                        'answer' => 'Memilih produk isi ulang dan membawa wadah pakai ulang',
                    ],
                    [
                        'question' => 'Apa alasan utama pengelolaan sampah perlu dilihat sebagai bagian dari gaya hidup, bukan sekadar urusan kebersihan?',
                        'options' => ['Karena hanya petugas kebersihan yang bertanggung jawab', 'Karena sampah berkaitan dengan pola konsumsi, kesehatan, dan keberlanjutan lingkungan', 'Karena sampah akan hilang sendiri jika dibiarkan lama', 'Karena semua jenis sampah dapat dibuang ke sungai'],
                        'answer' => 'Karena sampah berkaitan dengan pola konsumsi, kesehatan, dan keberlanjutan lingkungan',
                    ],
                ],
            ],
            [
                'title' => 'Dampak Plastik Sekali Pakai',
                'content' => "Pengantar\nPlastik sekali pakai tampak praktis karena murah, ringan, dan mudah didapat. Namun, justru karena sifat itulah penggunaannya menjadi sangat masif dan sulit dikendalikan. Kantong belanja, kemasan makanan, sedotan, alat makan sekali pakai, dan botol minum sering hanya dipakai beberapa menit, tetapi meninggalkan dampak lingkungan yang berlangsung sangat lama.\n\nMengapa Plastik Sekali Pakai Menjadi Krisis?\nMasalah utama plastik sekali pakai adalah volumenya yang besar, umur pakainya yang sangat singkat, dan daya urainya yang sangat lambat. Sebagian besar plastik tidak benar-benar hilang, melainkan terpecah menjadi partikel yang lebih kecil. Partikel inilah yang dikenal sebagai mikroplastik. Mikroplastik dapat masuk ke sungai, laut, tanah, udara, dan pada akhirnya ke rantai makanan manusia.\n\nDampak terhadap Ekosistem\nDi lingkungan darat, sampah plastik dapat menyumbat drainase dan memperparah banjir. Di wilayah pesisir dan laut, plastik sering ditemukan di perut ikan, penyu, atau burung laut. Hewan-hewan ini tidak dapat membedakan plastik dengan makanan. Selain membahayakan satwa, pencemaran plastik juga merusak kualitas habitat dan menurunkan daya dukung ekosistem.\n\nDampak terhadap Manusia dan Sistem Sosial\nMasalah plastik juga memiliki dimensi kesehatan dan ekonomi. Mikroplastik yang masuk ke air dan makanan berpotensi berdampak pada tubuh manusia. Di sisi lain, biaya pembersihan sampah plastik di kota, sungai, dan pesisir sangat besar. Artinya, kebiasaan praktis hari ini dapat menimbulkan beban kolektif yang jauh lebih mahal di masa depan.\n\nStrategi Pengurangan yang Relevan\nMengurangi plastik sekali pakai membutuhkan perubahan kebiasaan dan keputusan konsumsi. Membawa tas belanja, botol minum, dan wadah makan sendiri adalah langkah dasar. Menolak barang sekali pakai yang sebenarnya tidak dibutuhkan juga penting. Selain itu, memilih produk dengan kemasan lebih sederhana atau sistem isi ulang akan membantu mengurangi timbulan sampah dari sumbernya.\n\nContoh Situasi Sehari-hari\nSaat membeli minuman, memilih botol isi ulang jauh lebih baik daripada membeli botol sekali pakai setiap hari. Saat memesan makanan, menolak sendok, garpu, dan sedotan plastik jika tidak diperlukan juga merupakan keputusan yang berdampak. Perubahan seperti ini terlihat kecil, tetapi sangat berarti jika dilakukan berulang dan ditiru oleh banyak orang.\n\nRefleksi\nPersoalan plastik bukan semata masalah sampah, tetapi juga cerminan pola konsumsi yang terburu-buru dan tidak mempertimbangkan dampak jangka panjang. Karena itu, solusi terbaik tidak hanya terletak pada pembersihan, tetapi pada pengurangan sejak awal.\n\nPesan Utama\nMengurangi plastik sekali pakai adalah bentuk tanggung jawab terhadap lingkungan, kesehatan, dan masa depan bersama. Pilihan yang terlihat sederhana hari ini dapat mencegah dampak yang jauh lebih besar esok hari.",
                'points' => 30,
                'duration' => '6 menit',
                'quiz_title' => 'Kuis: Dampak Plastik Sekali Pakai',
                'questions' => [
                    [
                        'question' => 'Apa alasan utama plastik sekali pakai menjadi persoalan lingkungan yang serius?',
                        'options' => ['Karena semua plastik langsung larut di air', 'Karena dipakai singkat tetapi bertahan sangat lama di lingkungan', 'Karena tidak pernah digunakan dalam kehidupan sehari-hari', 'Karena hanya ditemukan di wilayah industri'],
                        'answer' => 'Karena dipakai singkat tetapi bertahan sangat lama di lingkungan',
                    ],
                    [
                        'question' => 'Mengapa mikroplastik dianggap berbahaya, meskipun ukurannya sangat kecil?',
                        'options' => ['Karena mudah berubah menjadi oksigen', 'Karena dapat masuk ke tanah, air, dan rantai makanan', 'Karena hanya ada di laboratorium', 'Karena membuat plastik lebih cepat terurai'],
                        'answer' => 'Karena dapat masuk ke tanah, air, dan rantai makanan',
                    ],
                    [
                        'question' => 'Seorang siswa membeli minuman kemasan setiap hari dan langsung membuang botolnya. Pilihan perubahan yang paling berdampak adalah...',
                        'options' => ['Tetap membeli minuman kemasan, tetapi membuang di tempat sampah biasa', 'Beralih ke botol minum isi ulang dan mengisinya dari rumah', 'Mengganti botol plastik dengan sedotan plastik', 'Membakar botol plastik setelah digunakan'],
                        'answer' => 'Beralih ke botol minum isi ulang dan mengisinya dari rumah',
                    ],
                    [
                        'question' => 'Manakah contoh keputusan konsumsi yang paling relevan untuk mengurangi plastik dari sumbernya?',
                        'options' => ['Memilih produk dengan sistem isi ulang', 'Menambah lapisan plastik agar kemasan lebih aman', 'Membuang plastik ke saluran air saat hujan', 'Menggunakan plastik sekali pakai untuk semua kebutuhan'],
                        'answer' => 'Memilih produk dengan sistem isi ulang',
                    ],
                    [
                        'question' => 'Dampak plastik terhadap hewan laut yang paling sering terjadi adalah...',
                        'options' => ['Menjadi sumber nutrisi utama', 'Tertelan atau menjerat tubuh hewan', 'Membantu memperluas habitat alami', 'Meningkatkan kualitas air laut'],
                        'answer' => 'Tertelan atau menjerat tubuh hewan',
                    ],
                    [
                        'question' => 'Mengapa pengurangan plastik tidak cukup hanya mengandalkan aksi bersih-bersih?',
                        'options' => ['Karena plastik tidak bisa dikumpulkan sama sekali', 'Karena akar masalahnya juga ada pada pola konsumsi yang berlebihan', 'Karena sampah akan hilang sendiri jika dibiarkan', 'Karena semua plastik pasti terurai dalam beberapa hari'],
                        'answer' => 'Karena akar masalahnya juga ada pada pola konsumsi yang berlebihan',
                    ],
                ],
            ],
            [
                'title' => 'Perubahan Iklim 101',
                'content' => "Pengantar\nPerubahan iklim adalah perubahan jangka panjang pada suhu bumi dan pola cuaca global yang dipicu oleh meningkatnya konsentrasi gas rumah kaca di atmosfer. Ketika gas-gas seperti karbon dioksida dan metana menumpuk, panas dari matahari tertahan lebih lama di atmosfer. Akibatnya, suhu bumi meningkat dan sistem iklim menjadi tidak seimbang.\n\nPenyebab Utama\nSumber utama perubahan iklim saat ini berasal dari aktivitas manusia. Pembakaran bahan bakar fosil untuk listrik, transportasi, dan industri menghasilkan emisi besar. Deforestasi mengurangi kemampuan bumi menyerap karbon. Selain itu, pola konsumsi yang boros energi, limbah pangan, dan produksi massal yang tidak efisien ikut memperparah situasi.\n\nMengapa Ini Menjadi Isu Mendesak?\nPerubahan iklim bukan sekadar persoalan suhu yang naik beberapa derajat. Kenaikan suhu global berdampak pada sistem yang saling terhubung: cuaca, air, pangan, kesehatan, dan keanekaragaman hayati. Perubahan kecil pada temperatur rata-rata dapat memicu gelombang panas, banjir, kekeringan, hujan ekstrem, dan kebakaran hutan yang lebih sering dan lebih parah.\n\nDampak terhadap Kehidupan Sehari-hari\nBagi masyarakat, perubahan iklim dapat berarti musim tanam yang terganggu, harga pangan yang naik, krisis air bersih, kualitas udara yang memburuk, dan meningkatnya risiko penyakit. Di wilayah pesisir, kenaikan permukaan laut mengancam permukiman dan mata pencaharian. Di darat dan laut, ekosistem menjadi semakin rentan karena banyak spesies tidak mampu beradaptasi dengan perubahan yang terlalu cepat.\n\nUpaya Mitigasi yang Relevan\nMitigasi berarti mengurangi penyebab perubahan iklim. Langkah ini bisa dimulai dari penggunaan energi yang lebih efisien, transportasi yang lebih rendah emisi, pengurangan pemborosan makanan, pengelolaan sampah yang lebih baik, dan perlindungan hutan. Upaya ini juga membutuhkan perubahan pada level kebijakan, industri, dan infrastruktur, bukan hanya kebiasaan individu.\n\nUpaya Adaptasi yang Tidak Kalah Penting\nSelain mengurangi emisi, masyarakat juga perlu beradaptasi. Adaptasi meliputi pengelolaan air yang lebih baik, sistem peringatan dini bencana, ruang hijau di perkotaan, dan perencanaan pembangunan yang memperhatikan risiko iklim. Dengan kata lain, menghadapi perubahan iklim memerlukan kesiapan untuk mengurangi dampak sekaligus membangun ketahanan.\n\nRefleksi\nPerubahan iklim sering terasa terlalu besar untuk dihadapi, tetapi pemahaman yang tepat membantu kita melihat titik masuk yang nyata. Pilihan energi, cara bergerak, pola belanja, hingga kebijakan publik yang kita dukung semuanya berpengaruh. Isu ini bukan hanya tentang alam, melainkan tentang keadilan antargenerasi dan kualitas hidup manusia.\n\nPesan Utama\nPerubahan iklim adalah tantangan bersama yang menuntut kesadaran, ilmu, dan tindakan. Semakin cepat kita memahami persoalan ini, semakin besar peluang kita untuk melindungi lingkungan, mengurangi risiko, dan memastikan masa depan yang lebih aman.",
                'points' => 50,
                'duration' => '8 menit',
                'quiz_title' => 'Kuis: Perubahan Iklim 101',
                'questions' => [
                    [
                        'question' => 'Faktor manakah yang paling berkontribusi terhadap perubahan iklim global saat ini?',
                        'options' => ['Pembakaran bahan bakar fosil', 'Penggunaan kompos', 'Daur ulang kertas', 'Penanaman mangrove'],
                        'answer' => 'Pembakaran bahan bakar fosil',
                    ],
                    [
                        'question' => 'Mengapa deforestasi ikut memperparah perubahan iklim?',
                        'options' => ['Karena pohon menghasilkan plastik', 'Karena hutan mengurangi kemampuan bumi menyerap karbon', 'Karena semua hutan otomatis menjadi gurun', 'Karena penebangan membuat suhu laut turun'],
                        'answer' => 'Karena hutan mengurangi kemampuan bumi menyerap karbon',
                    ],
                    [
                        'question' => 'Manakah contoh dampak perubahan iklim yang paling relevan bagi kehidupan masyarakat sehari-hari?',
                        'options' => ['Musim lebih stabil setiap tahun', 'Cuaca ekstrem, gangguan pangan, dan risiko banjir meningkat', 'Permukaan laut selalu menurun', 'Suhu bumi kembali normal tanpa upaya apa pun'],
                        'answer' => 'Cuaca ekstrem, gangguan pangan, dan risiko banjir meningkat',
                    ],
                    [
                        'question' => 'Seorang warga ingin berkontribusi pada mitigasi perubahan iklim. Langkah mana yang paling tepat?',
                        'options' => ['Menghemat energi dan mengurangi penggunaan kendaraan bermotor jika ada alternatif', 'Membiarkan alat elektronik menyala terus-menerus', 'Membakar sampah plastik di halaman', 'Menebang pohon untuk memperluas lahan parkir'],
                        'answer' => 'Menghemat energi dan mengurangi penggunaan kendaraan bermotor jika ada alternatif',
                    ],
                    [
                        'question' => 'Perbedaan utama antara mitigasi dan adaptasi dalam isu perubahan iklim adalah...',
                        'options' => ['Mitigasi fokus mengurangi penyebab, adaptasi fokus menyesuaikan diri terhadap dampak', 'Mitigasi dan adaptasi adalah istilah yang sama', 'Mitigasi hanya untuk pemerintah, adaptasi hanya untuk siswa', 'Adaptasi bertujuan meningkatkan emisi, mitigasi mengabaikan dampak'],
                        'answer' => 'Mitigasi fokus mengurangi penyebab, adaptasi fokus menyesuaikan diri terhadap dampak',
                    ],
                    [
                        'question' => 'Mengapa perubahan iklim disebut sebagai isu keadilan antargenerasi?',
                        'options' => ['Karena hanya memengaruhi orang tua', 'Karena dampaknya akan dirasakan lebih berat oleh generasi yang datang kemudian jika tidak ditangani sekarang', 'Karena tidak berhubungan dengan masa depan', 'Karena hanya terjadi di negara tertentu'],
                        'answer' => 'Karena dampaknya akan dirasakan lebih berat oleh generasi yang datang kemudian jika tidak ditangani sekarang',
                    ],
                ],
            ],
        ];

        foreach ($materials as $material) {
            $artikel = Artikel::updateOrCreate(
                ['title' => $material['title']],
                [
                    'content' => $material['content'],
                    'points' => $material['points'],
                    'duration' => $material['duration'],
                ]
            );

            Kuis::updateOrCreate(
                ['artikel_id' => $artikel->id],
                [
                    'title' => $material['quiz_title'],
                    'questions' => $material['questions'],
                ]
            );
        }
    }
}
