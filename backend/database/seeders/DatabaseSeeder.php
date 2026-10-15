<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Initial Staff Accounts
        DB::table('users')->insert([
            [
                'name' => 'Super Administrator',
                'email' => 'admin@kombolchacity.gov.et',
                'password' => Hash::make('Admin@2026!'),
                'role' => 'super_admin',
                'phone' => '+251 33 551 0001',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Communications Officer (Editor)',
                'email' => 'editor@kombolchacity.gov.et',
                'password' => Hash::make('Editor@2026!'),
                'role' => 'editor',
                'phone' => '+251 33 551 0002',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 2. Seed The 4 Sub-Cities (ክፍለ ከተሞች)
        $subCities = [
            [
                'name_am' => 'አቢሻገር ክፍለ ከተማ',
                'name_en' => 'Abishager Sub-City',
                'name_om' => 'Kutaa Magaalaa Abishager',
                'slug' => 'abishager',
                'chief_executive_am' => 'አቶ ሞላ ካሳሁን',
                'chief_executive_en' => 'Ato Molla Kassahun',
                'chief_executive_om' => 'Obbo Mollaa Kassaahuun',
                'phone' => '+251 33 551 1010',
                'email' => 'abishager@kombolchacity.gov.et',
                'office_address_am' => 'አቢሻገር፣ ዋና መንገድ አጠገብ',
                'office_address_en' => 'Abishager, Main Corridor Road',
                'office_address_om' => 'Abishaager, Daandii Guddaa Qarqara',
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_am' => 'ቦርከና ክፍለ ከተማ',
                'name_en' => 'Borkena Sub-City',
                'name_om' => 'Kutaa Magaalaa Borkena',
                'slug' => 'borkena',
                'chief_executive_am' => 'ወ/ሮ ሰላማዊት አበራ',
                'chief_executive_en' => 'W/ro Selamawit Abera',
                'chief_executive_om' => 'Aaddee Selaamaawiit Abarraa',
                'phone' => '+251 33 551 2020',
                'email' => 'borkena@kombolchacity.gov.et',
                'office_address_am' => 'ቦርከና፣ የቀድሞ ቀበሌ 03 ጽ/ቤት',
                'office_address_en' => 'Borkena, Former Kebele 03 Administration Hall',
                'office_address_om' => 'Borkanaa, Waajjira Duraanii Ganda 03',
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_am' => 'ሀሰናገር ክፍለ ከተማ',
                'name_en' => 'Hasenager Sub-City',
                'name_om' => 'Kutaa Magaalaa Hasenager',
                'slug' => 'hasenager',
                'chief_executive_am' => 'አቶ አህመድ ሙስጠፋ',
                'chief_executive_en' => 'Ato Ahmed Mustafa',
                'chief_executive_om' => 'Obbo Ahimad Mustafaa',
                'phone' => '+251 33 551 3030',
                'email' => 'hasenager@kombolchacity.gov.et',
                'office_address_am' => 'ሀሰናገር፣ አውቶቡስ ተርሚናል ፊት ለፊት',
                'office_address_en' => 'Hasenager, Facing Intercity Bus Terminal',
                'office_address_om' => 'Hasanaager, Fuuldura Buufata Baaburaa/Konkolaataa',
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_am' => 'ቢራሮ ክፍለ ከተማ',
                'name_en' => 'Biraro Sub-City',
                'name_om' => 'Kutaa Magaalaa Biraro',
                'slug' => 'biraro',
                'chief_executive_am' => 'አቶ ተስፋዬ መንግስቱ',
                'chief_executive_en' => 'Ato Tesfaye Mengistu',
                'chief_executive_om' => 'Obbo Tasfaayee Mangistuu',
                'phone' => '+251 33 551 4040',
                'email' => 'biraro@kombolchacity.gov.et',
                'office_address_am' => 'ቢራሮ፣ የኢንዱስትሪ ፓርክ መንገድ',
                'office_address_en' => 'Biraro, Industrial Park Access Road',
                'office_address_om' => 'Biraaroo, Daandii Wiirtuu Indaastirii',
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        DB::table('sub_cities')->insert($subCities);

        // 3. Seed The 22 Municipal Offices
        $offices = [
            ['name_am' => 'የኮምቦልቻ ከንቲባ ጽሕፈት ቤት', 'name_en' => "Kombolcha Mayor's Office", 'name_om' => 'Waajjira Kantiibaa Kombolchaa', 'slug' => 'mayor-office', 'phone' => '+251 33 551 0022', 'email' => 'mayor@kombolchacity.gov.et', 'building_location_am' => 'ዋና ማዘጋጃ ቤት፣ ህንፃ 1 ወለል 2', 'room_number' => '201'],
            ['name_am' => 'የፋይናንስ መምሪያ', 'name_en' => 'Finance Department', 'name_om' => 'Waajjira Faayinaansii', 'slug' => 'finance-dept', 'phone' => '+251 33 551 0045', 'email' => 'finance@kombolchacity.gov.et', 'building_location_am' => 'ዋና ማዘጋጃ ቤት፣ ህንፃ 2 ወለል 1', 'room_number' => '104'],
            ['name_am' => 'የመንገዶች ባለስልጣን መምሪያ', 'name_en' => 'Roads Authority Directorate', 'name_om' => 'Abbaa Taayitaa Daandiiwwanii', 'slug' => 'roads-authority', 'phone' => '+251 33 551 0180', 'email' => 'roads@kombolchacity.gov.et', 'building_location_am' => 'ቦርከና ህንፃ፣ 3ኛ ፎቅ', 'room_number' => '302'],
            ['name_am' => 'የወጣትና ስፖርት መምሪያ', 'name_en' => 'Youth and Sports Directorate', 'name_om' => 'Qajeelcha Dargaggootaa fi Ispoortii', 'slug' => 'youth-sports', 'phone' => '+251 33 551 0220', 'email' => 'youth@kombolchacity.gov.et', 'building_location_am' => 'ኮምቦልቻ ስታዲየም ቢሮዎች', 'room_number' => '12'],
            ['name_am' => 'የኢንዱስትሪና ኢንቨስትመንት መምሪያ', 'name_en' => 'Industry and Investment Directorate', 'name_om' => 'Qajeelcha Indaastirii fi Invastimantii', 'slug' => 'industry-investment', 'phone' => '+251 33 551 0350', 'email' => 'investment@kombolchacity.gov.et', 'building_location_am' => 'ኢንዱስትሪ ፓርክ የመረጃ ማዕከል', 'room_number' => '05'],
            ['name_am' => 'የከተማ ልማትና ኮንስትራክሽን መምሪያ', 'name_en' => 'Urban Development & Construction', 'name_om' => 'Misooma Magaalaa fi Ijaarsaa', 'slug' => 'urban-development', 'phone' => '+251 33 551 0410', 'email' => 'construction@kombolchacity.gov.et', 'building_location_am' => 'ማዘጋጃ ቤት ህንፃ 1', 'room_number' => '115'],
            ['name_am' => 'የውሃና ፍሳሽ አገልግሎት ድርጅት', 'name_en' => 'Water and Sewerage Enterprise', 'name_om' => 'Dhaabbata Tajaajila Bishaanii', 'slug' => 'water-sewerage', 'phone' => '+251 33 551 0145', 'email' => 'water@kombolchacity.gov.et', 'building_location_am' => 'አቢሻገር፣ የውሃ ማጣሪያ ማዕከል', 'room_number' => '01'],
            ['name_am' => 'የጤና ጥበቃ መምሪያ', 'name_en' => 'Health Care Department', 'name_om' => 'Waajjira Eegumsa Fayyaa', 'slug' => 'health-dept', 'phone' => '+251 33 551 0500', 'email' => 'health@kombolchacity.gov.et', 'building_location_am' => 'ሆስፒታል አቅራቢያ', 'room_number' => '20'],
            ['name_am' => 'የትምህርት መምሪያ', 'name_en' => 'Education Department', 'name_om' => 'Waajjira Barnootaa', 'slug' => 'education-dept', 'phone' => '+251 33 551 0612', 'email' => 'education@kombolchacity.gov.et', 'building_location_am' => 'ዋና ማዘጋጃ ቤት ህንፃ 2', 'room_number' => '204'],
            ['name_am' => 'የንግድና ገበያ ልማት መምሪያ', 'name_en' => 'Trade and Market Development', 'name_om' => 'Daldalaa fi Gabaa Misoomaa', 'slug' => 'trade-market', 'phone' => '+251 33 551 0725', 'email' => 'trade@kombolchacity.gov.et', 'building_location_am' => 'ገበያ አዳራሽ ህንፃ', 'room_number' => '108'],
            ['name_am' => 'የመሬት አስተዳደርና አጠቃቀም መምሪያ', 'name_en' => 'Land Administration & Management', 'name_om' => 'Bulchiinsa fi Itti Fayyadama Lafaa', 'slug' => 'land-admin', 'phone' => '+251 33 551 0830', 'email' => 'land@kombolchacity.gov.et', 'building_location_am' => 'ዋና ማዘጋጃ ቤት ህንፃ 1 ወለል 3', 'room_number' => '301'],
            ['name_am' => 'የሰላምና ጸጥታ መምሪያ', 'name_en' => 'Peace and Security Department', 'name_om' => 'Nagaa fi Nageenya', 'slug' => 'peace-security', 'phone' => '+251 33 551 0911', 'email' => 'security@kombolchacity.gov.et', 'building_location_am' => 'ፖሊስ መምሪያ አጠገብ', 'room_number' => '02'],
            ['name_am' => 'የሴቶችና ህፃናት ጉዳይ መምሪያ', 'name_en' => "Women's & Children's Affairs", 'name_om' => 'Dhimma Dubartootaa fi Daa\'immanii', 'slug' => 'women-children', 'phone' => '+251 33 551 1120', 'email' => 'women@kombolchacity.gov.et', 'building_location_am' => 'ማህበራዊ ጉዳይ ህንፃ', 'room_number' => '14'],
            ['name_am' => 'የህዝብ ግንኙነትና ኮሙኒኬሽን መምሪያ', 'name_en' => 'Public Relations & Communication', 'name_om' => 'Qunnamtii Ummataa fi Koominikeeshinii', 'slug' => 'public-relations', 'phone' => '+251 33 551 1230', 'email' => 'press@kombolchacity.gov.et', 'building_location_am' => 'ዋና ማዘጋጃ ቤት ህንፃ 1 ወለል 1', 'room_number' => '102'],
            ['name_am' => 'የፅዳትና ውበት አስተዳደር ኤጀንሲ', 'name_en' => 'Sanitation and Beautification Agency', 'name_om' => 'Qulqullinaa fi Miidhagina Magaalaa', 'slug' => 'sanitation-agency', 'phone' => '+251 33 551 1340', 'email' => 'sanitation@kombolchacity.gov.et', 'building_location_am' => 'ቦርከና ፓርክ ቢሮ', 'room_number' => '03'],
            ['name_am' => 'የትራንስፖርትና ተሽከርካሪ መቆጣጠሪያ መምሪያ', 'name_en' => 'Transport Regulatory Directorate', 'name_om' => 'To\'annoo Geejjibaa fi Konkolaattotaa', 'slug' => 'transport-dept', 'phone' => '+251 33 551 1450', 'email' => 'transport@kombolchacity.gov.et', 'building_location_am' => 'አውቶቡስ ተርሚናል ቢሮዎች', 'room_number' => '08'],
            ['name_am' => 'የገቢዎች ባለስልጣን ቅርንጫፍ ጽ/ቤት', 'name_en' => 'Revenues Authority Branch Office', 'name_om' => 'Abbaa Taayitaa Galiiwwanii', 'slug' => 'revenues-office', 'phone' => '+251 33 551 1560', 'email' => 'revenue@kombolchacity.gov.et', 'building_location_am' => 'ገቢዎች ህንፃ', 'room_number' => '201'],
            ['name_am' => 'የግብርናና የተፈጥሮ ሀብት መምሪያ', 'name_en' => 'Agriculture & Natural Resources', 'name_om' => 'Qonnaa fi Qabeenya Uumamaa', 'slug' => 'agriculture-dept', 'phone' => '+251 33 551 1670', 'email' => 'agriculture@kombolchacity.gov.et', 'building_location_am' => 'ቢራሮ ቢሮዎች', 'room_number' => '11'],
            ['name_am' => 'የባህልና ቱሪዝም መምሪያ', 'name_en' => 'Culture and Tourism Department', 'name_om' => 'Aadaa fi Tuurizimii', 'slug' => 'culture-tourism', 'phone' => '+251 33 551 1780', 'email' => 'tourism@kombolchacity.gov.et', 'building_location_am' => 'ባህል ማዕከል አዳራሽ', 'room_number' => '04'],
            ['name_am' => 'የስራ እድል ፈጠራና ኢንተርፕራይዝ ልማት', 'name_en' => 'Job Creation & Enterprise Development', 'name_om' => 'Uumuu Carraa Hojii fi Dhaabbilee', 'slug' => 'job-creation', 'phone' => '+251 33 551 1890', 'email' => 'jobs@kombolchacity.gov.et', 'building_location_am' => 'ቲቪኢቲ ኮሌጅ አጠገብ', 'room_number' => '105'],
            ['name_am' => 'የፍትህ መምሪያ', 'name_en' => 'Justice Department', 'name_om' => 'Waajjira Haqaa', 'slug' => 'justice-dept', 'phone' => '+251 33 551 1990', 'email' => 'justice@kombolchacity.gov.et', 'building_location_am' => 'ፍርድ ቤት ህንፃ 2', 'room_number' => '203'],
            ['name_am' => 'የቴክኒክና ሙያ ስልጠና (ቲቪኢቲ) ኤጀንሲ', 'name_en' => 'TVET Agency', 'name_om' => 'Ejenasii Leenjii Teeknikaa fi Ogummaa', 'slug' => 'tvet-agency', 'phone' => '+251 33 551 2100', 'email' => 'tvet@kombolchacity.gov.et', 'building_location_am' => 'ኮምቦልቻ ፖሊቴክኒክ ኮሌጅ', 'room_number' => '01'],
        ];

        foreach ($offices as $idx => $off) {
            $off['order'] = $idx + 1;
            $off['is_active'] = true;
            $off['created_at'] = now();
            $off['updated_at'] = now();
            DB::table('offices')->insert($off);
        }

        // 4. Seed Emergency Contacts
        $emergencyContacts = [
            ['name_am' => 'የከተማ ፖሊስ መምሪያ', 'name_en' => 'City Police Station', 'name_om' => 'Waajjira Poolisii Magaalaa', 'phone_number' => '991', 'badge_color' => 'red', 'icon_name' => 'ShieldAlert', 'order' => 1],
            ['name_am' => 'የእሳትና ድንገተኛ አደጋ መከላከል', 'name_en' => 'Fire & Rescue Emergency', 'name_om' => 'Balaa Ibiddii fi Tasaa', 'phone_number' => '939', 'badge_color' => 'orange', 'icon_name' => 'Flame', 'order' => 2],
            ['name_am' => 'አምቡላንስ / ሪፈራል ሆስፒታል', 'name_en' => 'Ambulance / Referral Hospital', 'name_om' => 'Ambulaansii / Hospitaala', 'phone_number' => '907', 'badge_color' => 'rose', 'icon_name' => 'Activity', 'order' => 3],
            ['name_am' => 'የውሃ ፍሳሽ ድንገተኛ ጥገና', 'name_en' => 'Water Leak Emergency Hotline', 'name_om' => 'Sarara Bishaanii Balaa Tasaa', 'phone_number' => '+251 33 551 0145', 'badge_color' => 'blue', 'icon_name' => 'Droplets', 'order' => 4],
            ['name_am' => 'የመብራት ኃይል አደጋ መስመር', 'name_en' => 'Power Grid Emergency Desk', 'name_om' => 'Tajaajila Ibsaa Balaa Tasaa', 'phone_number' => '905', 'badge_color' => 'amber', 'icon_name' => 'Zap', 'order' => 5],
        ];
        foreach ($emergencyContacts as $ec) {
            $ec['created_at'] = now();
            $ec['updated_at'] = now();
            DB::table('emergency_contacts')->insert($ec);
        }

        // 5. Seed Initial Tenders
        DB::table('tenders')->insert([
            [
                'reference_number' => 'KMB/NCB/08/2026',
                'title_am' => 'የከተማ ውስጥ አስፋልት ጥገና እና የፍሳሽ ቦይ ማስፋፊያ ግዥ ጨረታ',
                'title_en' => 'Procurement of Asphalt Road Maintenance & Drainage Channel Infrastructure',
                'title_om' => 'Caalbaasii Suphaa Daandii Asfaaltii fi Booyii Dhangala\'aa Magaalaa',
                'category' => 'Works',
                'bid_bond_amount' => 100000.00,
                'closing_datetime' => Carbon::now()->addDays(14)->setTime(10, 0, 0),
                'opening_datetime' => Carbon::now()->addDays(14)->setTime(10, 30, 0),
                'status' => 'open',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'reference_number' => 'KMB/NCB/09/2026',
                'title_am' => 'ለዘመናዊ የህዝብ መብራት ዝርጋታ የሶላር እቃዎች አቅርቦት ግዥ ጨረታ',
                'title_en' => 'Supply and Installation of Solar-Powered Smart Street Lighting Systems',
                'title_om' => 'Caalbaasii Dhiyeessii Ibsaa Daandii Soolaaraa Magaalaatiif',
                'category' => 'Goods',
                'bid_bond_amount' => 50000.00,
                'closing_datetime' => Carbon::now()->addDays(6)->setTime(14, 0, 0),
                'opening_datetime' => Carbon::now()->addDays(6)->setTime(14, 30, 0),
                'status' => 'open',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 6. Seed Initial Step Service (Example: Kebele ID Card)
        $serviceId = DB::table('services')->insertGetId([
            'type' => 'step',
            'title_am' => 'የነዋሪነት የቀበሌ መታወቂያ ካርድ አሰጣጥ አገልግሎት',
            'title_en' => 'Resident Kebele Identification Card Issuance',
            'title_om' => 'Waraqaa Eenyummaa Lammiilee Gandaa Kennuu',
            'slug' => 'resident-kebele-id',
            'summary_am' => 'በኮምቦልቻ ከተማ ውስጥ ለሚኖሩ ህጋዊ ዜጎች የመኖሪያ መታወቂያ የመስጠት ሂደት እና ቅደም ተከተሎች',
            'summary_en' => 'Procedure and sequential guidelines for obtaining an official resident ID in Kombolcha City.',
            'summary_om' => 'Adeemsa waraqaa eenyummaa gandaa Kombolchaa keessatti argachuuf barbaachisu.',
            'estimated_time_am' => '3 የስራ ቀናት',
            'estimated_time_en' => '3 Business Days',
            'estimated_time_om' => 'Guyyoota Hojii 3',
            'fee_amount' => 50.00,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('service_steps')->insert([
            [
                'service_id' => $serviceId,
                'step_number' => 1,
                'title_am' => 'አስፈላጊ ሰነዶችን ማዘጋጀት',
                'title_en' => 'Prepare Required Documentation',
                'title_om' => 'Sanadoota Barbaachisoo Qopheessuu',
                'instruction_am' => 'የልደት ምስክር ወረቀት፣ 2 የቅርብ ጊዜ ጉርድ ፎቶግራፍ እና የቤት ኪራይ ወይም የይዞታ ማረጋገጫ ይዘው ይቅረቡ።',
                'instruction_en' => 'Gather birth certificate, two recent passport-size photos, and proof of residence/tenancy.',
                'instruction_om' => 'Waraqaa ragaa dhalootaa, suuraawwan gabaabaa 2 fi ragaa mana jireenyaa qabadhaa koottaa.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_id' => $serviceId,
                'step_number' => 2,
                'title_am' => 'የአገልግሎት ክፍያ መክፈል',
                'title_en' => 'Payment of Administrative Fee',
                'title_om' => 'Kaffaltii Tajaajilaa Raawwachuu',
                'instruction_am' => 'በቴሌብር ወይም በንግድ ባንክ በኩል የተቆረጠ የ 50 ብር የክፍያ ደረሰኝ ያያይዙ።',
                'instruction_en' => 'Pay the nominal 50 ETB administrative fee via Telebirr or Commercial Bank of Ethiopia.',
                'instruction_om' => 'Kaffaltii qarshii 50 Telebirr ykn Baankii Daldala Itiyoophiyaa tiin raawwadhaa.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_id' => $serviceId,
                'step_number' => 3,
                'title_am' => 'የጣት አሻራ እና የፎቶ ምዝገባ',
                'title_en' => 'Biometric Data Capture',
                'title_om' => 'Asheeta Qubaa fi Suuraa Galmeessuu',
                'instruction_am' => 'በሚኖሩበት ክፍለ ከተማ ወሳኝ ኩነት ዴስክ በመቅረብ ባዮሜትሪክ መረጃዎን ያስመዝግቡ።',
                'instruction_en' => 'Visit the vital events desk at your respective Sub-City office for biometric capture.',
                'instruction_om' => 'Kutaa magaalaa keessanitti argamuun ragaa baayoo-metriikii keessan galmeessisaa.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_id' => $serviceId,
                'step_number' => 4,
                'title_am' => 'መታወቂያ ካርድ መረከብ',
                'title_en' => 'Card Collection',
                'title_om' => 'Waraqaa Eenyummaa Fudhachuu',
                'instruction_am' => 'በ 3ኛው የስራ ቀን በደረሰኝዎ መሰረት ቋሚ የታተመ መታወቂያዎን ይረከቡ።',
                'instruction_en' => 'Present your submission slip on the 3rd business day to collect your printed laminated ID card.',
                'instruction_om' => 'Guyyaa hojii 3ffaa irratti nagahee keessan qabachuun waraqaa eenyummaa keessan fudhadhaa.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 7. Seed Mayor Profile
        DB::table('mayors')->insert([
            'name_am' => 'ክቡር አቶ ከድር አህመድ',
            'name_en' => 'Hon. Kedir Ahmed',
            'name_om' => 'Kabajamoo Obbo Kadiir Ahimad',
            'term_start' => '2021',
            'term_end' => 'Present',
            'is_current' => true,
            'welcome_message_am' => 'እንኳን ወደ ታሪካዊቷ፣ የኢንዱስትሪና የንግድ እምብርት ወደ ሆነችው ውቧ ኮምቦልቻ ከተማ አስተዳደር ዲጂታል ፖርታል በደህና መጡ! ከተማችንን ለነዋሪዎቿ ምቹ፣ ለባለሀብቶች ተመራጭ፣ በቴክኖሎጂ የታገዘ ፈጣን አገልግሎት የምትሰጥ ለማድረግ በቁርጠኝነት እየሰራን ነው።',
            'welcome_message_en' => 'Welcome to the digital portal of Kombolcha City Administration—the historical heartbeat of industry, commerce, and logistics in Ethiopia. We are committed to building a vibrant, resilient, and investor-friendly metropolis with accountable digital municipal governance.',
            'welcome_message_om' => 'Baga nagaan gara poortaalii dijitaalaa bulchiinsa Magaalaa Kombolchaa—wiirtuu seena-qabeessaa indaastirii fi daldalaatti dhuftan. Magaalaa keenya jiraattotaaf mijattuu fi invastimantiif filatamtuu gochuuf qindoominaan hojjechaa jirra.',
            'biography_am' => 'ክቡር አቶ ከድር አህመድ በከተማ ልማት አመራር እና ኢኮኖሚክስ ዘርፍ ከፍተኛ የትምህርት ዝግጅት ያላቸው ሲሆን፣ ላለፉት አመታት የኮምቦልቻ ከተማን የኢንዱስትሪ ፓርክ ስራዎችን በማስተባበር፣ የመሰረተ ልማት ዝርጋታዎችን በማፋጠን እና የህዝብ አገልግሎት አሰጣጥን በማዘመን ከፍተኛ አስተዋፅኦ አበርክተዋል።',
            'biography_en' => 'Hon. Kedir Ahmed holds advanced degrees in Urban Governance and Economics. He has spearheaded landmark urban regeneration programs, strategic expansion of the Kombolcha Industrial Park, and citizen-first municipal service modernization.',
            'biography_om' => 'Kabajamoo Obbo Kadiir Ahimad bulchiinsa magaalaa fi ikonoomiksii irratti barnoota olaanaa kan qaban yoo ta\'u, misooma bu\'uuraalee magaalaa Kombolchaa fi tajaajila hawaasaa fooyyessuu keessatti gahee olaanaa gumaachaniiru.',
            'portrait_path' => '/assets/mayor-portrait.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 8. Seed Cabinet & Council Members
        DB::table('leadership_members')->insert([
            [
                'type' => 'cabinet',
                'name_am' => 'ዶ/ር ተስፋዬ ብርሃኑ',
                'name_en' => 'Dr. Tesfaye Berhanu',
                'name_om' => 'Dr. Tasfaayee Birhaanuu',
                'position_am' => 'ምክትል ከንቲባ እና የንግድና ኢንዱስትሪ መምሪያ ኃላፊ',
                'position_en' => 'Deputy Mayor & Head of Trade & Industry',
                'position_om' => 'Itti Aanaa Kantiibaa fi Hogganaa Daldalaa fi Indaastirii',
                'phone' => '+251 33 551 0023',
                'email' => 'deputy.mayor@kombolchacity.gov.et',
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'cabinet',
                'name_am' => 'ወ/ሮ ዘይነባ ኡስማን',
                'name_en' => 'W/ro Zeyneba Usman',
                'name_om' => 'Aaddee Zaynab Usmaan',
                'position_am' => 'የከተማ ልማትና ኮንስትራክሽን መምሪያ ኃላፊ',
                'position_en' => 'Head of Urban Development & Construction',
                'position_om' => 'Hogganaa Misooma Magaalaa fi Ijaarsaa',
                'phone' => '+251 33 551 0411',
                'email' => 'construction.head@kombolchacity.gov.et',
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'cabinet',
                'name_am' => 'አቶ በላይ አስፋው',
                'name_en' => 'Ato Belay Asfaw',
                'name_om' => 'Obbo Balaay Asfaaw',
                'position_am' => 'የፋይናንስና ኢኮኖሚ ልማት መምሪያ ኃላፊ',
                'position_en' => 'Head of Finance & Economic Development',
                'position_om' => 'Hogganaa Faayinaansii fi Misooma Ikonoomii',
                'phone' => '+251 33 551 0046',
                'email' => 'finance.head@kombolchacity.gov.et',
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'council',
                'name_am' => 'አቶ መሀመድ ሰኢድ',
                'name_en' => 'Ato Mohammed Seid',
                'name_om' => 'Obbo Mahaammad Sa\'iid',
                'position_am' => 'የከተማው ምክር ቤት አፈ-ጉባኤ',
                'position_en' => 'Speaker of the City Council',
                'position_om' => 'Afa-Gaa\'ii Mana Maree Magaalaa',
                'phone' => '+251 33 551 0088',
                'email' => 'council.speaker@kombolchacity.gov.et',
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'council',
                'name_am' => 'ወ/ሮ ታሪኳ ኃይሌ',
                'name_en' => 'W/ro Tarikua Haile',
                'name_om' => 'Aaddee Taarikwaa Haayilee',
                'position_am' => 'የማህበራዊ ጉዳዮች ቋሚ ኮሚቴ ሰብሳቢ',
                'position_en' => 'Chairperson of Social Affairs Standing Committee',
                'position_om' => 'Dura Taa\'aa Koree Dhaabbii Dhimmoota Hawaasummaa',
                'phone' => '+251 33 551 0089',
                'email' => 'social.council@kombolchacity.gov.et',
                'order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 9. Seed News Articles
        DB::table('news_articles')->insert([
            [
                'title_am' => 'በኮምቦልቻ የኢንዱስትሪ ፓርክ 4 አዳዲስ የማኑፋክቸሪንግ ፋብሪካዎች ወደ ስራ ገቡ',
                'title_en' => 'Four New Manufacturing Plants Inaugurated at Kombolcha Industrial Park',
                'title_om' => 'Paarkii Indaastirii Kombolchaa Keessatti Warshaaleen Haaraa Afur Hojii Jalqaban',
                'slug' => 'industrial-park-expansion-2026',
                'category' => 'Investment',
                'summary_am' => 'ከ 2,500 በላይ ለሚሆኑ ወጣቶች ቋሚ የስራ እድል የፈጠሩ ዘመናዊ የጨርቃ ጨርቅና አግሮ-ፕሮሰሲንግ ፋብሪካዎች በይፋ ስራ ጀመሩ።',
                'summary_en' => 'State-of-the-art textile and agro-processing plants begin export operations, generating 2,500 direct jobs.',
                'summary_om' => 'Warshaaleen huccuu fi omisha qonnaa qindeessan hojii jalqabuun dargaggoota 2,500 oliif carraa hojii uumaniiru.',
                'content_am' => 'የኮምቦልቻ ከተማ አስተዳደር ከኢንዱስትሪ ፓርኮች ልማት ኮርፖሬሽን ጋር በመተባበር ያስገነባቸው 4 አዳዲስ ፋብሪካዎች ዛሬ በደመቀ ስነ-ስርዓት ተመርቀዋል። በእለቱ የከተማው ከንቲባ፣ የፌደራል ሚኒስትሮች እና የውጭ ባለሀብቶች ተገኝተዋል። ፋብሪካዎቹ ለሀገር ውስጥ ገበያ ከማቅረብ ባሻገር ለውጭ ገበያ የሚላኩ ምርቶችን በማምረት የውጭ ምንዛሪ ያስገኛሉ ተብሎ ይጠበቃል።',
                'content_en' => 'The Kombolcha City Administration, in partnership with the Industrial Parks Development Corporation, has officially commissioned four advanced manufacturing complexes today. The event was attended by municipal leadership, regional dignitaries, and international trade partners.',
                'content_om' => 'Bulchiinsi Magaalaa Kombolchaa dhaabbata paarkii indaastirii waliin ta\'uun warshaalee haaraa 4 eebbisiisee jira.',
                'thumbnail_path' => '/assets/news-industry.jpg',
                'is_featured' => true,
                'status' => 'published',
                'views_count' => 1420,
                'published_at' => Carbon::now()->subDays(2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title_am' => 'የ 15 ኪሎ ሜትር የከተማ ውስጥ አስፋልት መንገድ ግንባታ 85 በመቶ ደረሰ',
                'title_en' => 'Construction of 15-Kilometer City Ring Asphalt Road Reaches 85% Completion',
                'title_om' => 'Ijaarsi Daandii Asfaaltii Magaalaa Keessaa Kiilomeetira 15 Dhibbantaa 85 Irra Ga\'e',
                'slug' => 'asphalt-road-construction-progress',
                'category' => 'Infrastructure',
                'summary_am' => 'አቢሻገርን እና ቦርከና ክፍለ ከተሞችን የሚያገናኘው ዋናው የትራንስፖርት ኮሪደር በመጪው ወር ሙሉ በሙሉ ይጠናቀቃል።',
                'summary_en' => 'The pivotal bypass corridor linking Abishager and Borkena sub-cities is on schedule for final commissioning next month.',
                'summary_om' => 'Daandiin kutaa magaalaa Abishaager fi Borkenaa walqunnamsiisu ji\'a dhufu keessatti ni xumurama.',
                'content_am' => 'የመንገዶች ባለስልጣን ባቀረበው ሪፖርት መሰረት በከተማዋ የትራፊክ መጨናነቅን በዘላቂነት የሚፈታው ይህ ሜጋ ፕሮጀክት የመንገድ ዳር መብራቶች እና የፍሳሽ ቦይ ግንባታዎችን ጨምሮ ወደ ፍፃሜው ተቃርቧል።',
                'content_en' => 'According to the Roads Directorate update, the strategic bypass project includes pedestrian walkways, energy-efficient LED street lighting, and underground stormwater management systems.',
                'content_om' => 'Ijaarsi daandii kun tajaajila ibsaa fi booyii dhangala\'aa guutuu of keessaa qaba.',
                'thumbnail_path' => '/assets/news-roads.jpg',
                'is_featured' => true,
                'status' => 'published',
                'views_count' => 980,
                'published_at' => Carbon::now()->subDays(4),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title_am' => 'በኮምቦልቻ ከተማ አስተዳደር ዘመናዊ የዲጂታል ታክስ እና አገልግሎት ስርዓት ተጀመረ',
                'title_en' => 'Kombolcha City Launches Unified Digital Municipal Revenue & Tax Portal',
                'title_om' => 'Magaalaan Kombolchaa Sirna Kaffaltii Gibiraa fi Galii Dijitaalaa Hojiirra Oolchite',
                'slug' => 'digital-tax-portal-launch',
                'category' => 'Governance',
                'summary_am' => 'ነጋዴዎችና ነዋሪዎች የከተማ ታክስና የአገልግሎት ክፍያዎችን በቴሌብር እና በኢ-ባንኪንግ በቅጽበት መክፈል ጀመሩ።',
                'summary_en' => 'Citizens and commercial merchants can now settle municipal licensing fees and property taxes digitally via Telebirr and banking apps.',
                'summary_om' => 'Hawaasni kaffaltii tajaajilaa fi gibira magaalaa karaa Telebirr fi baankii dijitaalaatiin raawwachuu danda\'u.',
                'content_am' => 'የገቢዎች ባለስልጣን እና የኢንፎርሜሽን ኮሙኒኬሽን መምሪያ በጋራ ያበለፀጉት ይህ አዲስ አሰራር ሙስናንና ወረፋን በማስቀረት አገልግሎቱን ቀልጣፋ ያደርገዋል።',
                'content_en' => 'Jointly developed by the Revenues Authority and ICT Directorate, the platform eliminates bureaucratic wait times and improves financial transparency.',
                'content_om' => 'Sirni kun malaammaltummaa hambisuu fi hojii saffisiisuuf gargaara.',
                'thumbnail_path' => '/assets/news-digital.jpg',
                'is_featured' => false,
                'status' => 'published',
                'views_count' => 640,
                'published_at' => Carbon::now()->subDays(7),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 10. Seed Projects
        DB::table('projects')->insert([
            [
                'title_am' => 'የኮምቦልቻ ዘመናዊ የህዝብ ትራንስፖርት ተርሚናል ግንባታ',
                'title_en' => 'Construction of Modern Intercity Bus Transit Terminal',
                'title_om' => 'Ijaarsa Buufata Konkolaattota Geejjibaa Ammayyaa Kombolchaa',
                'slug' => 'bus-terminal-project',
                'budget_etb' => 125000000.00,
                'funding_source' => 'City Treasury & Federal Infrastructure Grant',
                'contractor_name' => 'Wollo Construction Enterprise',
                'supervising_engineer' => 'Eng. Daniel Mekonnen',
                'progress_percent' => 78,
                'start_date' => '2025-01-10',
                'target_completion_date' => '2026-12-30',
                'status' => 'in_progress',
                'description_am' => 'በቀን ከ 30,000 በላይ ተሳፋሪዎችን እና 400 ተሽከርካሪዎችን የማስተናገድ አቅም ያለው ባለ 3 ወለል ዘመናዊ ተርሚናል',
                'description_en' => 'High-capacity terminal designed for 30,000 daily commuters with automated ticketing and commercial bays.',
                'description_om' => 'Buufata ammayyaa guyyaatti imaltoota 30,000 tajaajiluu danda\'u.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title_am' => 'የቦርከና ወንዝ የተፋሰስ ልማትና የጎርፍ መከላከያ ግንባታ',
                'title_en' => 'Borkena River Watershed Regeneration & Flood Mitigation Project',
                'title_om' => 'Misooma Qarqara Laga Borkenaa fi Ittisa Loolaatiif Ijaarame',
                'slug' => 'borkena-flood-mitigation',
                'budget_etb' => 84000000.00,
                'funding_source' => 'Climate Resilient Urban Development Fund',
                'contractor_name' => 'National Water Works Construction',
                'supervising_engineer' => 'Eng. Hiwot Alemayehu',
                'progress_percent' => 92,
                'start_date' => '2024-09-01',
                'target_completion_date' => '2026-11-15',
                'status' => 'in_progress',
                'description_am' => 'የቦርከናን ወንዝ ዳርቻ በማልማት፣ የመዝናኛ ፓርክ እና የጎርፍ መከላከያ ግንቦችን በመገንባት ከተማዋን ከአደጋ የመጠበቅ ስራ',
                'description_en' => 'Comprehensive retaining walls, green riverfront promenades, and stormwater management systems.',
                'description_om' => 'Laga Borkenaa qulqulleessuu fi hawaasa loolaa irraa eeguuf hojjetame.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title_am' => 'የኮምቦልቻ ከተማ አስተዳደር ዘመናዊ ዳታ ሴንተርና የኦፕቲካል ፋይበር ዝርጋታ',
                'title_en' => 'Municipal Tier-III Data Center & Fiber Optic Backbone Integration',
                'title_om' => 'Wiirtuu Daataa Magaalaa fi Sarara Faayibar Optikii Diriirsuu',
                'slug' => 'data-center-fiber-project',
                'budget_etb' => 45000000.00,
                'funding_source' => 'Municipal Innovation Budget',
                'contractor_name' => 'Ethio Telecom Engineering Partnership',
                'supervising_engineer' => 'Eng. Bereket Tadesse',
                'progress_percent' => 100,
                'start_date' => '2025-03-01',
                'target_completion_date' => '2026-08-30',
                'status' => 'completed',
                'description_am' => 'ሁሉንም 22 የመንግስት መስሪያ ቤቶች እና 4 ክፍለ ከተሞችን በከፍተኛ ፍጥነት ኔትወርክ የሚያስተሳስር የዳታ ማዕከል',
                'description_en' => 'Fully operational backbone linking all 22 municipal offices and 4 sub-cities for digital administration.',
                'description_om' => 'Waajjiraalee 22 fi kutaalee magaalaa 4 sarara interneetii saffisaatiin walqunnamsiiseera.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 11. Seed Resources (Directives, Master Plans, Bylaws)
        DB::table('resources')->insert([
            [
                'title_am' => 'የኮምቦልቻ ከተማ የ 10 ዓመት መሪ ማስተር ፕላን (2020-2030)',
                'title_en' => 'Kombolcha City 10-Year Master Spatial Development Plan',
                'title_om' => 'Pilaanii Misooma Magaalaa Kombolchaa Waggaa 10',
                'category' => 'Master Plan',
                'year' => 2026,
                'file_path' => '/documents/kombolcha_master_plan_2030.pdf',
                'file_extension' => 'pdf',
                'file_size_formatted' => '14.2 MB',
                'downloads_count' => 1840,
                'description_am' => 'የከተማዋን የመሬት አጠቃቀም፣ የኢንዱስትሪ ቀጣናዎችንና የመኖሪያ ሰፈሮችን የሚያሳይ ዝርዝር ካርታና ሰነድ።',
                'description_en' => 'Comprehensive spatial master zoning guidelines, residential layouts, and manufacturing corridor allocations.',
                'description_om' => 'Pilaanii itti fayyadama lafa magaalaa fi misooma indaastirii kan agarsiisu.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title_am' => 'የከተማ መሬት ይዞታ ምዝገባ እና የካርታ አሰጣጥ መመሪያ ቁጥር 12/2018',
                'title_en' => 'Urban Land Tenure Registration and Cadastre Directive No. 12/2026',
                'title_om' => 'Qajeelfama Galmee Qabiyyee Lafa Magaalaa Lakk. 12/2026',
                'category' => 'Directives',
                'year' => 2026,
                'file_path' => '/documents/land_cadastre_directive_12.pdf',
                'file_extension' => 'pdf',
                'file_size_formatted' => '2.8 MB',
                'downloads_count' => 930,
                'description_am' => 'ህጋዊ የይዞታ ማረጋገጫ ካርታ ለማግኘት የሚጠበቁ ህጎችና የአሰራር መመሪያዎች።',
                'description_en' => 'Regulatory guidelines for property boundary verification, title deed transfers, and cadastre registration.',
                'description_om' => 'Qajeelfama waraqaa ragaa abbaa qabiyyummaa lafaa argachuuf barbaachisu.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title_am' => 'የኮምቦልቻ ከተማ አስተዳደር የ 2018 ዓ.ም ዓመታዊ በጀት እና የልማት እቅድ',
                'title_en' => 'Fiscal Year 2026/27 Approved Municipal Budget & Action Plan',
                'title_om' => 'Baajata Waggaa fi Karoora Misoomaa Bulchiinsa Magaalaa 2026/27',
                'category' => 'Annual Reports',
                'year' => 2026,
                'file_path' => '/documents/kombolcha_budget_2026.pdf',
                'file_extension' => 'pdf',
                'file_size_formatted' => '5.1 MB',
                'downloads_count' => 745,
                'description_am' => 'በምክር ቤቱ የጸደቀው የከተማው አመታዊ ገቢ፣ ወጪ እና የካፒታል ፕሮጀክቶች በጀት ሰነድ።',
                'description_en' => 'City Council ratified annual revenue targets, departmental capital expenditures, and development allocations.',
                'description_om' => 'Baajata bu\'uuraalee misoomaa fi tajaajila hawaasaaf qoodame kan of keessaa qabu.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title_am' => 'የንግድ ምዝገባና ፈቃድ አሰጣጥ የአገልግሎት ቻርተር እና ቅጾች',
                'title_en' => 'Commercial Trade Registration & Licensing Citizen Charter & Forms',
                'title_om' => 'Chaartara Tajaajila Galmee Daldalaa fi Heeyyamaa',
                'category' => 'Forms',
                'year' => 2026,
                'file_path' => '/documents/trade_license_charter_forms.pdf',
                'file_extension' => 'pdf',
                'file_size_formatted' => '1.5 MB',
                'downloads_count' => 1290,
                'description_am' => 'አዲስ የንግድ ስራ ለመጀመር እና ነባር ፈቃድ ለማደስ የሚሞሉ ቅጾችና መመሪያዎች።',
                'description_en' => 'Downloadable application templates and step checklist for commercial business registration.',
                'description_om' => 'Uunkaalee heeyyama daldalaa haaraa fi haaromsaaf guutamu.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 12. Seed Vacancies
        DB::table('vacancies')->insert([
            [
                'title_am' => 'ከፍተኛ የከተማ ሲቪል መሀንዲስ (Senior Civil Engineer)',
                'title_en' => 'Senior Urban Civil Infrastructure Engineer',
                'title_om' => 'Injinara Siiviilii Misooma Magaalaa Olaanaa',
                'position_code' => 'KMB-ENG-042',
                'employment_type' => 'Full-Time',
                'salary_grade' => 'PS-10 (14,250 ETB + Allowance)',
                'requirements_am' => 'በሲቪል ኢንጂነሪንግ ወይም ኮንስትራክሽን ማኔጅመንት የመጀመሪያ ዲግሪ እና 4 ዓመት አግባብ ያለው የስራ ልምድ። የኮምፒውተር ሶፍትዌሮች (AutoCAD, Civil 3D) እውቀት ወሳኝ ነው።',
                'requirements_en' => 'BSc Degree in Civil Engineering or Construction Technology Management with at least 4 years of proven field experience in road and drainage supervision.',
                'requirements_om' => 'Digrii jalqabaa Injinariingii Siiviiliitiin kan qabu fi muuxannoo hojii waggaa 4 kan qabu.',
                'deadline' => Carbon::now()->addDays(12),
                'status' => 'open',
                'application_guide_am' => 'የትምህርትና የስራ ልምድ ማስረጃ ዋናውንና የማይመለስ ፎቶ ኮፒ በመያዝ በከተማው ሲቪል ሰርቪስ መምሪያ ቢሮ ቁጥር 105 በግንባር በመቅረብ መመዝገብ ይችላሉ።',
                'application_guide_en' => 'Submit original and photocopies of educational and experience certificates in person at Civil Service Department Room 105.',
                'application_guide_om' => 'Ragaalee barnootaa fi muuxannoo qabachuun waajjira tajaajila siiviiliitti kutaa 105 dhiyaachuun galmaa\'aa.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title_am' => 'የዳታቤዝ አስተዳዳሪና የአይቲ ባለሙያ (Database Administrator)',
                'title_en' => 'Database Administrator & Network Systems Officer',
                'title_om' => 'Ogeessa Bulchiinsa Daataabeasii fi Neetiwoorkii',
                'position_code' => 'KMB-ICT-018',
                'employment_type' => 'Full-Time',
                'salary_grade' => 'PS-09 (11,800 ETB + Technical Allowance)',
                'requirements_am' => 'በኮምፒውተር ሳይንስ፣ ኢንፎርሜሽን ቴክኖሎጂ ወይም ተዛማጅ ዘርፍ የመጀመሪያ ዲግሪ እና 2 ዓመት ልምድ። በ MySQL, Linux ሰርቨሮች እና ኔትወርኪንግ ክህሎት ያለው።',
                'requirements_en' => 'BSc in Computer Science, Software Engineering, or Information Systems with minimum 2 years experience managing relational databases and enterprise Linux networks.',
                'requirements_om' => 'Digrii Kompiitara Saayinsii ykn Teeknolojii Odeeffannootiin fi muuxannoo waggaa 2.',
                'deadline' => Carbon::now()->addDays(8),
                'status' => 'open',
                'application_guide_am' => 'የማመልከቻ ሰነዶችዎን በማዘጋጀት በማዘጋጃ ቤት ህንፃ 1 ቢሮ ቁጥር 102 ማስገባት ይቻላል።',
                'application_guide_en' => 'Submit CV and credentials to the Public Relations & ICT Directorate, Municipal Hall 1 Room 102.',
                'application_guide_om' => 'Galmee ragaalee keessanii waajjira ICTtti dhiyeessaa.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 13. Seed Civic Opinion Poll
        $pollId = DB::table('polls')->insertGetId([
            'question_am' => 'ለ 2018/19 በጀት ዓመት የኮምቦልቻ ከተማ አስተዳደር ቅድሚያ ሰጥቶ ሊገነባቸው የሚገቡ የመሰረተ ልማት ዘርፎች የትኞቹ ናቸው?',
            'question_en' => 'Which municipal infrastructure sector should receive highest investment priority in Kombolcha for the upcoming fiscal cycle?',
            'question_om' => 'Bara baajataa dhufuuf bulchiinsi magaalaa Kombolchaa dameelee misoomaa keessaa kamiif dursa kennuu qaba?',
            'description_am' => 'የህዝብ አስተያየት በከተማዋ በጀት ድልድል እና የፕሮጀክት እቅድ ላይ ቀጥተኛ ተፅዕኖ አለው። ድምጽዎን ይስጡ!',
            'description_en' => 'Citizen participation shapes municipal budgetary decisions and capital resource allocation. Cast your vote!',
            'description_om' => 'Yaadni ummataa karoora baajata magaalaa keessatti gahee qaba. Sagalee keessan kennaa!',
            'expires_at' => Carbon::now()->addDays(30),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('poll_options')->insert([
            ['poll_id' => $pollId, 'option_text_am' => 'የአስፋልት መንገዶች ጥገና እና የፍሳሽ ቦይ ማስፋፊያ', 'option_text_en' => 'Asphalt Road Upgrades & Comprehensive Drainage', 'option_text_om' => 'Suphaa Daandii Asfaaltii fi Booyii Dhangala\'aa', 'votes_count' => 412, 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['poll_id' => $pollId, 'option_text_am' => 'የንፁህ መጠጥ ውሃ አቅርቦትና የማጣሪያ ጣቢያዎች ግንባታ', 'option_text_en' => 'Potable Drinking Water Supply & Filtration Reservoirs', 'option_text_om' => 'Dhiyeessii Bishaan Dhugaatii Qulqulluu', 'votes_count' => 684, 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['poll_id' => $pollId, 'option_text_am' => 'የሶላር የመንገድ መብራቶች እና የሰላምና ጸጥታ ቁጥጥር ካሜራዎች', 'option_text_en' => 'Solar Street Lighting & Smart Security Infrastructure', 'option_text_om' => 'Ibsaa Daandii Soolaaraa fi Nageenya Eeguu', 'votes_count' => 295, 'order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['poll_id' => $pollId, 'option_text_am' => 'የህዝብ መዝናኛ ፓርኮች፣ የህፃናት መጫወቻ እና ስታዲየም ልማት', 'option_text_en' => 'Public Recreational Parks, Green Zones & Youth Stadiums', 'option_text_om' => 'Paarkii Bashannanaa fi Ispoortii Dargaggootaa', 'votes_count' => 178, 'order' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 14. Seed Internal Staff Board
        $topicId = DB::table('internal_board_topics')->insertGetId([
            'title_am' => 'የ 3ኛው ሩብ ዓመት የማዘጋጃ ቤት የስራ አፈፃፀም ግምገማ መርሃ ግብር',
            'title_en' => 'Q3 Municipal Performance & Public Service Evaluation Assembly',
            'title_om' => 'Sagantaa Gamaggama Hojii Kurmaana 3ffaa Bulchiinsa Magaalaa',
            'category' => 'Notices',
            'is_pinned' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('internal_board_posts')->insert([
            [
                'topic_id' => $topicId,
                'author_id' => 1,
                'author_name' => 'የከንቲባ ጽ/ቤት ኃላፊ',
                'author_department' => "Mayor's Executive Office",
                'content_am' => 'ለሁሉም የ 22ቱ መምሪያዎች ኃላፊዎች እና የ 4ቱ ክፍለ ከተማ ዋና ስራ አስፈፃሚዎች በሙሉ፡ የ 3ኛው ሩብ ዓመት እቅድ አፈፃፀም ሪፖርት እስከ መጪው አርብ ድረስ በዳታቤዙ በኩል እንዲገባ እና በሚቀጥለው ሰኞ በሚካሄደው የጋራ ግምገማ ላይ በአካል እንዲቀርቡ እናሳስባለን።',
                'content_en' => 'To all 22 Directorate Heads and 4 Sub-City Chief Executives: Please submit your Q3 performance reports via the municipal portal by Friday. The executive appraisal meeting will convene Monday morning at Main Conference Hall.',
                'content_om' => 'Hogganoota waajjiraalee 22 fi kutaalee magaalaa 4 hundaaf: Gabaasa hojii kurmaana 3ffaa hanga Jimaataatti dhiyeessaa.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
