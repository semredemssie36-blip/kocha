<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing sub_cities
        DB::table('sub_cities')->delete();

        $subCities = [
            [
                'name_am' => 'አቢሻገር ክፍለ ከተማ',
                'name_en' => 'Abishager Sub-City',
                'name_om' => 'Kutaa Magaalaa Abishaager',
                'slug' => 'abishager',
                'slogan_am' => 'አቢሻአገር፡ የልማትና የዕድገት ማዕከል! የዘመናዊነትና የመልካም ኑሮ ተምሳሌት!',
                'slogan_en' => 'Abishager: Hub of Development, Growth, and Civic Modernity!',
                'slogan_om' => 'Abishaager: Wiirtuu Misoomaa, Guddinaa fi Ammayyummaa!',
                'chief_executive_am' => 'የአቢሻገር ክፍለ ከተማ አስተዳደር',
                'chief_executive_en' => 'Abishager Sub-City Administration',
                'chief_executive_om' => 'Bulchiinsa Kutaa Magaalaa Abishaager',
                'phone' => '0335510005',
                'email' => 'abishager@kombolchacity.gov.et',
                'office_address_am' => 'ኮምቦልቻ፣ አቢሻገር ክፍለ ከተማ አስተዳደር ጽ/ቤት',
                'office_address_en' => 'Kombolcha, Abishager Sub-City Administrative Complex',
                'office_address_om' => 'Kombolcha, Waajjira Bulchiinsa Kutaa Magaalaa Abishaager',
                'description_am' => 'በሶስት ቀበሌዎች (ቀዲዳ፣ ሸዋበር፣ ሁሩባ) የተመሰረተችው አቢሻአገር፣ የዘመናዊነትና የመልካም ኑሮ ተምሳሌት ሆናለች። የከፍተኛ ትምህርት ተቋማት፣ የፍሳሽ ቆሻሻ ማጣሪያ፣ የ40 ሜትር ባይ ፓስ መንገድ እና የእንስሳት ሀብት ክላስተር የሚገኙባት ቀጠና ናት።',
                'description_en' => 'Founded across three constituent kebeles (Qedida, Shewaber, and Huruba), Abishager is a model of urban modernity and quality living. It is home to Wollo University Institute of Technology, advanced municipal wastewater treatment infrastructure, a 40-meter bypass highway corridor, and strategic livestock economic clusters.',
                'description_om' => 'Gandoolee sadii (Qadiidaa, Shawaabar, fi Hurubaa) jalatti kan hundeeffamte Abishaager, fakkeenya ammayyummaa fi jireenya gaariiti. Dhaabbilee barnoota olaanaa, sarara dhangala\'aa, daandii baay-paasii meetira 40 fi kilaastara beeyladaa qabdi.',
                'kebeles' => json_encode([
                    ['am' => 'ቀዲዳ', 'en' => 'Qedida', 'type' => 'urban'],
                    ['am' => 'ሸዋበር', 'en' => 'Shewaber', 'type' => 'urban'],
                    ['am' => 'ሁሩባ', 'en' => 'Huruba', 'type' => 'urban'],
                ]),
                'highlights' => json_encode([
                    'Wollo University Institute of Technology (KIOT)',
                    '40-Meter Heavy-Traffic Bypass Highway Corridor',
                    'Municipal Wastewater & Sewerage Treatment Facility',
                    'Livestock Economic Growth & Fattening Cluster',
                    'Comprehensive Primary & Secondary Education Centers',
                    'Interfaith Harmony & Social Peace Centers'
                ]),
                'photo_path' => '/assets/city_image_2.jpg',
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_am' => 'ቢራሮ ክፍለ ከተማ',
                'name_en' => 'Biraro Sub-City',
                'name_om' => 'Kutaa Magaalaa Biraaroo',
                'slug' => 'biraro',
                'slogan_am' => 'ቢራሮ፡ የኮምቦልቻ የልብ ምት! የንግድ፣ የልማትና የመልካም ኑሮ ማዕከል!',
                'slogan_en' => 'Biraro: The Heartbeat of Kombolcha! Hub of Commerce and Heritage!',
                'slogan_om' => 'Biraaroo: Dha\'annaa Onnee Kombolchaa! Wiirtuu Daldalaa fi Misoomaa!',
                'chief_executive_am' => 'የቢራሮ ክፍለ ከተማ አስተዳደር',
                'chief_executive_en' => 'Biraro Sub-City Administration',
                'chief_executive_om' => 'Bulchiinsa Kutaa Magaalaa Biraaroo',
                'phone' => '0335511505',
                'email' => 'biraro@kombolchacity.gov.et',
                'office_address_am' => 'ኮምቦልቻ፣ ቢራሮ ክፍለ ከተማ አስተዳደር ጽ/ቤት',
                'office_address_en' => 'Kombolcha, Biraro Sub-City Administrative Complex',
                'office_address_om' => 'Kombolcha, Waajjira Bulchiinsa Kutaa Magaalaa Biraaroo',
                'description_am' => 'በአራት ቀበሌዎች (ቦርቸሌ፣ ራስ-አገዝ፣ ሚጢቆሎ፣ ከተማ አገር) የምትመሰረተው ቢራሮ፣ የከተማዋ መሀል / እምብርት ሆና በልዩ ልዩ ጥቅሞች ትታወቃለች። የኮምቦልቻ ከተማ አስተዳደር መገኛ፣ የከተማው ኳስ ሜዳ፣ ሳምንታዊ ዋና ገበያ፣ ዘመናዊ ቄራ እና የግንባታ ድንጋይ ማዕድን ሀብት ይገኝባታል።',
                'description_en' => 'Formed across four key kebeles (Borchele, Ras Agez, Mitikolo, and Ketema Ager), Biraro is the historic downtown and administrative heart of Kombolcha. It houses the Metropolitan City Hall, the Central Football Stadium, the bi-weekly Wednesday & Saturday markets, modern abattoirs, and construction stone quarries.',
                'description_om' => 'Gandoolee afur (Borcallee, Raas-Agaz, Muxiiqolloo, fi Magaalaa Biyyaa) irraa kan ijaaramte Biraaroo, wiirtuu magaalaa fi giddugala seenaa Kombolchaati. Waajjira Bulchiinsaa, Istaadiyoomii kubbaa miilaa, gabaa sanbataa fi roobii, qeeraa ammayyaa fi qabeenya dhagaa ijaarsaa qabdi.',
                'kebeles' => json_encode([
                    ['am' => 'ቦርቸሌ', 'en' => 'Borchele', 'type' => 'urban'],
                    ['am' => 'ራስ-አገዝ', 'en' => 'Ras Agez', 'type' => 'urban'],
                    ['am' => 'ሚጢቆሎ', 'en' => 'Mitikolo', 'type' => 'urban'],
                    ['am' => 'ከተማ አገር (08)', 'en' => 'Ketema Ager (08)', 'type' => 'rural'],
                ]),
                'highlights' => json_encode([
                    'Metropolitan City Administration Headquarters',
                    'Central City Football Stadium & Sports Arena',
                    'Bi-Weekly Saturday & Wednesday Central Markets & Borchele Market',
                    'Modern Municipal Abattoir & Meat Processing Center',
                    'Major Commercial Banking & Financial Services Cluster',
                    'Construction Stone Quarry & Mineral Deposits',
                    'Youth Development Center & Correctional Administration'
                ]),
                'photo_path' => '/assets/city_image.jpg',
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_am' => 'ቦርከና ክፍለ ከተማ',
                'name_en' => 'Borkena Sub-City',
                'name_om' => 'Kutaa Magaalaa Borkanaa',
                'slug' => 'borkena',
                'slogan_am' => 'ቦርከና ክፍለ ከተማ፡ የዕድገትና የዕድል ማዕከል! ዋና የሎጂስቲክስና የኢንቨስትመንት መዳረሻ!',
                'slogan_en' => 'Borkena: Hub of Prosperity, National Logistics and Investment!',
                'slogan_om' => 'Borkanaa: Wiirtuu Loojistiksii, Invastimantii fi Carraawwan Guddinaa!',
                'chief_executive_am' => 'የቦርከና ክፍለ ከተማ አስተዳደር',
                'chief_executive_en' => 'Borkena Sub-City Administration',
                'chief_executive_om' => 'Bulchiinsa Kutaa Magaalaa Borkanaa',
                'phone' => '0335510229',
                'email' => 'Borkenarevenue@gmail.com',
                'office_address_am' => 'ኮምቦልቻ፣ ቦርከና ክፍለ ከተማ፣ ከተባበሩት ማደያ 50 ሜትር ገባ ብሎ ከዶክተር አዳነ ክሊኒክ ፊት ለፊት',
                'office_address_en' => 'Kombolcha, Borkena Sub-City, 50m behind Total Fuel Station, Facing Dr. Adane Clinic',
                'office_address_om' => 'Kombolcha, Kutaa Magaalaa Borkanaa, Buufata Dhaabbata Boba\'aa booda',
                'description_am' => 'በስድስት ቀበሌዎች (የቀድሞው አየር ማረፊያ፣ ሽሻበር፣ ቁጠባ፣ የጎፍ፣ መጠኔ፣ ጋሌሳ) የተዋቀረችው ቦርከና፣ የኢትዮጵያ ደረቅ ወደብ፣ የባቡር ስቴሽን፣ የ40 ሜትር ባይ ፓስ መንገድ እና በቅርቡ በቅድመ ጥናት የተገኘው የወርቅ ማዕድን የሚገኝባት የኢኮኖሚ ምሰሶ ናት።',
                'description_en' => 'Spanning six strategic kebeles (Old Airport, Shishaber, Kuteba, Yegof, Metene, and Galesa), Borkena is Ethiopia\'s northeastern multimodal logistics gateway. It anchors the Kombolcha Dry Port, Ethio-Djibouti Railway freight depot, the 40-meter bypass highway, multiple industrial villages, and newly discovered gold mineral reserves.',
                'description_om' => 'Gandoolee jaha (Buufata Xiyyaaraa Duraanii, Shishaabar, Quxxabaa, Yagof, Daxanee, fi Gaallisaa) kan hammate Borkanaa, buufata gogaa Itoophiyaa, buufata baaburaa, daandii baay-paasii meetira 40 fi albuuda warqee dhiheenyatti qoratame qabdi.',
                'kebeles' => json_encode([
                    ['am' => 'የቀድሞው አየር ማረፊያ', 'en' => 'Old Airport', 'type' => 'urban'],
                    ['am' => 'ሽሻበር', 'en' => 'Shishaber', 'type' => 'urban'],
                    ['am' => 'ቁጠባ', 'en' => 'Kuteba', 'type' => 'urban'],
                    ['am' => 'የጎፍ', 'en' => 'Yegof', 'type' => 'urban'],
                    ['am' => 'መጠኔ (010)', 'en' => 'Metene (010)', 'type' => 'rural'],
                    ['am' => 'ጋሌሳ (011)', 'en' => 'Galesa (011)', 'type' => 'rural'],
                ]),
                'highlights' => json_encode([
                    'Kombolcha Dry Port (National Maritime Freight Terminal)',
                    'Ethio-Djibouti Railway Station & Multimodal Cargo Depot',
                    '40-Meter Heavy-Vehicle Bypass Highway Network',
                    'Newly Discovered Gold Mineral Deposit (በቅድመ ጥናት የተገኘ የወርቅ ማዕድን)',
                    'Active Manufacturing Villages & Industrial Warehousing',
                    'Sub-City Revenue Authority (Direct Line: 0335510229)',
                    'Modern Star Hotels & Scenic Recreation Parks'
                ]),
                'photo_path' => '/assets/industry_park.jpg',
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_am' => 'ሀሰን አገር ክፍለ ከተማ',
                'name_en' => 'Hassen Ager Sub-City',
                'name_om' => 'Kutaa Magaalaa Hasanaager',
                'slug' => 'hasen-ager',
                'slogan_am' => 'ሀሰና አገር፡ የልማትና የኑሮ ተስፋችን! የአየር ትራንስፖርትና የኢንዱስትሪ ማዕከል!',
                'slogan_en' => 'Hassen Ager: Our Promise for Prosperity, Industrial Parks and Aviation Hub!',
                'slogan_om' => 'Hasanaager: Wiirtuu Buufata Xiyyaaraa, Paarkii Indaastirii fi Abdii Misoomaa!',
                'chief_executive_am' => 'የሀሰን አገር ክፍለ ከተማ አስተዳደር',
                'chief_executive_en' => 'Hassen Ager Sub-City Administration',
                'chief_executive_om' => 'Bulchiinsa Kutaa Magaalaa Hasanaager',
                'phone' => '0335510005',
                'email' => 'hasenager@kombolchacity.gov.et',
                'office_address_am' => 'ኮምቦልቻ፣ ሀሰን አገር ክፍለ ከተማ፣ በርበሬ ወንዝ ቀበሌ ፍርድ ቤት ግቢ',
                'office_address_en' => 'Kombolcha, Hassen Ager Sub-City, Berberewenz Kebele Court Compound',
                'office_address_om' => 'Kombolcha, Kutaa Magaalaa Hasanaager, Mooraa Mana Murtii Ganda Barbaaree',
                'description_am' => 'በሰባት ቀበሌዎች (በርበሬ ወንዝ፣ ሁዳድ፣ ሜጃ፣ ቱሉአዳሜ፣ ሙጢ ግራር፣ ሁላበር፣ ትዩ አምባ) የምትመሰረተው ሀሰን አገር፣ የኮምቦልቻ አየር ማረፊያ፣ የኮምቦልቻ ኢንዱስትሪ ፓርክ፣ የቦርከና ወንዝ ዳርቻ ስነ-ምህዳር ጥበቃ እና የመዝናኛ መናፈሻዎች የሚገኙባት ስልታዊ ቀጠና ናት።',
                'description_en' => 'Comprising seven expansive kebeles (Berberewenz, Hudadu, Meja, Tulu Adame, Muti Girar, Hulaber, and Tiyu Amba), Hassen Ager is the economic powerhouse of Kombolcha. It hosts the regional Kombolcha Airport, the flagship Kombolcha Industrial Park, the Borkena riverfront conservation greenway, public leisure parks, and civic justice courts.',
                'description_om' => 'Gandoolee torba (Laga Barbaaree, Hudaad, Meejaa, Tulluu Adaamii, Muxii Giraar, Hulaabar, fi Tiyyuu Ambaa) kan hammate Hasanaager, buufata xiyyaaraa naannichaa, Paarkii Indaastirii Kombolchaa, eegumsa laga Borkanaa fi iddoowwan bashannanaa qabdi.',
                'kebeles' => json_encode([
                    ['am' => 'በርበሬ ወንዝ', 'en' => 'Berberewenz', 'type' => 'urban'],
                    ['am' => 'ሁዳድ', 'en' => 'Hudadu', 'type' => 'urban'],
                    ['am' => 'ሜጃ', 'en' => 'Meja', 'type' => 'urban'],
                    ['am' => 'ቱሉአዳሜ', 'en' => 'Tulu Adame', 'type' => 'urban'],
                    ['am' => 'ሙጢ ግራር (06)', 'en' => 'Muti Girar (06)', 'type' => 'rural'],
                    ['am' => 'ሁላበር (07)', 'en' => 'Hulaber (07)', 'type' => 'rural'],
                    ['am' => 'ትዩ አምባ (09)', 'en' => 'Tiyu Amba (09)', 'type' => 'rural'],
                ]),
                'highlights' => json_encode([
                    'Kombolcha Regional Airport (35-min Flight to Addis Ababa, 49-min to Bahir Dar)',
                    'Flagship Kombolcha Industrial Park (Textile, Apparel & Manufacturing)',
                    'Borkena Riverfront Ecological Conservation & Promenade',
                    'Public Leisure Parks & Community Green Open Spaces',
                    'First-Instance Judicial Courts & Police Precinct',
                    'Comprehensive Educational Centers & High Schools'
                ]),
                'photo_path' => '/assets/earport2.webp',
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($subCities as $sc) {
            DB::table('sub_cities')->insert($sc);
        }
    }
}
