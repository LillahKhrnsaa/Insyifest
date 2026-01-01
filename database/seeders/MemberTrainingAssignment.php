<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Member;
use App\Models\Coach;

class MemberTrainingAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Penugasan
        // Key: Nama Lengkap Pelatih (Sesuai CoachSeeder)
        // Value: Array Nama Member (Sesuai MemberSeeder / users.sql)
        $assignments = [
            'Alif Ikrar Prabu' => [
                'Rakhshandrina kaysa S F', 'Mochammad Revansyah Egar Rifai', 'Bima Aditya', 'Muhamad Rizqi Pratama', 
                'Gifari Arkhan Fauzi', 'Naida Zulfa', 'Nikita Maesaroh', 'Abdulloh Ramadhan Wiksan', 
                'Anindya Putri Paradila', 'Aldiansyah Rizky Pratama', 'Milano Zhafran Ali Prihartanto', 'Nabil Payadh', 
                'Kanaya Archie Bintari', 'Muhammad Rasyid Anshori', 'Muhammad Ihsan Pratama', 'KEN RATU DIANETTA WIBOWO', 
                'Haidar Zhafran Abrisam', 'Alifya Fatihatun Maqqiya', 'Rafka Khairan Azzam', 'Ailee Gista Tama Widiyanto', 
                'Inoke Juin Re Shallomitha', 'Najwan mumtaz abdul majid', 'Azmi Rafardhan Athalla', 'AZKA SHIDQI ALFAKHRI', 
                'Rafif Ammar Irawan', 'AKMAL ATTAQI', 'Farannisa Rafani Royana', 'Ayu Farida Putri', 'Kaka Arsenio Setiako', 
                'Muhammad As\'ad Fadillah', 'ihram yazid athallah', 'M Hafidz Arfandi', 'Muhamad Riza Al hafidz'
            ],
            'Asri Suci Alfiani' => [
                'Ahnaf rizki al ghifary', 'Tristan Alvaro Pratama', 'Hayaka jagat prawira', 'Firaz arrazi seilaputra', 
                'M Hikari Alfatih', 'Kayla Azzahra', 'Ainun Umiati', 'Keyra Shauma', 'Fazia Khairisa Royana', 
                'Kenya Rejeki Rinonce', 'Talita Fayza Rahmillah', 'QUEENAYA AYU ANINDYA', 'HIRO MALIK ADHITAMA', 
                'Earlyta Arsyfa salsabila', 'Raisa Aura Dewi', 'Naila Dwi Putri'
            ],
            'Dony Adhi Nugroho Hidayat' => [
                'Faza Yusuf Permana', 'Muhammad Ali Zhafran', 'M rusmantyo algybran', 'Abrisam Fakih An-tsaqif', 
                'Uwais alqarni', 'Tanisha Dwi Aruna', 'Faizan Syahdan Alkhairi', 'SHOFA SITI NUR RIZKIYAH', 
                'Putri Yasmine Suderajat', 'Nashwa shaqueena shareen', 'Ahmad Luthfi Faruq Noor', 'adiba adzkiya supriyatna', 
                'Kenzie Ghaisan Rezqiano Susanto', 'Fatih rizky mubarak', 'Arsyila Putri Yevin', 'Alvin Al Akbar', 
                'Almahyra Shanum Qeerani', 'Shakila Maritsa Ayumi', 'Athazaky Abdillah Sarwono', 'AJWA SARMA ALMIRA', 
                'Talita Fayza Rahmillah', 'Raziq Hanan', 'Miftah Zain Al Kusufi', 'Azkadina Ayra N', 
                'Anindya nayara alesha putri', 'Azhira Agustin Wijaya', 'Fadhlan Haikal Rahmat', 'Novel', 
                'M zyandru firamansah', 'Aslan Bilal Abdullah', 'Muhammad Rafa Azka Putra', 'Kinza Ravindra', 
                'Tristan Alvaro Pratama', 'Tubagus Rassya Rafardhan', 'Ayunda Giska Al Khair', 'Arslan Al Muharram', 
                'Aerilyn Bellvania Cinta Kirana', 'Ibrahim Abdurrohman', 'Deanisa Kinanti Azahra', 'Muhammad At Tahrir', 
                'Ahmad Bayanaka Qamar', 'Sabiya Aruna Divyanisa', 'HABIBIE ASSYAWAL SEPTIADI', 'SHANA HAFIZAH WIGUNA', 
                'Nurul Aisyah', 'Abizar arfan raqila', 'Abian Agam Narendra', 'Ainayya aysha nada', 'Keanu Al Ghazali', 
                'Afifah Nur Khoirunnisa', 'Raniazahra Maritzha Alvyandri', 'Farzana Zia Azkayra', 'Gracia octaviona amadey', 
                'Anfari Ahmad Alfariji', 'SYAUQI AZKA RAYHAN SYAKEIL', 'Kalila aninditha aludra', 'YASMINE KIREINA FIRMANSYAH', 
                'ADIFA BUSSAINA MAHYA', 'Ghaizan Ahsan Fauzi', 'Anbiya Keisha', 'Aisyah Inara Amrullah', 
                'Andina Widya Ambarwati', 'Naira Azzahra Putri Maa\'ruf', 'Syifa alkhansa', 'M.Rakan Ataya Muammar', 
                'Kanaya Dwi Navisha', 'Shafna tanjilul ilma', 'Rai jiwa Firdaus putra Maa\'ruf', 'Uwais Al Qarni', 
                'Abqary Syauqi Awayya', 'Arshaka Fillio Abdillah', 'Bimasena ahmad arumi', 'Lamira Quitta', 
                'Athafariz malik', 'Rama Ikhsan', 'Arshaka Ikhsan', 'Fadlan Alkassalam', 'Fadli Munajat', 
                'Muhammad Fakhri Syafiq', 'Faizah Dhuha Salsabila', 'Aqila Azzahra', 'Adnan alfiansyah', 
                'Husna mubsira mamun', 'Hafizh Abdurrohman', 'Abdurrahman an naufal zaidan', 'Sabriel Rayyan Shiddiq', 
                'Raffasya Nabhan', 'Hasbialloh Almuzzammil'
            ],
            'Fabiyan Fahliyansyah' => [
                'Azfika Hafizha Rasyid', 'Zaumi Alka atthaya johari', 'Dhira Zania Rafanda', 'Muhammad Azka Henriansyah', 
                'Alifya Fatihatun Maqqiya', 'Rafisqy evano alfareza', 'Farrel Alifiandra', 'Muhamad azkha alsidqi', 
                'Alzaidan Syafiq'
            ],
            'Fauzan Noer Afrizal' => [
                'Ziya Diatmika Mysha', 'Shanaya Julie Praditha', 'Reysa Aulia', 'Mochammad fazry suryapermana', 
                'BIMA BAHIR SETIAWAN', 'Allykha Nauvalyn Nurkhairia', 'Muhammad Radja Darmawan', 'Muhammad Mirza Ukail', 
                'Ghazwan Solehwidadi', 'Fabian omar sasongko', 'Amalia Husna Aisha'
            ],
            'Endah Khairun Nissa' => [
                'Nashmia Hanina Rahmah', 'Shakila Rubina', 'Azalea khaliqa dzahin', 'Granada nayyer cordova', 
                'Linggar Rafisqi Nurdiana', 'Marsya Amelia Adeva', 'Hanum Adhwa', 'Abdurrahman Al Albani'
            ],
            'Moh Lutfi Adistira Wirawan' => [
                'Nizzar Adriansyah Pradita', 'Kalila aninditha aludra', 'ADIFA BUSSAINA MAHYA', 'Ghaizan Ahsan Fauzi', 
                'YASMINE KIREINA FIRMANSYAH', 'SYAUQI AZKA RAYHAN SYAKEIL', 'Bima Aditya', 'Abdulloh Ramadhan Wiksan', 
                'Anfari Ahmad Alfariji', 'Aldiansyah Rizky Pratama', 'Milano Zhafran Ali Prihartanto', 'Kanaya Archie Bintari', 
                'Muhammad Rasyid Anshori', 'Nabil Payadh', 'Muhammad At Tahrir', 'Muhammad Ar-Rasyid Mudzaffar', 
                'Juna Ilmi arrizki', 'Rakhshandrina kaysa S F', 'Nawal nufus al-mumtazah', 'Farzana Zia Azkayra', 
                'Anbiya Keisha', 'Ratu Fakhira Qotrunada'
            ],
            'Mohammad Hafid Siddik' => [
                'Artarindra Aqmar Nhauffal Shidqi', 'Ibrahim Hanif', 'Arsyad Haidar Farizi', 'Raffaza Azizul Khakim', 
                'Aida Alfika Putri', 'Shazia Nadhira Labibah', 'Adnan Faiz', 'Abidzar Arfan', 'Safa Shabrina', 
                'Trafagar Umar', 'Jordan Benedict', 'Ashila Faizah', 'Muhammad Ramadhan Alfarizy', 'Arsyad Ghatan', 
                'Bilal Sayudha', 'Gamma Abimanyu', 'Muhammad Ikram Al Fatih Imanullah', 'Chika Khaira'
            ],
            'Salsa Ramdiyani Eki Putri' => [
                'Rania Safiyya Irawan', 'Kanaya Alifa Husna', 'Muhammad Ar-Rasyid Mudzaffar', 'M.Rakan Ataya Muammar', 
                'Fatimah Aulia Azzahra', 'Shazia Belva Keinarra', 'Ayesha naziya putri', 'Arsyad Fachry Setiawan', 
                'Keenan Alghaizan Hamdani', 'Nazila Ghaizka putri', 'Nadhira Salsabila Qurratu’ain', 'Bentar Hikari', 
                'LAILATUL NUHA ZAHIRA', 'Muhammad Ikram Al Fatih Imanullah', 'Granada nayyer cordova', 
                'Andina Widya Ambarwati', 'Azka Hafizh Zaidan', 'Aksara nakhla kelana'
            ],
            'Iman Fala Handoko' => [
                'Muhammad Gavin Krisnadi', 'Ghazi Hamizan', 'hambia andasta', 'Delia Ulfa', 'Nathania Nesha', 
                'Arshaka Virendra', 'Raden Alfariel ihsan hidayat (fariel)', 'R Muhammad Ammar Rafasya', 
                'Muhammad Ramadhan Alfarizy', 'Husna Zayyanah Dzatil Izzah', 'Syafiq Hamid Al Muhsin', 
                'Akhtar Ilmannino Aditama', 'Gisakti Almizan', 'Almahyra Mecca Nur Ardiyanto', 'Shanum Alnaira Nur Rahman', 
                'Destama Aldizar Putra', 'Fajri Soraya', 'Nabilu Rahman', 'Shareefa azkadina Almahyra', 
                'Aqsa Alycio Gavin Alvaro', 'Abyan Naufal', 'Hasbialloh Almuzzammil'
            ],
            'Juan Njawi Wandhira' => [
                'Ananda putri shabira', 'Uwais alqarni', 'Syifa alkhansa', 'Laila marsya ramadani', 'NICO NUR RAMADHAN', 
                'MUHAMMAD FAHRI HUSAINI', 'NAFIZA HUMAIRA YASMIN', 'Anisa Adinda Putri', 'TASYA SHAKILA ALMAHYRA', 
                'Adzkiya Maulida Asfa', 'Haufan Hazza Alfatih', 'Alesha Alfathunnisa Azzahra', 'Shania Samara', 
                'Tristan Alvaro Pratama', 'Kinza Ravindra', 'Muhammad rafif sya’bani saky', 'Abira Damar Yudhistira', 
                'Balqis Taqiya Sayida', 'Raffi ahza al mubarroq', 'Arsy Zhafira Rafani', 'AZIZA ASSYABIA NURRAFIFA SAKY'
            ],
            'Rindy Antika' => [
                'Jennaira Maheswari Shafanah', 'Assyifa Bilqish Ashalina', 'Muthia Nur Khairunnisa', 
                'Adifa Nazla Wulandhany', 'Khaylila Aluna Maulidya', 'Aqila Lulu', 'Syafiah Fathurrohmah', 'Alula Qalbi'
            ],
            'Muhammad Tegar Satrio' => [
                'Gavin Elano Prasetyo', 'RAJENDRA ABID ZANATAN', 'Hanif Althaf Raffasya Muzakki', 
                'Meilia Amandita Mulyana', 'M. Shabri Alvian', 'Reno Aprilio', 'M Fathir'
            ]
        ];

        // Eksekusi Seeder
        foreach ($assignments as $coachName => $memberNames) {
            
            // 1. Cari ID Pelatih (berdasarkan Nama Lengkap di User)
            $coachUser = User::where('name', $coachName)->whereHas('roles', function($q){
                $q->where('name', 'coach');
            })->first();

            // Jika pelatih tidak ditemukan di DB, skip
            if (!$coachUser) continue;

            $coach = Coach::where('user_id', $coachUser->id)->first();
            if (!$coach) continue;

            // 2. Loop setiap member
            foreach ($memberNames as $memberName) {
                
                // Cari User Member berdasarkan Nama
                // Pakai LIKE agar tidak error jika ada perbedaan spasi kecil
                $memberUser = User::where('name', 'LIKE', $memberName)->whereHas('roles', function($q){
                    $q->where('name', 'member');
                })->first();

                if ($memberUser) {
                    $member = Member::where('user_id', $memberUser->id)->first();

                    if ($member) {
                        // 3. Simpan ke tabel member_training_assignments
                        DB::table('member_training_assignments')->updateOrInsert(
                            [
                                'member_id' => $member->id,
                                'coach_id'  => $coach->id,
                            ],
                            [
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                    }
                }
            }
        }
    }
}