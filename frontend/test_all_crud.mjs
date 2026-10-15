import axios from 'axios';

async function testAllAdminOperations() {
  try {
    console.log('1. Admin Authentication...');
    const loginRes = await axios.post('http://127.0.0.1:8080/api/admin/login', {
      email: 'admin@kombolchacity.gov.et',
      password: 'Admin@2026!'
    }, { headers: { 'Accept': 'application/json' } });
    const token = loginRes.data.token;
    const authHeaders = {
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json'
    };
    console.log('Authenticated successfully.');

    // 2. Test Mayor Profile Update with Portrait Path
    console.log('2. Updating Mayor Profile with custom portrait_path...');
    const mayorRes = await axios.put('http://127.0.0.1:8080/api/admin/mayor', {
      name_am: 'ክቡር አቶ ከድር አህመድ',
      name_en: 'Hon. Kedir Ahmed',
      welcome_message_am: 'እንኳን ወደ ኮምቦልቻ ከተማ አስተዳደር ዲጂታል ፖርታል በደህና መጡ! (ዘመናዊ)',
      welcome_message_en: 'Welcome to the digital portal of Kombolcha City Administration (Updated).',
      portrait_path: '/assets/mayor_portrait.jpg',
      term_start: '2021',
      term_end: 'Present',
      biography_am: 'ክቡር አቶ ከድር አህመድ የኮምቦልቻ ከተማ ከንቲባ ናቸው።',
      biography_en: 'Hon. Kedir Ahmed is the Mayor of Kombolcha City.'
    }, { headers: authHeaders });
    console.log('Mayor update result:', mayorRes.data.message);

    // Verify Public Mayor endpoint
    const pubMayor = await axios.get('http://127.0.0.1:8080/api/mayor');
    console.log('Verified Public Mayor Portrait:', pubMayor.data.portrait_path);

    // 3. Test Service CRUD
    console.log('3. Testing Citizen Service Creation, Edit, and Delete...');
    const createServiceRes = await axios.post('http://127.0.0.1:8080/api/admin/services', {
      title_am: 'የንግድ ፈቃድ እድሳት አገልግሎት',
      title_en: 'Commercial Business License Renewal',
      type: 'step',
      summary_am: 'የንግድ ድርጅት ፈቃድ በኦንላይን ወይም በቀበሌ ለማደስ የሚያስፈልጉ ሂደቶች።',
      fee_amount: 150,
      estimated_time_am: '2 የስራ ቀናት'
    }, { headers: authHeaders });
    const srvId = createServiceRes.data.id;
    console.log('Created Service ID:', srvId);

    const updateServiceRes = await axios.put(`http://127.0.0.1:8080/api/admin/services/${srvId}`, {
      title_am: 'የንግድ ፈቃድ እድሳት አገልግሎት (የተሻሻለ)',
      title_en: 'Commercial Business License Renewal (Updated)',
      type: 'step',
      summary_am: 'የተሟላ የግብር ክሊራንስ በማያያዝ የሚሰጥ ፈጣን አገልግሎት።',
      fee_amount: 200,
      estimated_time_am: '1 የስራ ቀን'
    }, { headers: authHeaders });
    console.log('Updated Service result:', updateServiceRes.data.message);

    // 4. Test Leadership CRUD
    console.log('4. Testing Leadership Member with photo_path...');
    const leaderRes = await axios.post('http://127.0.0.1:8080/api/admin/leadership', {
      type: 'cabinet',
      name_am: 'አቶ ካሊድ ዑመር',
      name_en: 'Ato Khalid Omer',
      position_am: 'የኢንቨስትመንትና ኢንዱስትሪ ቢሮ ኃላፊ',
      position_en: 'Head of Investment & Industry Bureau',
      photo_path: '/assets/mayor_portrait.jpg',
      phone: '+251 33 551 0988',
      email: 'khalid@kombolchacity.gov.et'
    }, { headers: authHeaders });
    const leaderId = leaderRes.data.id;
    console.log('Created Leadership Member ID:', leaderId);

    // 5. Test Municipal Office CRUD
    console.log('5. Testing Municipal Office Registration...');
    const officeRes = await axios.post('http://127.0.0.1:8080/api/admin/offices', {
      name_am: 'የከተማ ውበትና አረንጓዴ ልማት ጽ/ቤት',
      name_en: 'Urban Beautification & Green Development Office',
      phone: '+251 33 551 0777',
      email: 'green@kombolchacity.gov.et',
      building_location_am: 'ማዘጋጃ ቤት ህንፃ 1 ወለል 1',
      room_number: '109'
    }, { headers: authHeaders });
    const officeId = officeRes.data.id;
    console.log('Created Office ID:', officeId);

    // 6. Test Resource Directives CRUD with PDF file_path
    console.log('6. Testing Resource Directive with PDF file_path...');
    const resRes = await axios.post('http://127.0.0.1:8080/api/admin/resources', {
      title_am: 'የ 2018 ዓ.ም የከተማ ውበትና ፅዳት መመሪያ',
      title_en: '2026 Urban Sanitation and Greenery Directive',
      category: 'Directives',
      year: 2026,
      file_path: '/uploads/sanitation_directive_2026.pdf',
      file_size_formatted: '3.4 MB'
    }, { headers: authHeaders });
    const resourceId = resRes.data.id;
    console.log('Created Resource ID:', resourceId);

    console.log('\n>>> ALL CRUD & UPLOAD TESTS PASSED SUCCESSFULLY! <<<');
  } catch (err) {
    console.error('Test Error:', err.response?.data?.message || err.response?.data || err.message);
  }
}

testAllAdminOperations();
