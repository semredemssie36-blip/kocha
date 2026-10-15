USE kombolcha_city_db;

DELETE FROM hero_slides;

INSERT INTO hero_slides 
(image_path, tag_am, tag_en, tag_om, title_am, title_en, title_om, subtitle_am, subtitle_en, subtitle_om, button_text_am, button_text_en, button_text_om, button_url, `order`, is_active, created_at, updated_at) 
VALUES 
('/assets/hero_industry.jpg', 
 'የማኑፋክቸሪንግና የወጪ ንግድ ማዕከል', 
 'National Manufacturing & Logistics Hub', 
 'Wiirtuu Misooma Indaastirii fi Daldalaa', 
 'የኮምቦልቻ ኢንዱስትሪ ፓርክ እና የንግድ መናኸሪያ', 
 'Kombolcha Industrial Park & Strategic Commercial Center', 
 'Paarkii Indaastirii Kombolchaa fi Wiirtuu Daldala Addunyaa', 
 'ለአገር ውስጥ እና ለዓለም አቀፍ ባለሀብቶች ምቹ የመሰረተ ልማት ዝርጋታ፣ ደረቅ ወደብ እና ዘመናዊ የማምረቻ ሼዶች።', 
 'A premier eco-industrial park, dry port customs terminal, and freight corridor driving Ethiopia’s economic transformation.', 
 'Abbootii qabeenyaa biyya keessaa fi alaa tiif bu\'uuraalee misoomaa ammayyaa fi tajaajila saffisaa.', 
 'የኢንቨስትመንት እድሎችን ያስሱ', 
 'Explore Investment Opportunities', 
 'Carraawwan Invastimantii Daawwadhaa', 
 '/explore', 
 1, 1, NOW(), NOW()),

('/assets/hero_aerial.jpg', 
 'የከተማ ልማትና ዘመናዊ አስተዳደር', 
 'Metropolitan Urban Administration', 
 'Misooma Magaalaa fi Bulchiinsa Ammayyaa', 
 'በቴክኖሎጂ የታገዘ ፈጣንና ግልጽ የከተማ አስተዳደር አገልግሎት', 
 'Smart Municipal Governance & Citizen-First Public Services', 
 'Bulchiinsa Magaalaa Saffisaa fi Iftoomina Qabu', 
 'የ 4ቱ ክፍለ ከተሞችና 22ቱ መምሪያዎች ይፋዊ መመሪያዎች፣ የቀበሌ መታወቂያ እና ፈቃድ አሰጣጥ ያለ ወረፋ።', 
 'Connecting 4 Sub-Cities and 22 municipal directorates with transparent civic workflows and zero delays.', 
 'Tajaajilaalee kutaalee magaalaa 4 fi waajjiraalee sekteeraa 22 salphaatti argadhaa.', 
 'የዜጎች አገልግሎቶችን ይመልከቱ', 
 'Access Citizen Services', 
 'Tajaajilaalee Lammiilee', 
 '/services', 
 2, 1, NOW(), NOW()),

('/assets/mayor_portrait.jpg', 
 'የከንቲባ መልእክትና አመራር', 
 'Mayoral Leadership & Civic Vision', 
 'Ergaa fi Geggeessummaa Kantiibaa', 
 'ለነዋሪዎች ምቹ፣ ለአካባቢ ተስማሚና የበለጸገች ኮምቦልቻ', 
 'Building a Prosperous, Liveable & Inclusive Kombolcha', 
 'Kombolcha Magaalaa Qulqulluu fi Misoomaa Taasisuu', 
 'ህዝባዊ ተሳትፎንና ግልጽነትን መሰረት በማድረግ ከተማችንን ወደ ላቀ ደረጃ እናሸጋግራለን።', 
 'Working hand-in-hand with our citizens, industrial pioneers, and youth to build modern municipal governance.', 
 'Hawaasa keenya waliin ta\'uun magaalaa keenya gara fuulduraatti tarkaanfachiisna.', 
 'የከንቲባ ጽሕፈት ቤትን ይጎብኙ', 
 'Office of the Mayor', 
 'Waajjira Kantiibaa', 
 '/mayor', 
 3, 1, NOW(), NOW());
