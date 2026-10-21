<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficialDestinationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds for Kombolcha's official tourism & economic landmarks.
     */
    public function run(): void
    {
        DB::table('destinations')->delete();

        $destinations = [
            [
                'id' => 1,
                'category' => 'nature',
                'title_am' => 'የጎፍ ጥብቅ የተፈጥሮ ደን',
                'title_en' => 'Yegof State Protected Natural Forest Reserve',
                'title_om' => 'Bosona Eegamaa Gaara Yagof',
                'location_am' => 'የጎፍ ተራራ፣ ቦርከና ክፍለ ከተማ',
                'location_en' => 'Mount Yegof, Borkena Sub-City',
                'location_om' => 'Gaara Yagof, Kutaa Magaalaa Borkanaa',
                'summary_am' => 'ከባህር ጠለል በላይ እስከ 2,700 ሜትር ከፍታ ላይ የሚገኝ፣ ብዝሃ ህይወት፣ ጥንታዊ የጥድና ዝግባ ዛፎች እንዲሁም አስደናቂ የአእዋፍ ዝርያዎች መገኛ የሆነ የከተማዋ የተፈጥሮ ሳንባና የቱሪዝም መዳረሻ።',
                'summary_en' => 'Towering up to 2,700 meters above sea level, Yegof is a lush afro-montane forest sanctuary boasting native juniper, olive stands, diverse birdlife, and panoramic vistas overlooking the Kombolcha valley.',
                'summary_om' => 'Bosona uumamaa olka’iinsa meetira 2,700 irratti argamu, lubbu-qabeeyyii fi simbirroota hedduu of keessaa qabu.',
                'image_path' => '/assets/yegof_forest.jpg',
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'category' => 'nature',
                'title_am' => 'ሰናይት ግሪን መስክና መናፈሻ',
                'title_en' => 'Senait Green Recreational Eco-Park',
                'title_om' => 'Maddi Magariisaa fi Boqonnaa Sanaayit',
                'location_am' => 'ኮምቦልቻ ከተማ አስተዳደር',
                'location_en' => 'Central Kombolcha Corridor',
                'location_om' => 'Giddu-galeessa Magaalaa Kombolchaa',
                'summary_am' => 'አረንጓዴ የተፈጥሮ መስክ፣ ንጹህ አየርና ለቤተሰብ እረፍት፣ ለፎቶግራፍና ለመዝናናት ምቹ የሆነ የከተማዋ ተወዳጅ መናፈሻ።',
                'summary_en' => 'A serene urban eco-park and expansive meadow offering verdant picnic grounds, scenic photography spots, and pristine open-air community recreation.',
                'summary_om' => 'Bakka bashannanaa magariisaa fi qilleensa qulqulluu qabu.',
                'image_path' => '/assets/senait_green.jpg',
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'category' => 'culture',
                'title_am' => 'ልጎ ደብረ ገነት ቅዱስ ጊዮርጊስ አንድነት ገዳም',
                'title_en' => 'Ligo Debre Genet St. George Unity Monastery',
                'title_om' => 'Gadaamii Qulqulluu Giyoorgis Liigoo',
                'location_am' => 'ልጎ፣ ኮምቦልቻ ዙሪያ',
                'location_en' => 'Ligo Ridge, Greater Kombolcha',
                'location_om' => 'Liigoo, Naannawa Kombolchaa',
                'summary_am' => 'ዘመናትን ያስቆጠረ ጥንታዊ የአንድነት ገዳም፤ የሃይማኖታዊ ትውፊት፣ የብራና መጽሐፍትና የሰላምና እርቅ መንፈሳዊ ማዕከል የሆነ ታሪካዊ መዳረሻ።',
                'summary_en' => 'A revered historical Ethiopian Orthodox monastic sanctuary renowned for centuries of sacred spiritual discipline, ancient parchment manuscripts, and profound peace heritage.',
                'summary_om' => 'Gadaamii seena-qabeessa fi wiirtuu aadaa fi amantaa.',
                'image_path' => '/assets/ligo_monastery.jpg',
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'category' => 'culture',
                'title_am' => 'ገደራ ሐዋርያው ቅዱስ ቶማስ ታሪካዊ ቤተክርስቲያን',
                'title_en' => 'Gedera Apostles St. Thomas Historic Church',
                'title_om' => 'Mana Sagadaa Qulqulluu Toomaas Gadarā',
                'location_am' => 'ገደራ፣ ኮምቦልቻ',
                'location_en' => 'Gedera District, Kombolcha',
                'location_om' => 'Gadarā, Kombolchaa',
                'summary_am' => 'በቅዱስ ቶማስ ስም የተሰየመ፣ ጥንታዊ ስነ-ህንፃና ታሪካዊ ቅርሶችን የያዘ የሃይማኖት ቱሪዝም መዳረሻ።',
                'summary_en' => 'An iconic stone ecclesiastical sanctuary dedicated to St. Thomas the Apostle, celebrated for its unique local architectural craftsmanship and heritage festivals.',
                'summary_om' => 'Mana amantaa seenaa fi aadaa gabbataa qabu.',
                'image_path' => '/assets/gedera_church.jpg',
                'order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'category' => 'culture',
                'title_am' => 'ዛውዮች ታሪካዊ የእምነት ቦታና የእስልምና ትምህርት ማዕከላት',
                'title_en' => 'Zawya Historical Islamic Shrines & Quranic Academy',
                'title_om' => 'Wiirtuu Seena-qabeessa Zaawiyaa fi Mana Barumsaa Islaamaa',
                'location_am' => 'ኮምቦልቻና አካባቢው',
                'location_en' => 'Historic Kombolcha Spiritual Corridor',
                'location_om' => 'Naannawa Seenaa Kombolchaa',
                'summary_am' => 'ለዘመናት በወሎ ምድር የእስልምና እውቀት፣ የቁርአን ትምህርት፣ የመቻቻልና የፍቅር ማዕከል ሆነው ያገለገሉ ጥንታዊ ዛውዮችና መጅቶች።',
                'summary_en' => 'Renowned centuries-old Sufi zawiyas and Islamic scholarship academies revered throughout Wollo for cultivating interfaith tolerance, Arabic literature, and spiritual devotion.',
                'summary_om' => 'Wiirtuu amantaa fi beekumsa Islaamaa kan jaalalaa fi wandooma gabbisu.',
                'image_path' => '/assets/zawya_shrine.jpg',
                'order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'category' => 'industry',
                'title_am' => 'ኮምቦልቻ ልዩ የኢኮኖሚ ዞንና ኢንዱስትሪ ፓርክ (SEZ)',
                'title_en' => 'Kombolcha Special Economic Zone & Industrial Park',
                'title_om' => 'Zoonii Diinagdee Addaa fi Paarkii Indaastirii Kombolchaa',
                'location_am' => 'ሀሰናገር ክፍለ ከተማ',
                'location_en' => 'Hassen Ager Sub-City Industrial Belt',
                'location_om' => 'Kutaa Magaalaa Hasanaagar',
                'summary_am' => 'ከ64 በላይ ፋብሪካዎች በምርት ላይ የሚገኙበት፣ በጨርቃጨርቅ፣ አልባሳትና ማኑፋክቸሪንግ ኤክስፖርት የታወቀ የኢትዮጵያ ቀዳሚ የኢንዱስትሪ ማዕከል።',
                'summary_en' => 'A premier 75+ hectare manufacturing enclave hosting 64+ operational factories, state-of-the-art effluent treatment, and zero-tariff export processing zones.',
                'summary_om' => 'Paarkii indaastirii warshaalee 64 ol of keessaa qabu fi oomishaalee gara alaatti ergu.',
                'image_path' => '/assets/industry_park_2.jpg',
                'order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'category' => 'industry',
                'title_am' => 'የኮምቦልቻ ደረቅ ወደብና ብሄራዊ የባቡር ተርሚናል',
                'title_en' => 'Kombolcha Multimodal Dry Port & Railway Terminal',
                'title_om' => 'Buufata Gogaa fi Tajaajila Baaburaa Kombolchaa',
                'location_am' => 'ቦርከና ክፍለ ከተማ',
                'location_en' => 'Borkena Sub-City Logistics Corridor',
                'location_om' => 'Kutaa Magaalaa Borkanaa',
                'summary_am' => 'የሰሜን ምስራቅ ኢትዮጵያን ንግድ ከጅቡቲ ወደብ (478 ኪ.ሜ) እና ከአሰብ (390 ኪ.ሜ) ጋር የሚያገናኝ ስትራቴጂካዊ የኮንቴነርና የጭነት ሎጂስቲክስ ማዕከል።',
                'summary_en' => 'Strategic logistics gateway connecting northern industrial shipments directly to Djibouti (478 km) and Assab corridors via heavy electric freight rail.',
                'summary_om' => 'Wiirtuu loojistiksii gogaa fi baaburaa kan gabaa addunyaa waliin walqunnamsiisu.',
                'image_path' => '/assets/dry_port.jpg',
                'order' => 7,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($destinations as $dest) {
            DB::table('destinations')->insert($dest);
        }
    }
}
