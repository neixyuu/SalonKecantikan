<?php

namespace Database\Seeders;

use App\Models\Treatment;
use Illuminate\Database\Seeder;

class TreatmentSeeder extends Seeder
{
    public function run(): void
    {
        $treatments = [
            [
                'name'        => 'Facial Brightening',
                'description' => 'Perawatan wajah intensif untuk mencerahkan dan meratakan warna kulit. Menggunakan bahan-bahan alami premium yang menutrisi dan meremajakan kulit wajah Anda.',
                'price'       => 250000,
                'duration'    => '75 menit',
                'image'       => null,
            ],
            [
                'name'        => 'Deep Cleansing Facial',
                'description' => 'Membersihkan pori-pori secara mendalam untuk menghilangkan komedo, kotoran, dan sel kulit mati. Cocok untuk kulit bermasalah dan berminyak.',
                'price'       => 200000,
                'duration'    => '60 menit',
                'image'       => null,
            ],
            [
                'name'        => 'Body Massage Relaksasi',
                'description' => 'Pijat seluruh tubuh dengan teknik Swedish massage untuk menghilangkan stres dan ketegangan otot. Menggunakan aromaterapi dan minyak esensial pilihan.',
                'price'       => 350000,
                'duration'    => '90 menit',
                'image'       => null,
            ],
            [
                'name'        => 'Hair Spa Treatment',
                'description' => 'Perawatan rambut lengkap mulai dari creambath, hair mask, hingga blow dry. Rambut menjadi lebih sehat, berkilau, dan mudah diatur.',
                'price'       => 180000,
                'duration'    => '60 menit',
                'image'       => null,
            ],
            [
                'name'        => 'Manikur & Pedikur',
                'description' => 'Perawatan kuku tangan dan kaki yang lengkap termasuk pembersihan, pemangkasan, penghalusan, dan pewarnaan kuku dengan cat kuku pilihan.',
                'price'       => 150000,
                'duration'    => '60 menit',
                'image'       => null,
            ],
            [
                'name'        => 'Anti-Aging Facial',
                'description' => 'Perawatan wajah premium dengan teknologi terkini untuk mengurangi kerutan, mengencangkan kulit, dan mengembalikan elastisitas kulit secara alami.',
                'price'       => 450000,
                'duration'    => '90 menit',
                'image'       => null,
            ],
        ];

        foreach ($treatments as $treatment) {
            Treatment::create($treatment);
        }
    }
}
