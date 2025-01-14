<?php

namespace Database\Seeders;

use App\Models\Questionnaire;
use App\Models\Section;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class QuestionnaireSeeder extends Seeder
{
    public function run(): void
    {
        // Create Questionnaire
        $questionnaire = Questionnaire::create([
            'title' => 'KUESIONER STIE EKUITAS',
            'description' => 'Kuesioner ini bersifat rahasia dan semata-mata untuk memberi masukan terhadap pengembangan kualitas STIE Ekuitas dalam menuju Universitas Ekuitas Indonesia',
            'is_active' => true,
            'starts_at' => Carbon::now(),
            'ends_at' => Carbon::now()->addMonth(),
        ]);

        // Section 1: Identitas Program
        $section1 = Section::create([
            'questionnaire_id' => $questionnaire->id,
            'title' => 'Identitas Program',
            'order' => 1,
        ]);

        Question::create([
            'section_id' => $section1->id,
            'title' => 'Program',
            'type' => 'radio',
            'options' => [
                'Diploma III',
                'Sarjana',
                'Magister',
            ],
            'is_required' => true,
            'order' => 1,
        ]);

        Question::create([
            'section_id' => $section1->id,
            'title' => 'Program Studi',
            'type' => 'radio',
            'options' => [
                'Manajemen',
                'Akuntansi',
                'Bisnis Digital',
                'Perbankan dan Keuangan',
            ],
            'is_required' => true,
            'order' => 2,
        ]);

        // Section 2: Fasilitas & Lingkungan
        $section2 = Section::create([
            'questionnaire_id' => $questionnaire->id,
            'title' => 'Fasilitas & Lingkungan',
            'order' => 2,
        ]);

        $facilityQuestions = [
            'Bagaimana penilaian Anda terhadap kondisi keamanan dan kebersihan lingkungan di STIE Ekuitas ?',
            'Apakah fasilitas ruang kuliah, jaringan internet dan Wi-Fi di STIE Ekuitas cukup memadai untuk menunjang proses pembelajaran ?',
            'Bagaimana penilaian Anda terhadap fasilitas kantin, fasilitas kegiatan olahraga, dan fasilitas kegiatan kesenian mahasiswa ?',
            'Bagaimana penilaian Anda terhadap kondisi fasilitas parkir (sepeda motor, mobil, dll) dan toilet di STIE Ekuitas ?',
            'Bagaimana penilaian Anda terhadap area hijau (taman, ruang terbuka) di STIE Ekuitas ?',
        ];

        foreach ($facilityQuestions as $index => $question) {
            Question::create([
                'section_id' => $section2->id,
                'title' => $question,
                'type' => 'radio',
                'options' => $index === 1 ? [
                    'Tidak Memadai',
                    'Kurang Memadai',
                    'Memadai',
                    'Sangat Memadai',
                ] : [
                    'Sangat Buruk',
                    'Buruk',
                    'Cukup Baik',
                    'Baik',
                    'Sangat Baik',
                ],
                'is_required' => true,
                'order' => $index + 1,
            ]);
        }

        // Section 3: Dosen & Pembelajaran
        $section3 = Section::create([
            'questionnaire_id' => $questionnaire->id,
            'title' => 'Dosen & Pembelajaran',
            'order' => 3,
        ]);

        $teachingQuestions = [
            [
                'title' => 'Bagaimana kapasitas kompetensi dosen dalam menyampaikan materi perkuliahan ?',
                'options' => ['Sangat Buruk', 'Buruk', 'Cukup Baik', 'Baik', 'Sangat Baik'],
            ],
            [
                'title' => 'Bagaimana cara dosen berinteraksi dengan mahasiswa selama pembelajaran (incl. pengajaran, bimbingan tugas akhir/skripsi/tesis) ?',
                'options' => ['Sangat Buruk', 'Buruk', 'Cukup Baik', 'Baik', 'Sangat Baik'],
            ],
            [
                'title' => 'Bagaimana penilaian Anda terhadap sikap dosen dalam mengelola kelas? (misalnya: ketepatan waktu, disiplin, ketegasan, dan keteraturan)',
                'options' => ['Sangat Buruk', 'Buruk', 'Cukup Baik', 'Baik', 'Sangat Baik'],
            ],
            [
                'title' => 'Bagaimana dosen dalam memberikan penilaian terhadap mahasiswa (misalnya: nilai ujian, tugas, dan partisipasi) ?',
                'options' => ['Sangat Tidak Adil', 'Tidak Adil', 'Cukup Adil', 'Adil', 'Sangat Adil'],
            ],
            [
                'title' => 'Apakah pernah mengalami/mengetahui dosen meminta sesuatu hadiah dari mahasiswa ?',
                'options' => ['Sering Sekali', 'Sering', 'Tidak Tahu', 'Pernah', 'Tidak Pernah'],
            ],
        ];

        foreach ($teachingQuestions as $index => $question) {
            Question::create([
                'section_id' => $section3->id,
                'title' => $question['title'],
                'type' => 'radio',
                'options' => $question['options'],
                'is_required' => true,
                'order' => $index + 1,
            ]);
        }

        // Section 4: Layanan & Sistem Informasi
        $section4 = Section::create([
            'questionnaire_id' => $questionnaire->id,
            'title' => 'Layanan & Sistem Informasi',
            'order' => 4,
        ]);

        $serviceQuestions = [
            'Bagaimana penilaian Anda terhadap pelayanan petugas administrasi yang berhubungan dengan kegiatan akademik (jadwal kuliah, cuti, proses pemilihan mata kuliah / KRS, konsultasi akademik, transkrip nilai, dll) ?',
            'Bagaimana penilaian Anda terhadap pelayanan petugas administrasi yang berhubungan dengan informasi Uang Kuliah maupun kelancaran proses pembayaran uang kuliah ?',
            'Bagaimana kualitas maupun kemudahan penggunaan layanan Sistem informasi yang ada di STIE Ekuitas (Sistem Informasi akademik, Keuangan maupun sistem informasi lainnya) ?',
            'Bagaimana pendapat Anda mengenai kualitas informasi STIE Ekuitas pada website maupun media sosial lainnya ?',
            'Bagaimana kualitas layanan/dukungan teknis (misalnya: helpdesk atau petugas teknis) ketika Anda mengalami masalah dengan sistem akademik maupun sistem yang ada lainnya ?',
        ];

        foreach ($serviceQuestions as $index => $question) {
            Question::create([
                'section_id' => $section4->id,
                'title' => $question,
                'type' => 'radio',
                'options' => [
                    'Sangat Buruk',
                    'Buruk',
                    'Cukup Baik',
                    'Baik',
                    'Sangat Baik',
                ],
                'is_required' => true,
                'order' => $index + 1,
            ]);
        }

        // Section 5: Saran & Masukan
        $section5 = Section::create([
            'questionnaire_id' => $questionnaire->id,
            'title' => 'Saran & Masukan',
            'order' => 5,
        ]);

        Question::create([
            'section_id' => $section5->id,
            'title' => 'Saran dan Masukan untuk STIE Ekuitas',
            'type' => 'textarea',
            'is_required' => false,
            'order' => 1,
        ]);
    }
}
