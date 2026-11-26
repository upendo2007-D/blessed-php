CREATE DATABASE IF NOT EXISTS blessed_sai
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE blessed_sai;

CREATE TABLE IF NOT EXISTS admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS page_content (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  page_key VARCHAR(40) NOT NULL,
  block_key VARCHAR(80) NOT NULL,
  title VARCHAR(255) NOT NULL DEFAULT '',
  body TEXT NOT NULL,
  UNIQUE KEY page_block (page_key, block_key)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS programs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  icon VARCHAR(30) NOT NULL DEFAULT '',
  title VARCHAR(180) NOT NULL,
  description TEXT NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  UNIQUE KEY program_title (title)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS admissions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  label VARCHAR(180) NOT NULL,
  title VARCHAR(180) NOT NULL,
  description TEXT NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  UNIQUE KEY admission_title (title)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS staff_members (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(180) NOT NULL,
  role VARCHAR(180) NOT NULL,
  initials VARCHAR(12) NOT NULL DEFAULT '',
  sort_order INT NOT NULL DEFAULT 0,
  UNIQUE KEY staff_name (name)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS news_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  published_at DATE NOT NULL,
  title VARCHAR(180) NOT NULL,
  description TEXT NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_published TINYINT(1) NOT NULL DEFAULT 1,
  UNIQUE KEY news_title_date (title, published_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS academic_courses (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(180) NOT NULL,
  description TEXT NOT NULL,
  details TEXT NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  UNIQUE KEY course_title (title)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS gallery_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  image_path VARCHAR(500) NOT NULL,
  alt_text VARCHAR(255) NOT NULL,
  caption VARCHAR(255) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_published TINYINT(1) NOT NULL DEFAULT 1,
  UNIQUE KEY gallery_image (image_path(191))
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contact_messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(180) NOT NULL,
  email VARCHAR(190) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  INDEX message_date (created_at)
) ENGINE=InnoDB;

INSERT IGNORE INTO page_content (page_key, block_key, title, body) VALUES
  ('home', 'hero_eyebrow', '', 'Welcome to our school'),
  ('home', 'hero', 'Lugazi Progressive Secondary School', 'Nurturing academic excellence, character, and a strong sense of community since 1998.'),
  ('home', 'hero_primary_link', 'Learn More', ''),
  ('home', 'hero_secondary_link', 'Contact Us', ''),
  ('home', 'programs', 'Academic Programmes', 'We offer comprehensive O-Level and A-Level programmes designed to build knowledge, confidence, and leadership.'),
  ('home', 'admissions_eyebrow', '', 'Admissions'),
  ('home', 'admissions', 'Apply to join Lugazi Progressive', 'Open admissions for the next academic year are now underway. Submit your application and discover our supportive learning environment.'),
  ('home', 'team_eyebrow', '', 'Our team'),
  ('home', 'team', 'Meet our leaders and teachers', 'Our faculty combines experienced educators with student-centered support staff to create an engaging academic community.'),
  ('home', 'news_eyebrow', '', 'News & events'),
  ('home', 'news', 'Latest school announcements', 'Keep up with important updates, upcoming events, and student achievements from across our campus.'),
  ('about', 'intro', 'About Our School', 'Since 1998, Lugazi Progressive Secondary School has delivered quality secondary education in Buikwe District. We build confident learners through strong academics, discipline, and support.'),
  ('about', 'stat_years', '25+', 'Years of service'),
  ('about', 'stat_subjects', '18+', 'Subjects offered'),
  ('about', 'stat_staff', '60+', 'Dedicated staff'),
  ('about', 'mission', 'Mission', 'To empower learners through disciplined teaching and meaningful experiences.'),
  ('about', 'vision', 'Vision', 'To be a leading center of academic excellence and personal growth.'),
  ('about', 'values', 'Values', 'Respect, responsibility, integrity, service and a commitment to lifelong learning.'),
  ('academics', 'intro', 'Academic Programmes', 'Our curriculum blends strong academic subjects with practical learning to prepare students for A-Level success and a future career.'),
  ('gallery', 'intro', 'Campus Gallery', 'Explore moments from school life, classrooms, labs, and student activities.'),
  ('contact', 'intro', 'Contact Us', 'Have a question about admissions, academics, or student life? Send us a message and our team will respond promptly.'),
  ('contact', 'name_label', 'Name', ''),
  ('contact', 'email_label', 'Email', ''),
  ('contact', 'message_label', 'Message', ''),
  ('contact', 'submit_label', 'Send Message', ''),
  ('contact', 'success_message', '', 'Thank you. Your message has been sent.');

INSERT IGNORE INTO programs (icon, title, description, sort_order) VALUES
  ('SCI', 'Sciences', 'Biology, Chemistry, Physics and Mathematics for future innovators.', 1),
  ('ART', 'Humanities & Arts', 'History, Geography, Literature and Fine Art for well-rounded learners.', 2),
  ('ICT', 'ICT & Technology', 'Digital literacy, computer studies and modern technology skills.', 3),
  ('SPORT', 'Sports & Clubs', 'Football, netball, drama, debate and many co-curricular activities.', 4);

INSERT IGNORE INTO admissions (label, title, description, sort_order) VALUES
  ('Admissions dates', 'Term intake', 'Applications accepted all year with rolling interviews for O-Level and A-Level students.', 1),
  ('Scholarships', 'Academic support', 'Need-based tuition support is available for deserving learners from Buikwe and surrounding districts.', 2),
  ('Visit campus', 'Open day', 'Join our next campus tour to meet teachers, see classrooms, and learn about boarding facilities.', 3);

INSERT IGNORE INTO staff_members (name, role, initials, sort_order) VALUES
  ('Ms. Miriam Kintu', 'Principal', 'MK', 1),
  ('Mr. David Tendo', 'Head of Science', 'DT', 2),
  ('Ms. Angela Ssenkaaba', 'Head of Humanities', 'AS', 3);

INSERT IGNORE INTO news_items (published_at, title, description, sort_order) VALUES
  ('2026-06-10', 'Science fair winners announced', 'Students showcased innovation and research projects at the annual science fair.', 1),
  ('2026-07-02', 'Sports day celebration', 'Our athletes competed in football, netball, and track events with strong school spirit.', 2),
  ('2026-08-25', 'New library opening', 'A renovated library space now offers more study areas and digital learning resources.', 3);

INSERT IGNORE INTO academic_courses (title, description, details, sort_order) VALUES
  ('O-Level Programme', 'Our O-Level curriculum builds a strong foundation in core subjects while encouraging creativity, critical thinking and character development.', 'English Language & Literature\nMathematics\nScience: Biology, Chemistry, Physics\nHumanities: History & Geography\nCreative Arts & ICT', 1),
  ('A-Level Programme', 'At A-Level, students choose pathways that prepare them for university and careers with in-depth study and practical application.', 'Science combinations: Physics, Chemistry, Mathematics\nArts combinations: History, Economics, Literature\nBusiness combinations: Accounting, Entrepreneurship, ICT\nResearch projects and guided study support', 2),
  ('Science Department', 'Our science department offers well-equipped labs and hands-on experiments to foster scientific inquiry and confidence.', 'Biology with practical investigation\nChemistry lab work and safety training\nPhysics experiments and applied mathematics\nIntegrated STEM learning opportunities', 3),
  ('ICT & Technology', 'We teach digital skills that students need for modern learning, future careers and responsible online citizenship.', 'Computer Studies and programming basics\nDigital literacy and information systems\nMultimedia, design and presentation tools\nProject work using classroom technology', 4),
  ('Sports & Co-curricular', 'Sport, clubs and co-curricular activities develop leadership, teamwork and healthy living beyond the classroom.', 'Football, athletics and netball\nDebating, drama and music\nArt, science club and nature studies\nCommunity service and leadership programs', 5);

INSERT IGNORE INTO gallery_items (image_path, alt_text, caption, sort_order) VALUES
  ('/assets/img/group.jpeg', 'School unity', 'Student unity and teamwork.', 1),
  ('/assets/img/peace.jpg', 'Peace at school', 'A peaceful learning environment.', 2),
  ('/assets/img/library.jpg', 'School computer lab', 'Our School Computer Lab.', 3),
  ('/assets/img/class.jpg', 'Classroom learning', 'Engaging classroom lessons.', 4),
  ('/assets/img/maktaba.jpeg', 'School community', 'Our school library for research.', 5),
  ('/assets/img/green.jpeg', 'Student research', 'A good learning environment.', 6);
