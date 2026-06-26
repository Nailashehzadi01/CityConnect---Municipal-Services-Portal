-- ═══════════════════════════════════════════════════
--  City Community Services Portal — Database Setup
--  Run this file in phpMyAdmin or MySQL CLI:
--  mysql -u root -p < database.sql
-- ═══════════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS city_portal
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE city_portal;

-- ─────────────────────────────────────────────
--  TABLE: complaints
-- ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS complaints (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(120)  NOT NULL,
    contact     VARCHAR(20)   NOT NULL,
    category    VARCHAR(60)   NOT NULL,
    description TEXT          NOT NULL,
    area        VARCHAR(200)  NOT NULL,
    status      ENUM('Pending','In Progress','Resolved') NOT NULL DEFAULT 'Pending',
    date        DATE          NOT NULL DEFAULT (CURRENT_DATE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
--  TABLE: announcements
-- ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS announcements (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    body  TEXT         NOT NULL,
    date  DATE         NOT NULL DEFAULT (CURRENT_DATE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
--  TABLE: services
-- ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS services (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    name    VARCHAR(120) NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone   VARCHAR(30)  NOT NULL,
    hours   VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
--  SAMPLE DATA: announcements
-- ─────────────────────────────────────────────
INSERT INTO announcements (title, body, date) VALUES
('Water Supply Suspension – Sector G-10', 'Due to emergency maintenance on the main pipeline, water supply in Sector G-10 will be suspended on May 5th from 8:00 AM to 6:00 PM. Residents are advised to store water in advance. Inconvenience is regretted.', '2026-05-01'),
('Road Closure – Jinnah Avenue (June 1–5)', 'Jinnah Avenue between Express Chowk and Constitution Avenue will remain closed from June 1 to June 5 for re-carpeting works. Please use alternate routes via 7th Avenue. Work will be carried out from 10:00 PM to 6:00 AM.', '2026-04-28'),
('Public Holiday Notice – Eid ul-Adha', 'All municipal offices will remain closed from June 16 to June 18, 2026 on account of Eid ul-Adha. Emergency services will remain operational. Regular operations resume on June 19.', '2026-04-25'),
('Community Tree Plantation Drive – May 10', 'The City Municipal Office is organizing a community tree plantation drive in Fatima Jinnah Park on May 10, 2026 at 8:00 AM. All residents are welcome to participate. Saplings will be provided free of cost.', '2026-04-20'),
('New Bus Route Launched – Route 45', 'A new bus route (Route 45) connecting I-8 Markaz to Centaurus Mall via Faisal Avenue has been launched. Buses will run every 20 minutes from 6:00 AM to 10:00 PM, Monday to Saturday.', '2026-04-15');

-- ─────────────────────────────────────────────
--  SAMPLE DATA: services
-- ─────────────────────────────────────────────
INSERT INTO services (name, address, phone, hours) VALUES
('Water & Sanitation Department', 'WASA Complex, Sector H-8/4, Islamabad', '051-9257408', 'Mon–Sat: 8:00 AM – 4:00 PM'),
('IESCO – Electricity Office (I-8)', 'IESCO Building, Sector I-8/4, Islamabad', '051-9251321', 'Mon–Fri: 9:00 AM – 5:00 PM'),
('Pakistan Institute of Medical Sciences (PIMS)', 'Shaheed Zulfiqar Ali Bhutto Rd, G-8/3, Islamabad', '051-9261170', '24 / 7 — Emergency Services Available'),
('Polyclinic Hospital', 'G-6/2, Near Abpara Market, Islamabad', '051-9214480', '24 / 7 — Emergency Services Available'),
('Islamabad Bus Terminal (I-8)', 'I-8 Markaz, Islamabad', '051-4865000', 'Daily: 5:00 AM – 11:00 PM'),
('Islamabad Police Headquarters', 'Park Road, Chak Shehzad, Islamabad', '051-9258100 / 15', '24 / 7'),
('Capital Development Authority (CDA)', 'G-7/4, Civic Center, Islamabad', '051-9202601', 'Mon–Fri: 9:00 AM – 5:00 PM'),
('Rescue 1122 – Emergency Services', 'Sector H-8, Islamabad', '1122', '24 / 7 — Fire, Rescue & Ambulance'),
('Islamabad Municipal Waste Management', 'MCI Building, Sector F-7/2, Islamabad', '051-9206020', 'Mon–Sat: 8:00 AM – 4:00 PM'),
('Pakistan Post Office – Main Branch', 'GPO, Melody Market, G-6, Islamabad', '051-9202630', 'Mon–Sat: 9:00 AM – 5:00 PM');

-- ─────────────────────────────────────────────
--  SAMPLE DATA: complaints
-- ─────────────────────────────────────────────
INSERT INTO complaints (name, contact, category, description, area, status, date) VALUES
('Ahmad Raza',    '03001234567', 'Road Damage',     'Large pothole on main road causing accidents.', 'G-10/2 Main Road', 'Pending',     '2026-04-30'),
('Sara Khan',     '03211234567', 'Water Supply',    'No water supply for 3 days. Pipeline burst near market.', 'F-7 Markaz', 'In Progress', '2026-04-28'),
('Bilal Ahmed',   '03451112233', 'Street Lighting', 'Street lights not working for a week on our block.', 'I-8/3 Street 12', 'Resolved',    '2026-04-20'),
('Fatima Malik',  '03331122334', 'Sanitation',      'Overflowing sewage drain near the park entrance.', 'G-9/4 Near Park', 'Pending',     '2026-04-29');
