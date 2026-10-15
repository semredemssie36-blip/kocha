<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('destinations')->count() > 0) {
            return;
        }

        $items = [
            [
                'category' => 'nature',
                'title_en' => 'Mount Yegof Nature Reserve & Forest',
                'title_am' => 'የይጎፍ ተራራ የተፈጥሮ ደንና የመስህብ ስፍራ',
                'title_om' => 'Tulluu Yagoof - Bosona Uumamaa fi Bakka Hawwataa',
                'location_en' => 'Bordering Kombolcha South Corridor',
                'location_am' => 'በኮምቦልቻ ደቡባዊ መግቢያ',
                'location_om' => 'Kallattii Kibba Magaalaa Kombolchaa',
                'summary_en' => 'Majestic mountain peak towering at 3,014 meters with dense indigenous juniper forests, hiking trails, and panoramic vistas of the entire Borkena valley.',
                'summary_am' => 'በ 3,014 ሜትር ከፍታ ላይ የሚገኝ፣ አገር በቀል ዛፎችን የያዘ፣ ለተራራ መውጣትና ለሽርሽር ምቹ የሆነ ውብ ተፈጥሯዊ ገጽታ።',
                'summary_om' => 'Tulluu olka\'iinsa meetira 3,014 qabu, bosona uumamaa fi bakka daawwannaaf mijatuudha.',
                'image_path' => null,
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'industry',
                'title_en' => 'Kombolcha Industrial Park (KIP)',
                'title_am' => 'የኮምቦልቻ ኢንዱስትሪ ፓርክ',
                'title_om' => 'Paarkii Indaastirii Kombolchaa',
                'location_en' => 'Biraro Sub-City, Industrial Corridor',
                'location_am' => 'ቢራሮ ክፍለ ከተማ፣ የኢንዱስትሪ ኮሪደር',
                'location_om' => 'Kutaa Magaalaa Biraaroo',
                'summary_en' => 'A premier 75-hectare eco-industrial hub housing leading global textile, apparel, and manufacturing export enterprises with plug-and-play sheds.',
                'summary_am' => 'በ 75 ሄክታር መሬት ላይ የተገነባ፣ ለውጭ ገበያ የሚላኩ የጨርቃ ጨርቅና የማኑፋክቸሪንግ ምርቶችን የሚያመርቱ አለም አቀፍ ፋብሪካዎች ስብስብ።',
                'summary_om' => 'Lafa heektaara 75 irratti kan ijaarame, warshaalee oomisha alaa qaban kan hammateedha.',
                'image_path' => null,
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'nature',
                'title_en' => 'Borkena Green Riverfront Park',
                'title_am' => 'የቦርከና ወንዝ አረንጓዴ መዝናኛ ፓርክ',
                'title_om' => 'Paarkii Bashannanaa Qarqara Laga Borkenaa',
                'location_en' => 'Borkena Sub-City Promenade',
                'location_am' => 'ቦርከና ክፍለ ከተማ የወንዝ ዳርቻ',
                'location_om' => 'Qarqara Laga Borkanaa',
                'summary_en' => 'Urban revitalization green belt featuring scenic riverfront walkways, recreational zones, sports fields, and family-friendly picnic gardens.',
                'summary_am' => 'በቦርከና ወንዝ ዳርቻ የተገነባ ዘመናዊ የመዝናኛ ፓርክ፣ የእግር ጉዞ መንገዶችና የህፃናት መጫወቻ ስፍራዎች።',
                'summary_om' => 'Paarkii magariisa ammayyaa qarqara lagaarratti ijaarame fi bakka bashannana maatiiti.',
                'image_path' => null,
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'culture',
                'title_en' => 'Wollo Cultural Handicraft & Spice Bazaar',
                'title_am' => 'የወሎ ባህላዊ የእጅ ጥበብና ቅመማ ቅመም ገበያ',
                'title_om' => 'Gabaa Aadaa fi Ogummaa Harka Walloo',
                'location_en' => 'Central Market Area',
                'location_am' => 'ማዕከላዊ የገበያ አደባባይ',
                'location_om' => 'Giddugala Gabaa',
                'summary_en' => 'Vibrant local bazaar showcasing authentic Wollo cultural handloom dresses, leather crafts, organic honey, and aromatic mountain herbs.',
                'summary_am' => 'የወሎ ባህላዊ የጥበብ አልባሳት፣ የእጅ ስራዎች፣ የተፈጥሮ ማር እና ቅመማ ቅመሞች የሚቀርቡበት ደማቅ የከተማዋ ገበያ።',
                'summary_om' => 'Gabaa uffata aadaa, hojii harka fi mi\'eessituu aadaa agarsiisuudha.',
                'image_path' => null,
                'order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'industry',
                'title_en' => 'Kombolcha Multi-Modal Dry Port & Logistics Terminal',
                'title_am' => 'የኮምቦልቻ ደረቅ ወደብና የሎጂስቲክስ ማዕከል',
                'title_om' => 'Buufata Gogaa fi Wiirtuu Lojistikii Kombolchaa',
                'location_en' => 'Addis Ababa Highway Exit',
                'location_am' => 'የአዲስ አበባ አውራ ጎዳና መውጫ',
                'location_om' => 'Baasa Daandii Finfinnee',
                'summary_en' => 'Crucial customs clearance depot connecting northern and central Ethiopia directly with the ports of Djibouti via railway and road freight.',
                'summary_am' => 'ከጅቡቲ ወደብ ጋር በቀጥታ የተሳሰረ፣ የጉምሩክ ክሊራንስና የኮንቴነር ማስተናገጃ ዘመናዊ ደረቅ ወደብ።',
                'summary_om' => 'Buufata lojistikii fi kiliiransii gumruukaa bu\'uuraa ta\'eedha.',
                'image_path' => null,
                'order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'industry',
                'title_en' => 'Kombolcha Domestic Airport (DSE/HADC)',
                'title_am' => 'የኮምቦልቻ አየር ማረፊያ',
                'title_om' => 'Buufata Xiyyaaraa Kombolchaa',
                'location_en' => 'Airfield Road, 5 km from Downtown',
                'location_am' => 'ከከተማው መሃል 5 ኪ.ሜ ርቀት ላይ',
                'location_om' => 'Giddugala Magaalaarraa KM 5',
                'summary_en' => 'Daily Ethiopian Airlines passenger and cargo flights connecting Kombolcha directly with Addis Ababa, Mekelle, and regional capitals.',
                'summary_am' => 'የኢትዮጵያ አየር መንገድ በየቀኑ በረራዎችን የሚያደርግበት፣ ከተማዋን ከመላው ሀገሪቱ ጋር የሚያስተሳስር ዘመናዊ አየር ማረፊያ።',
                'summary_om' => 'Buufata xiyyaaraa tajaajila balalii guyyaa guyyaa kennuudha.',
                'image_path' => null,
                'order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('destinations')->insert($items);
    }
}
