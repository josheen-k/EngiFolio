<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // All tables that focus on options for other tables
        DB::table('roles')->insert([
            ['role_id' => 1, 'role_name' => 'Admin'],
            ['role_id' => 2, 'role_name' => 'Staff'],
            ['role_id' => 3, 'role_name' => 'Student'],
        ]);

        DB::table('account_statuses')->insert([
            ['account_status_id' => 1, 'account_status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['account_status_id' => 2, 'account_status' => 'disabled', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('goal_statuses')->insert([
            ['goal_status_id' => 1, 'status' => 'Planned', 'created_at' => now(), 'updated_at' => now()],
            ['goal_status_id' => 2, 'status' => 'In progress', 'created_at' => now(), 'updated_at' => now()],
            ['goal_status_id' => 3, 'status' => 'Completed', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('competency_entry_levels')->insert([
            ['entry_level_id' => 1, 'competency_level' => 'Emerging', 'competency_level_weighting' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['entry_level_id' => 2, 'competency_level' => 'Developing', 'competency_level_weighting' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['entry_level_id' => 3, 'competency_level' => 'Proficient', 'competency_level_weighting' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['entry_level_id' => 4, 'competency_level' => 'Confident', 'competency_level_weighting' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('competency_entry_statuses')->insert([
            ['entry_status_id' => 1, 'entry_status' => 'Draft', 'created_at' => now(), 'updated_at' => now()],
            ['entry_status_id' => 2, 'entry_status' => 'Submitted', 'created_at' => now(), 'updated_at' => now()],
            ['entry_status_id' => 3, 'entry_status' => 'Reviewed', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('users')->insert([
            ['username' => 'admin1', 'email' => 'admin1@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Joe', 'last_name' => 'Bloggs', 'role_id' => 1, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'a123456', 'email' => 'alex.smith@adelaide.edu.au', 'first_name' => 'Alex', 'last_name' => 'Smith', 'password_hash' => Hash::make('password'), 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'a789012', 'email' => 'kate.brown@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Kate', 'last_name' => 'Brown', 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'tutor1', 'email' => 'jane@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Jane', 'last_name' => 'Doe',  'role_id' => 2, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'a789011', 'email' => 'priya.sharma@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Priya', 'last_name' => 'Sharma', 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'a123847', 'email' => 'samuel.chen@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Samuel', 'last_name' => 'Chen', 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'a882930', 'email' => 'isabella.martinez@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Isabella', 'last_name' => 'Martinez', 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'a455612', 'email' => 'fatima.al-sayed@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Fatima', 'last_name' => 'Al-Sayed', 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'a330948', 'email' => 'arjun.patel@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Arjun', 'last_name' => 'Patel', 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'a182934', 'email' => 'minji.kim@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Min-ji', 'last_name' => 'Kim', 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'a440291', 'email' => 'omar.hassan@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Omar', 'last_name' => 'Hassan', 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'a928374', 'email' => 'mei.ling@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Mei', 'last_name' => 'Ling', 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'a556102', 'email' => 'chloe.davis@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Chloe', 'last_name' => 'Davis', 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'a338495', 'email' => 'hiroshi.tanaka@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Hiroshi', 'last_name' => 'Tanaka', 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'a771029', 'email' => 'leila.mansour@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Leila', 'last_name' => 'Mansour', 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'a209384', 'email' => 'noah.clark@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Noah', 'last_name' => 'Clark', 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'a665412', 'email' => 'giovanni.rossi@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Giovanni', 'last_name' => 'Rossi', 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'a882736', 'email' => 'amara.diallo@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Amara', 'last_name' => 'Diallo', 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'a119283', 'email' => 'sophia.lee@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Sophia', 'last_name' => 'Lee', 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'tutor2', 'email' => 'kwame.asante@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Kwame', 'last_name' => 'Asante',  'role_id' => 2, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('student_profiles')->insert([
            ['user_id' => 2, 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Mechanical', 'personal_intro' => 'Focused on sustainable energy systems.', 'profile_image_url' => '/src/assets/alex.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 3, 'degree_title' => 'Bachelor of Civil Engineering', 'specialisation' => 'Structural', 'personal_intro' => 'Interested in earthquake-resistant urban design.', 'profile_image_url' => '/src/assets/kate.jpeg', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 5, 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Mechanical', 'personal_intro' => 'Focused on sustainable energy systems and thermal dynamics.', 'profile_image_url' => '/src/assets/kate.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 6, 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Software', 'personal_intro' => 'Interested in distributed systems and scalable cloud architecture.', 'profile_image_url' => '/src/assets/samuel.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 7, 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Civil', 'personal_intro' => 'Specialising in structural integrity and earthquake-resistant design.', 'profile_image_url' => '/src/assets/isabella.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 8, 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Electrical & Electronic', 'personal_intro' => 'Researching renewable grid integration and power electronics.', 'profile_image_url' => '/src/assets/lachlan.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 9, 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Chemical', 'personal_intro' => 'Focused on process optimisation and carbon capture technologies.', 'profile_image_url' => '/src/assets/grace.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 10, 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Mechatronic', 'personal_intro' => 'Developing autonomous robotics for industrial automation.', 'profile_image_url' => '/src/assets/james.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 11, 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Aerospace', 'personal_intro' => 'Passionate about orbital mechanics and propulsion systems.', 'profile_image_url' => '/src/assets/sarah.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 12, 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Biomedical', 'personal_intro' => 'Designing next-generation prosthetic limbs and wearable sensors.', 'profile_image_url' => '/src/assets/liam.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 13, 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Mining', 'personal_intro' => 'Exploring sustainable extraction methods and mine safety automation.', 'profile_image_url' => '/src/assets/chloe.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 14, 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Petroleum', 'personal_intro' => 'Focused on reservoir simulation and enhanced recovery techniques.', 'profile_image_url' => '/src/assets/oliver.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 15, 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Environmental', 'personal_intro' => 'Specialising in wastewater treatment and urban water management.', 'profile_image_url' => '/src/assets/emma.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 16, 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Computer Systems', 'personal_intro' => 'Interested in hardware-software co-design and IoT security.', 'profile_image_url' => '/src/assets/noah.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 17, 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Materials', 'personal_intro' => 'Researching high-performance composites for extreme environments.', 'profile_image_url' => '/src/assets/mia.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 18, 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Systems', 'personal_intro' => 'Managing complex lifecycle engineering for large-scale projects.', 'profile_image_url' => '/src/assets/lucas.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 19, 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Renewable Energy', 'personal_intro' => 'Advancing solar photovoltaic efficiency and storage solutions.', 'profile_image_url' => '/src/assets/sophia.jpg', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('student_links')->insert([
            ['profile_id' => 1, 'link_label' => 'LinkedIn', 'link_url' => 'https://linkedin.com/in/alex-eng', 'created_at' => now(), 'updated_at' => now()],
            ['profile_id' => 1, 'link_label' => 'Resume', 'link_url' => 'https://alex-eng.com/resume', 'created_at' => now(), 'updated_at' => now()],
            ['profile_id' => 2, 'link_label' => 'My Design Portfolio', 'link_url' => 'https://kate-structures.com', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('elevator_pitches')->insert([
            ['profile_id' => 1, 'pitch_text' => 'Hi, I am Alex, a Mechanical student focusing on fluid dynamics.', 'created_at' => now(), 'updated_at' => now()],
            ['profile_id' => 2, 'pitch_text' => 'I am Kate, an aspiring Structural Engineer dedicated to steel detailing.', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('career_development_plans')->insert([
            ['plan_id' => 1, 'profile_id' => 1, 'plan_year' => 2026, 'professional_interests' => 'Solar power', 'created_at' => now(), 'updated_at' => now()],
            ['plan_id' => 2, 'profile_id' => 2, 'plan_year' => 2026, 'professional_interests' => 'Bridge design', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('smart_goals')->insert([
            ['goal_id' => 1, 'profile_id' => 1, 'goal_description' => 'Complete Statics Course', 'goal_status_id' => 2, 'start_date' => '2026-02-01', 'end_date' => '2026-06-30', 'created_at' => now(), 'updated_at' => now()],
            ['goal_id' => 2, 'profile_id' => 2, 'goal_description' => 'Secure a summer internship', 'goal_status_id' => 1, 'start_date' => '2026-05-01', 'end_date' => '2026-08-01', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('goal_feedback')->insert([
            ['goal_id' => 2, 'staff_id' => 4, 'feedback_content' => 'Add action steps', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('industry_contacts')->insert([
            ['contact_id' => 3, 'profile_id' => 1, 'contact_name' => 'Elena Rodriguez', 'company' => 'Arup Adelaide', 'progress_notes' => 'Met at university lecture. Discussed graduate opportunities at Arup. She suggested connecting on LinkedIn and reaching out mid-year when applications open.', 'date_met' => '2026-03-12', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 4, 'profile_id' => 1, 'contact_name' => 'Kenji Sato', 'company' => 'Mitsubishi Heavy Industries', 'progress_notes' => 'Introduced at engineering careers expo. Expressed interest in structural engineering roles. Followed up via email, awaiting response.', 'date_met' => '2026-04-05', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 5, 'profile_id' => 1, 'contact_name' => 'Sarah Jenkins', 'company' => 'Santos Ltd', 'progress_notes' => 'Met at a networking dinner hosted by the engineering faculty. Discussed pipeline infrastructure projects. She recommended attending the Santos graduate information session in June.', 'date_met' => '2026-02-18', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 6, 'profile_id' => 1, 'contact_name' => 'Marcus Aurelius', 'company' => 'Roman Concrete Solutions', 'progress_notes' => 'Connected at a materials engineering seminar. Discussed innovative concrete applications in civil infrastructure. Exchanged business cards, planning to follow up about a site visit.', 'date_met' => '2026-01-10', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 7, 'profile_id' => 1, 'contact_name' => 'Amara Okafor', 'company' => 'Google Cloud', 'progress_notes' => 'Met at a tech and engineering crossover event. Discussed how cloud infrastructure intersects with engineering workflows. She offered to connect me with the Google engineering graduate team.', 'date_met' => '2026-05-20', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 8, 'profile_id' => 1, 'contact_name' => 'Lachlan Murdoch', 'company' => 'BAE Systems Australia', 'progress_notes' => 'Introduced through a mutual contact at a defence industry briefing. Discussed graduate roles in systems engineering. Following up next month with a formal expression of interest.', 'date_met' => '2026-03-25', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 9, 'profile_id' => 1, 'contact_name' => 'Siobhan Roy', 'company' => 'Waystar Civil Engineering', 'progress_notes' => 'Met at a university alumni panel. She spoke about project management in large civil works. Had a follow-up coffee meeting, discussed internship availability for semester two.', 'date_met' => '2026-04-15', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 10, 'profile_id' => 1, 'contact_name' => 'Chen Wei', 'company' => 'Tesla Motors', 'progress_notes' => 'Connected at an EV and sustainable transport forum. Discussed battery systems and manufacturing engineering. She shared details of Tesla\'s graduate engineering program opening later this year.', 'date_met' => '2026-05-02', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 11, 'profile_id' => 1, 'contact_name' => 'Alistair Cook', 'company' => 'Cook Renewable Energy', 'progress_notes' => 'Met at a renewable energy industry meetup. Discussed solar and wind project development in South Australia. He offered a tour of their current wind farm project site.', 'date_met' => '2026-02-28', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 12, 'profile_id' => 1, 'contact_name' => 'Dana Scully', 'company' => 'FBI Forensic Engineering', 'progress_notes' => 'Met at a forensic engineering guest lecture. Discussed failure analysis and investigation methodology. Expressed interest in the intersection of engineering and legal applications.', 'date_met' => '2026-01-30', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('industry_contact_methods')->insert([
            ['contact_id' => 3, 'method_type' => 'Email', 'method_value' => 'elena.rodriguez@arup.com.au', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 4, 'method_type' => 'LinkedIn', 'method_value' => 'linkedin.com/in/ksato-mhi', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 5, 'method_type' => 'Phone', 'method_value' => '+61 8 8116 5000', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 6, 'method_type' => 'Email', 'method_value' => 'm.aurelius@romanconcrete.it', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 7, 'method_type' => 'LinkedIn', 'method_value' => 'linkedin.com/in/aokafor-cloud', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 8, 'method_type' => 'Email', 'method_value' => 'lachlan.murdoch@baesystems.com', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 9, 'method_type' => 'Phone', 'method_value' => '+61 400 123 456', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 10, 'method_type' => 'Email', 'method_value' => 'w.chen@tesla.com', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 11, 'method_type' => 'LinkedIn', 'method_value' => 'linkedin.com/in/alistair-cook-energy', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 12, 'method_type' => 'Email', 'method_value' => 'd.scully@fbi.gov', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('competency_groups')->insert([
            ['group_id' => 1, 'display_id' => 'CAT1', 'group_name' => 'Knowledge Base', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 2, 'display_id' => 'CAT2', 'group_name' => 'Engineering Application Ability', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 3, 'display_id' => 'CAT3', 'group_name' => 'Professional And Personal Attributes', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('competency_indicators')->insert([
            ['group_id' => 1, 'display_id' => '1.1', 'indicator_name' => 'Engineering Fundamentals', 'description' => 'Comprehensive, theory based understanding of the underpinning natural and physical sciences and the engineering fundamentals applicable to the engineering discipline.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 1, 'display_id' => '1.2', 'indicator_name' => 'Mathematical, Data & Computer Sciences', 'description' => 'Conceptual understanding of the mathematics, numerical analysis, statistics, and computer and information sciences which underpin the engineering discipline.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 1, 'display_id' => '1.3', 'indicator_name' => 'Specialist Knowledge', 'description' => 'In-depth understanding of specialist bodies of knowledge within the engineering discipline.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 1, 'display_id' => '1.4', 'indicator_name' => 'Development & Research Directions', 'description' => 'Discernment of knowledge development and research directions within the engineering discipline.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 1, 'display_id' => '1.5', 'indicator_name' => 'Design Practices & Context', 'description' => 'Knowledge of engineering design practice and contextual factors impacting the engineering discipline.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 1, 'display_id' => '1.6', 'indicator_name' => 'Professional & Sustainable Practices', 'description' => 'Understanding of the scope, principles, norms, accountabilities and bounds of sustainable engineering practice in the specific discipline.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],

            ['group_id' => 2, 'display_id' => '2.1', 'indicator_name' => 'Complex Problem Solving', 'description' => 'Application of established engineering methods to complex engineering problem solving.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 2, 'display_id' => '2.2', 'indicator_name' => 'Techniques, Tools & Resources', 'description' => 'Fluent application of engineering techniques, tools and resources.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 2, 'display_id' => '2.3', 'indicator_name' => 'Synthesis & Design Processes', 'description' => 'Application of systematic engineering synthesis and design processes.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 2, 'display_id' => '2.4', 'indicator_name' => 'Conduct & Management', 'description' => 'Application of systematic approaches to the conduct and management of engineering projects.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],

            ['group_id' => 3, 'display_id' => '3.1', 'indicator_name' => 'Ethics & Accountability', 'description' => 'Ethical conduct and professional accountability.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 3, 'display_id' => '3.2', 'indicator_name' => 'Professional Communication', 'description' => 'Effective oral and written communication in professional and lay domains.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 3, 'display_id' => '3.3', 'indicator_name' => 'Innovation & Proactivity', 'description' => 'Creative, innovative and pro-active demeanour.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 3, 'display_id' => '3.4', 'indicator_name' => 'Information Management', 'description' => 'Professional use and management of information.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 3, 'display_id' => '3.5', 'indicator_name' => 'Self-Management & Conduct', 'description' => 'Orderly management of self, and professional conduct.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 3, 'display_id' => '3.6', 'indicator_name' => 'Teamwork & Leadership', 'description' => 'Effective team membership and team leadership.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
        
            // A discontinued indicator that has a discontinued date
            ['group_id' => 3, 'display_id' => '3.0', 'indicator_name' => 'Test', 'description' => 'Test', 'discontinued_date' => now(), 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('attainment_indicators')->insert([
            // All 1 competency attainment indicators
            ['indicator_id' => 1, 'attainment_indicator' => 'Engages with the engineering discipline at a phenomenological level, applying sciences and engineering fundamentals to systematic investigation, interpretation, analysis and innovative solution of complex problems and broader aspects of engineering practice.'],
            ['indicator_id' => 2, 'attainment_indicator' => 'Develops and fluently applies relevant investigation analysis, interpretation, assessment, characterisation, prediction, evaluation, modelling, decision making, measurement, evaluation, knowledge management and communication tools and techniques pertinent to the engineering discipline.'],
            ['indicator_id' => 3, 'attainment_indicator' => 'Proficiently applies advanced technical knowledge and skills in at least one specialist practice domain of the engineering discipline.'],
            ['indicator_id' => 4, 'attainment_indicator' => 'Identifies and critically appraises current developments, advanced technologies, emerging issues and interdisciplinary linkages in at least one specialist practice domain of the engineering discipline.'],
            ['indicator_id' => 4, 'attainment_indicator' => 'Interprets and applies selected research literature to inform engineering application in at least one specialist domain of the engineering discipline.'],
            ['indicator_id' => 5, 'attainment_indicator' => 'Identifies and applies systematic principles of engineering design relevant to the engineering discipline.'],
            ['indicator_id' => 5, 'attainment_indicator' => 'Identifies and understands the interactions between engineering systems and people in the social, cultural, environmental, commercial, legal and political contexts in which they operate, including both the positive role of engineering in sustainable development and the potentially adverse impacts of engineering activity in the engineering discipline.'],
            ['indicator_id' => 5, 'attainment_indicator' => 'Appreciates the issues associated with international engineering practice and global operating contexts.'],
            ['indicator_id' => 5, 'attainment_indicator' => 'Is aware of the founding principles of human factors relevant to the engineering discipline.'],
            ['indicator_id' => 5, 'attainment_indicator' => 'Is aware of the fundamentals of business and enterprise management.'],
            ['indicator_id' => 5, 'attainment_indicator' => 'Identifies the structure, roles and capabilities of the engineering workforce.'],
            ['indicator_id' => 6, 'attainment_indicator' => 'Appreciates the basis and relevance of standards and codes of practice, as well as legislative and statutory requirements applicable to the engineering discipline.'],
            ['indicator_id' => 6, 'attainment_indicator' => 'Appreciates the principles of safety engineering, risk management and the health and safety responsibilities of the professional engineer, including legislative requirements applicable to the engineering discipline.'],
            ['indicator_id' => 6, 'attainment_indicator' => 'Appreciates the social, environmental and economic principles of sustainable engineering practice.'],
            ['indicator_id' => 6, 'attainment_indicator' => 'Understands the fundamental principles of engineering project management as a basis for planning, organising and managing resources.'],
            ['indicator_id' => 6, 'attainment_indicator' => 'Appreciates the formal structures and methodologies of systems engineering as a holistic basis for managing complexity and sustainability in engineering practice.'],

            // 2.1
            ['indicator_id' => 7, 'attainment_indicator' => 'Ensures that all aspects of an engineering activity are soundly based on fundamental principles - by diagnosing, and taking appropriate action with data, calculations, results, proposals, processes, practices, and documented information that may be ill-founded, illogical, erroneous, unreliable or unrealistic.'],
            ['indicator_id' => 7, 'attainment_indicator' => 'Competently addresses complex engineering problems which involve uncertainty, ambiguity, imprecise information and wide-ranging and sometimes conflicting technical and non-technical factors.'],
            ['indicator_id' => 7, 'attainment_indicator' => 'Investigates complex problems using research-based knowledge and research methods.'],
            ['indicator_id' => 7, 'attainment_indicator' => 'Partitions problems, processes or systems into manageable elements for the purposes of analysis, modelling or design and then re-combines to form a whole, with the integrity and performance of the overall system as the paramount consideration.'],
            ['indicator_id' => 7, 'attainment_indicator' => 'Conceptualises alternative engineering approaches and evaluates potential outcomes against appropriate criteria to justify an optimal solution choice.'],
            ['indicator_id' => 7, 'attainment_indicator' => 'Critically reviews and applies relevant standards and codes of practice underpinning the engineering discipline and nominated specialisations.'],
            ['indicator_id' => 7, 'attainment_indicator' => 'Identifies, quantifies, mitigates and manages technical, health, environmental, safety and other contextual risks associated with engineering application in the designated engineering discipline.'],
            ['indicator_id' => 7, 'attainment_indicator' => 'Interprets and ensures compliance with relevant legislative and statutory requirements applicable to the engineering discipline.'],

            // 2.2
            ['indicator_id' => 8, 'attainment_indicator' => 'Proficiently identifies, selects and applies the materials, components, devices, systems, processes, resources, plant and equipment relevant to the engineering discipline.'],
            ['indicator_id' => 8, 'attainment_indicator' => 'Constructs or selects and applies from a qualitative description of a phenomenon, process, system, component or device a mathematical, physical or computational model based on fundamental scientific principles and justifiable simplifying assumptions.'],
            ['indicator_id' => 8, 'attainment_indicator' => 'Determines properties, performance, safe working limits, failure modes, and other inherent parameters of materials, components and systems relevant to the engineering discipline.'],
            ['indicator_id' => 8, 'attainment_indicator' => 'Applies a wide range of engineering tools for analysis, simulation, visualisation, synthesis and design, including assessing the accuracy and limitations of such tools, and validation of their results.'],
            ['indicator_id' => 8, 'attainment_indicator' => 'Applies formal systems engineering methods to address the planning and execution of complex, problem solving and engineering projects.'],
            ['indicator_id' => 8, 'attainment_indicator' => 'Designs and conducts experiments, analyses and interprets result data and formulates reliable conclusions.'],
            ['indicator_id' => 8, 'attainment_indicator' => 'Analyses sources of error in applied models and experiments; eliminates, minimises or compensates for such errors; quantifies significance of errors to any conclusions drawn.'],
            ['indicator_id' => 8, 'attainment_indicator' => 'Safely applies laboratory, test and experimental procedures appropriate to the engineering discipline.'],
            ['indicator_id' => 8, 'attainment_indicator' => 'Understands the need for systematic management of the acquisition, commissioning, operation, upgrade, monitoring and maintenance of engineering plant, facilities, equipment and systems.'],
            ['indicator_id' => 8, 'attainment_indicator' => 'Understands the role of quality management systems, tools and processes within a culture of continuous improvement.'],

            // 2.3
            ['indicator_id' => 9, 'attainment_indicator' => 'Proficiently applies technical knowledge and open ended problem solving skills as well as appropriate tools and resources to design components, elements, systems, plant, facilities and/or processes to satisfy user requirements.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Executes and leads a whole systems design cycle approach.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Determines client requirements and identifies the impact of relevant contextual factors, including business planning and costing targets.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Systematically addresses sustainability criteria.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Works within projected development, production and implementation constraints.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Elicits, scopes and documents the required outcomes of the design task and defines acceptance criteria.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Identifies, assesses and manages technical, health and safety risks integral to the design process.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Writes engineering specifications that fully satisfy the formal requirements.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Ensures compliance with essential engineering standards and codes of practice.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Partitions the design task into appropriate modular functional elements that can be separately addressed and subsequently integrated through defined interfaces.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Identifies and analyses possible design approaches and justifies an optimal approach.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Develops and completes the design using appropriate engineering principles, tools and processes.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Integrates functional elements to form a coherent design solution.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Quantifies the materials, components, systems, equipment, facilities, engineering resources and operating arrangements needed for implementation of the solution.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Checks the design solution for each element and the integrated system against the engineering specifications.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Devises and documents tests that will verify performance of the elements and the integrated realisation.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Prototypes and implements the design solution and verifies performance against specification.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Documents, commissions and reports the design outcome.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Executes and leads a whole systems design cycle approach including determining client requirements, systematically addressing sustainability criteria, working within projected constraints, eliciting and documenting required outcomes, identifying and managing risks, writing engineering specifications, ensuring compliance with standards, partitioning design tasks, identifying and analysing design approaches, developing and completing the design, integrating functional elements, quantifying materials and resources, checking design solutions, devising verification tests, prototyping and verifying performance, and documenting and reporting the design outcome.'],
            ['indicator_id' => 9, 'attainment_indicator' => 'Is aware of the accountabilities of the professional engineer in relation to the design authority role.'],

            // 2.4
            ['indicator_id' => 10, 'attainment_indicator' => 'Contributes to and/or manages complex engineering project activity, as a member and/or as the leader of an engineering team.'],
            ['indicator_id' => 10, 'attainment_indicator' => 'Seeks out the requirements and associated resources and realistically assesses the scope, dimensions, scale of effort and indicative costs of a complex engineering project.'],
            ['indicator_id' => 10, 'attainment_indicator' => 'Accommodates relevant contextual issues into all phases of engineering project work, including the fundamentals of business planning and financial management.'],
            ['indicator_id' => 10, 'attainment_indicator' => 'Proficiently applies basic systems engineering and/or project management tools and processes to the planning and execution of project work, targeting the delivery of a significant outcome to a professional standard.'],
            ['indicator_id' => 10, 'attainment_indicator' => 'Is aware of the need to plan and quantify performance over the full life-cycle of a project, managing engineering performance within the overall implementation context.'],
            ['indicator_id' => 10, 'attainment_indicator' => 'Demonstrates commitment to sustainable engineering practices and the achievement of sustainable outcomes in all facets of engineering project work.'],

            // 3.1
            ['indicator_id' => 11, 'attainment_indicator' => 'Demonstrates commitment to uphold the Engineers Australia Code of Ethics, and established norms of professional conduct pertinent to the engineering discipline.'],
            ['indicator_id' => 11, 'attainment_indicator' => 'Understands the need for due-diligence in certification, compliance and risk management processes.'],
            ['indicator_id' => 11, 'attainment_indicator' => 'Understands the accountabilities of the professional engineer and the broader engineering team for the safety of other people and for protection of the environment.'],
            ['indicator_id' => 11, 'attainment_indicator' => 'Is aware of the fundamental principles of intellectual property rights and protection.'],

            // 3.2
            ['indicator_id' => 12, 'attainment_indicator' => 'Is proficient in listening, speaking, reading and writing English, including comprehending critically and fairly the viewpoints of others, expressing information effectively and succinctly to technical and non-technical audiences using appropriate media, representing an engineering position to the broader community, and appreciating the impact of body language and cross-cultural differences in communication.'],
            ['indicator_id' => 12, 'attainment_indicator' => 'Prepares high quality engineering documents such as progress and project reports, reports of investigations and feasibility studies, proposals, specifications, design records, drawings, technical descriptions and presentations pertinent to the engineering discipline.'],

            // 3.3
            ['indicator_id' => 13, 'attainment_indicator' => 'Applies creative approaches to identify and develop alternative concepts, solutions and procedures, appropriately challenges engineering practices from technical and non-technical viewpoints, and identifies new technological opportunities.'],
            ['indicator_id' => 13, 'attainment_indicator' => 'Seeks out new developments in the engineering discipline and specialisations and applies fundamental knowledge and systematic processes to evaluate and report potential.'],
            ['indicator_id' => 13, 'attainment_indicator' => 'Is aware of broader fields of science, engineering, technology and commerce from which new ideas and interfaces may be drawn and readily engages with professionals from these fields to exchange ideas.'],

            // 3.4
            ['indicator_id' => 14, 'attainment_indicator' => 'Is proficient in locating and utilising information - including accessing, systematically searching, analysing, evaluating and referencing relevant published works and data, and is proficient in the use of indexes, bibliographic databases and other search facilities.'],
            ['indicator_id' => 14, 'attainment_indicator' => 'Critically assesses the accuracy, reliability and authenticity of information.'],
            ['indicator_id' => 14, 'attainment_indicator' => 'Is aware of common document identification, tracking and control procedures.'],

            // 3.5
            ['indicator_id' => 15, 'attainment_indicator' => 'Demonstrates commitment to critical self-review and performance evaluation against appropriate criteria as a primary means of tracking personal development needs and achievements.'],
            ['indicator_id' => 15, 'attainment_indicator' => 'Understands the importance of being a member of a professional and intellectual community, learning from its knowledge and standards, and contributing to their maintenance and advancement.'],
            ['indicator_id' => 15, 'attainment_indicator' => 'Demonstrates commitment to life-long learning and professional development.'],
            ['indicator_id' => 15, 'attainment_indicator' => 'Manages time and processes effectively, prioritises competing demands to achieve personal, career and organisational goals and objectives.'],
            ['indicator_id' => 15, 'attainment_indicator' => 'Thinks critically and applies an appropriate balance of logic and intellectual criteria to analysis, judgement and decision making.'],
            ['indicator_id' => 15, 'attainment_indicator' => 'Presents a professional image in all circumstances, including relations with clients, stakeholders, as well as with professional and technical colleagues across wide ranging disciplines.'],

            // 3.6
            ['indicator_id' => 16, 'attainment_indicator' => 'Understands the fundamentals of team dynamics and leadership.'],
            ['indicator_id' => 16, 'attainment_indicator' => 'Functions as an effective member or leader of diverse engineering teams, including those with multi-level, multi-disciplinary and multi-cultural dimensions.'],
            ['indicator_id' => 16, 'attainment_indicator' => 'Earns the trust and confidence of colleagues through competent and timely completion of tasks.'],
            ['indicator_id' => 16, 'attainment_indicator' => 'Recognises the value of alternative and diverse viewpoints, scholarly advice and the importance of professional networking.'],
            ['indicator_id' => 16, 'attainment_indicator' => 'Confidently pursues and discerns expert assistance and professional advice.'],
            ['indicator_id' => 16, 'attainment_indicator' => 'Takes initiative and fulfils the leadership role whilst respecting the agreed roles of others.'],
        ]);

        DB::table('competency_entries')->insert([
            [
                'entry_id' => 1, 
                'profile_id' => 1, 
                'indicator_id' => 1, 
                'experience_title' => 'Bridge Project', 
                'associated_year' => 1, 
                'experience_tasks' => 'CAD work', 
                'key_learnings' => 'Mastered 3D modeling constraints and learned the importance of load distribution in structural design.', 
                'future_applications' => 'Applying parametric modeling techniques to more complex architectural structures in Year 2.', 
                'entry_level_id' => 3, 'entry_status_id' => 2, 'start_date' => '2026-03-01', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'entry_id' => 2, 
                'profile_id' => 1, 
                'indicator_id' => 1, 
                'experience_title' => 'Electrical Project', 
                'associated_year' => 2, 
                'experience_tasks' => 'Wiring', 
                'key_learnings' => 'Understanding circuit continuity and the safety protocols required for high-voltage breadboarding.', 
                'future_applications' => 'Scaling these wiring principles to PCB design and automated hardware testing.', 
                'entry_level_id' => 1, 'entry_status_id' => 2, 'start_date' => '2026-03-01', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'entry_id' => 3, 'profile_id' => 1, 'indicator_id' => 2, 
                'experience_title' => 'Python Automation Script', 'associated_year' => 1, 
                'experience_tasks' => 'Writing scripts to parse CSV data', 
                'key_learnings' => 'Learned how to use pandas for data manipulation and the value of DRY (Don\'t Repeat Yourself) code.', 
                'future_applications' => 'Automating large-scale data analysis for future research projects.', 
                'entry_level_id' => 2, 'entry_status_id' => 2, 'start_date' => '2026-04-10', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'entry_id' => 4, 'profile_id' => 1, 'indicator_id' => 4, 
                'experience_title' => 'Robotics Club Lead', 'associated_year' => 2, 
                'experience_tasks' => 'Coordinating team meetings and parts procurement', 
                'key_learnings' => 'Developed leadership skills and learned to manage conflicting technical opinions within a team.', 
                'future_applications' => 'Managing multi-disciplinary engineering teams in a corporate setting.', 
                'entry_level_id' => 3, 'entry_status_id' => 2, 'start_date' => '2026-02-15', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'entry_id' => 5, 'profile_id' => 1, 'indicator_id' => 3, 
                'experience_title' => 'Sustainability Audit', 'associated_year' => 2, 
                'experience_tasks' => 'Calculating carbon footprint for campus building', 
                'key_learnings' => 'Understood life-cycle assessment (LCA) methodologies and material waste management.', 
                'future_applications' => 'Integrating sustainable material selection into civil engineering designs.', 
                'entry_level_id' => 2, 'entry_status_id' => 2, 'start_date' => '2026-05-20', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'entry_id' => 6, 'profile_id' => 1, 'indicator_id' => 1, 
                'experience_title' => 'Fluid Mechanics Lab', 'associated_year' => 2, 
                'experience_tasks' => 'Measuring pipe friction and flow rates', 
                'key_learnings' => 'Observed the practical differences between laminar and turbulent flow in real-world piping.', 
                'future_applications' => 'Designing efficient hydraulic systems for irrigation or urban water supply.', 
                'entry_level_id' => 1, 'entry_status_id' => 2, 'start_date' => '2026-08-12', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'entry_id' => 7, 'profile_id' => 1, 'indicator_id' => 5, 
                'experience_title' => 'Industry Networking Night', 'associated_year' => 1, 
                'experience_tasks' => 'Engaging with professional engineers from Arup', 
                'key_learnings' => 'Gained insight into the current demand for BIM specialists in the Australian market.', 
                'future_applications' => 'Tailoring my elective choices toward digital construction management.', 
                'entry_level_id' => 1, 'entry_status_id' => 2, 'start_date' => '2026-09-05', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'entry_id' => 8, 'profile_id' => 1, 'indicator_id' => 2, 
                'experience_title' => 'Control Systems Workshop', 'associated_year' => 3, 
                'experience_tasks' => 'Tuning PID controllers for a motor', 
                'key_learnings' => 'Understood the impact of proportional, integral, and derivative gains on system stability.', 
                'future_applications' => 'Optimising feedback loops in autonomous vehicle navigation.', 
                'entry_level_id' => 3, 'entry_status_id' => 2, 'start_date' => '2026-03-25', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'entry_id' => 9, 'profile_id' => 1, 'indicator_id' => 6, 
                'experience_title' => 'Ethics Case Study', 'associated_year' => 1, 
                'experience_tasks' => 'Analysing the Challenger disaster', 
                'key_learnings' => 'Identified the dangers of normalisation of deviance and the role of engineering whistleblowers.', 
                'future_applications' => 'Upholding rigorous safety standards despite project timeline pressures.', 
                'entry_level_id' => 2, 'entry_status_id' => 2, 'start_date' => '2026-04-02', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'entry_id' => 10, 'profile_id' => 1, 'indicator_id' => 1, 
                'experience_title' => 'Material Strength Testing', 'associated_year' => 2, 
                'experience_tasks' => 'Tensile testing of aluminum alloys', 
                'key_learnings' => 'Linked stress-strain curve theory to physical fracture points in metals.', 
                'future_applications' => 'Performing failure analysis in aerospace or automotive components.', 
                'entry_level_id' => 2, 'entry_status_id' => 2, 'start_date' => '2026-10-18', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'entry_id' => 11, 'profile_id' => 1, 'indicator_id' => 4, 
                'experience_title' => 'Hackathon Participant', 'associated_year' => 2, 
                'experience_tasks' => 'Rapid prototyping of a smart-city app', 
                'key_learnings' => 'Learned to prioritise Minimum Viable Product features under strict time constraints.', 
                'future_applications' => 'Iterating quickly in fast-paced software development cycles.', 
                'entry_level_id' => 3, 'entry_status_id' => 2, 'start_date' => '2026-11-01', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'entry_id' => 12, 'profile_id' => 1, 'indicator_id' => 3, 
                'experience_title' => 'Site Visit: Port Adelaide', 'associated_year' => 1, 
                'experience_tasks' => 'Observing maritime civil works', 
                'key_learnings' => 'Observed the effects of saltwater corrosion on reinforced concrete structures.', 
                'future_applications' => 'Designing durable infrastructure for coastal environments.', 
                'entry_level_id' => 4, 'entry_status_id' => 2, 'start_date' => '2026-05-15', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'entry_id' => 13, 'profile_id' => 1, 'indicator_id' => 7, 
                'experience_title' => 'Technical Report: Solar Array', 'associated_year' => 2, 
                'experience_tasks' => 'Compiling performance data into a formal 20-page report', 
                'key_learnings' => 'Mastered the use of LaTeX for professional formatting and learned to present complex data through clear, annotated charts.', 
                'future_applications' => 'Producing high-quality documentation for stakeholders and regulatory bodies in professional practice.', 
                'entry_level_id' => 3, 'entry_status_id' => 2, 'start_date' => '2026-06-12', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'entry_id' => 14, 'profile_id' => 1, 'indicator_id' => 8, 
                'experience_title' => 'Calculus in Structural Analysis', 'associated_year' => 1, 
                'experience_tasks' => 'Applying double integrals to calculate moments of inertia', 
                'key_learnings' => 'Bridged the gap between abstract multivariable calculus and its physical necessity in predicting beam deflection.', 
                'future_applications' => 'Executing rigorous mathematical verification for structural integrity in civil projects.', 
                'entry_level_id' => 2, 'entry_status_id' => 2, 'start_date' => '2026-04-20', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'entry_id' => 15, 'profile_id' => 1, 'indicator_id' => 9, 
                'experience_title' => 'Project Risk Assessment', 'associated_year' => 3, 
                'experience_tasks' => 'Creating a Risk Register for a construction site simulation', 
                'key_learnings' => 'Developed a safety-first mindset by identifying high-probability hazards and designing mitigation strategies.', 
                'future_applications' => 'Ensuring OHS compliance and minimising liability during real-world site management.', 
                'entry_level_id' => 3, 'entry_status_id' => 2, 'start_date' => '2026-08-05', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'entry_id' => 16, 'profile_id' => 1, 'indicator_id' => 10, 
                'experience_title' => 'Final Year Symposium Pitch', 'associated_year' => 4, 
                'experience_tasks' => 'Presenting a 5-minute technical pitch to industry judges', 
                'key_learnings' => 'Learned to translate highly technical jargon into a value-driven narrative for non-specialist audiences.', 
                'future_applications' => 'Securing project funding and pitching innovative engineering solutions to clients.', 
                'entry_level_id' => 3, 'entry_status_id' => 2, 'start_date' => '2026-10-15', 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'entry_id' => 17, 'profile_id' => 1, 'indicator_id' => 11, 
                'experience_title' => 'Software Version Control Workshop', 'associated_year' => 2, 
                'experience_tasks' => 'Managing collaborative code using Git and GitHub', 
                'key_learnings' => 'Understood branching strategies and the critical role of peer review in maintaining codebase integrity.', 
                'future_applications' => 'Collaborating effectively within large-scale, distributed software engineering teams.', 
                'entry_level_id' => 2, 'entry_status_id' => 2, 'start_date' => '2026-03-22', 'created_at' => now(), 'updated_at' => now()
            ],
        ]);

        DB::table('cdl_modules')->insert([
            ['cdl_id' => 1, 'title' => 'Introduction To Career Development Planning & Management', 'description' => 'Introduction To Career Development Planning & Management', 'module_url' => 'https://adelaide.edu.au/', 'created_at' => now(), 'updated_at' => now()],
            ['cdl_id' => 2, 'title' => 'Building Work History Through Practical Experience', 'description' => 'Building Work History Through Practical Experience', 'module_url' => 'https://adelaide.edu.au/', 'created_at' => now(), 'updated_at' => now()],
            ['cdl_id' => 3, 'title' => 'Professional Profile, Resume and Social Media', 'description' => 'Professional Profile, Resume and Social Media', 'module_url' => 'https://adelaide.edu.au/', 'created_at' => now(), 'updated_at' => now()],
            ['cdl_id' => 4, 'title' => 'Developing Employability Skills', 'description' => 'Developing Employability Skills', 'module_url' => 'https://adelaide.edu.au/', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('mentor_student_mapping')->insert([
            ['staff_id' => 4, 'profile_id' => 1, 'assigned_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['staff_id' => 4, 'profile_id' => 2, 'assigned_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['staff_id' => 4, 'profile_id' => 3, 'assigned_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['staff_id' => 4, 'profile_id' => 4, 'assigned_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['staff_id' => 4, 'profile_id' => 5, 'assigned_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['staff_id' => 4, 'profile_id' => 6, 'assigned_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['staff_id' => 4, 'profile_id' => 7, 'assigned_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['staff_id' => 4, 'profile_id' => 8, 'assigned_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['staff_id' => 4, 'profile_id' => 9, 'assigned_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['staff_id' => 4, 'profile_id' => 10, 'assigned_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['staff_id' => 4, 'profile_id' => 11, 'assigned_at' => now(), 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('achievement_certs')->insert([
            [
                'profile_id' => 1, 
                'title' => 'Dean\'s Merit List 2025', 
                'body' => 'Recognised for outstanding academic achievement in the Faculty of Engineering, Computer and Mathematical Sciences.', 
                'file_path' => '/uploads/certs/deans_list_alex.pdf', 
                'issued_date' => '2025-12-10', 
                'sort_order' => '1',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'profile_id' => 1,
                'title' => 'Sustainable Energy Innovation Prize',
                'body' => 'Awarded by Engineers Australia for the best undergraduate project on solar-thermal storage systems.',
                'file_path' => '/uploads/certs/innovation_prize.pdf',
                'issued_date' => '2026-03-15',
                'sort_order' => '2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'profile_id' => 1,
                'title' => 'University Scholarship for Academic Excellence',
                'body' => 'Awarded a merit-based scholarship for maintaining a GPA above 6.5 throughout the academic year.',
                'file_path' => '/uploads/certs/scholarship_2024.pdf',
                'issued_date' => '2024-02-01',
                'sort_order' => '3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'profile_id' => 1,
                'title' => 'Best Team Project – Software Engineering',
                'body' => 'Recognised for leading a team to deliver a scalable web application using modern development practices.',
                'file_path' => '/uploads/certs/team_project_award.pdf',
                'issued_date' => '2025-06-05',
                'sort_order' => '4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'profile_id' => 1,
                'title' => 'Volunteer Recognition Award',
                'body' => 'Acknowledged for contributing over 100 hours to STEM outreach programs for high school students.',
                'file_path' => '/uploads/certs/volunteer_award.pdf',
                'issued_date' => '2023-11-18',
                'sort_order' => '5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'profile_id' => 2,
                'title' => 'Civil Engineering Scholarship',
                'body' => 'Structural Engineering Industry Excellence Scholarship for high-performing female students.',
                'file_path' => '/uploads/certs/scholarship_kate.pdf',
                'issued_date' => '2026-02-01',
                'sort_order' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('attainment_certs')->insert([
            [
                'profile_id' => 1,
                'title' => 'Construction White Card',
                'body' => 'General Construction Induction (CPCCWHS1001) required for all industrial site visits.',
                'file_path' => '/uploads/attain/white_card_alex.pdf',
                'issued_date' => '2025-05-20',
                'expiry_date' => null,
                'sort_order' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'profile_id' => 1,
                'title' => 'First Aid + CPR',
                'body' => 'HLTAID011 Provide First Aid. Includes emergency response training for remote work sites.',
                'file_path' => '/uploads/attain/first_aid_alex.pdf',
                'issued_date' => '2026-01-10',
                'expiry_date' => '2029-01-10',
                'sort_order' => '2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'profile_id' => 2,
                'title' => 'Working at Heights',
                'body' => 'Safety certification for structural site inspections and scaffolding access.',
                'file_path' => '/uploads/attain/heights_kate.pdf',
                'issued_date' => '2026-04-15',
                'expiry_date' => '2028-04-15',
                'sort_order' => '3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('competency_feedback')->insert([
            ['entry_id' => 3, 'staff_id' => 4, 'feedback_content' => 'Strong use of the pandas library. For your next iteration, try to modularise your functions to improve code reusability.', 'created_at' => now(), 'updated_at' => now()],
            ['entry_id' => 4, 'staff_id' => 5, 'feedback_content' => 'Great leadership initiative. It would be beneficial to see a short reflection on how you resolved specific technical disagreements within the team.', 'created_at' => now(), 'updated_at' => now()],
            ['entry_id' => 5, 'staff_id' => 4, 'feedback_content' => 'The LCA calculations are accurate. However, you should cross-reference the Australian Carbon Credit Units (ACCUs) standards in your future applications.', 'created_at' => now(), 'updated_at' => now()],
            ['entry_id' => 6, 'staff_id' => 6, 'feedback_content' => 'Your analysis of laminar vs. turbulent flow is correct. Ensure the lab logbook scans are attached to show the raw data collection process.', 'created_at' => now(), 'updated_at' => now()],
            ['entry_id' => 7, 'staff_id' => 5, 'feedback_content' => 'Excellent networking initiative. I recommend following up with the contacts you made on LinkedIn to maintain those professional ties.', 'created_at' => now(), 'updated_at' => now()],
            ['entry_id' => 8, 'staff_id' => 6, 'feedback_content' => 'Good understanding of PID tuning. Next time, provide a screenshot of the oscillation curves to demonstrate how you reached stability.', 'created_at' => now(), 'updated_at' => now()],
            ['entry_id' => 9, 'staff_id' => 4, 'feedback_content' => 'A very thoughtful analysis of the Challenger incident. You correctly identified the communication breakdown as a core technical failure.', 'created_at' => now(), 'updated_at' => now()],
            ['entry_id' => 10, 'staff_id' => 6, 'feedback_content' => 'The stress-strain curve plots are well-rendered. Please clarify which specific aluminum alloy grade was used in the testing notes.', 'created_at' => now(), 'updated_at' => now()],
            ['entry_id' => 11, 'staff_id' => 5, 'feedback_content' => 'Fantastic work on the MVP. Your ability to work under pressure is evident, but make sure to document the tech stack used in more detail.', 'created_at' => now(), 'updated_at' => now()],
            ['entry_id' => 12, 'staff_id' => 4, 'feedback_content' => 'Insightful observations on coastal corrosion. This entry would be even stronger with photos from the site visit as visual evidence.', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('networking_events')->insert([
            [
                'event_id' => 1,
                'profile_id' => 1,
                'event_name' => 'Engineers Australia: South Australia Gala',
                'event_datetime' => '2026-06-15 18:30:00',
                'location' => 'Adelaide Convention Centre',
                'details' => 'Met with senior structural engineers and discussed the future of sustainable infrastructure in SA.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'event_id' => 2,
                'profile_id' => 1,
                'event_name' => 'University of Adelaide STEM Careers Fair',
                'event_datetime' => '2026-03-10 10:00:00',
                'location' => 'Ingkarni Wardli Atrium',
                'details' => 'Handed out CVs to recruiters from BAE Systems and Santos. Discussed internship timelines.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'event_id' => 3,
                'profile_id' => 1,
                'event_name' => 'Tech Adelaide: AI in Engineering Seminar',
                'event_datetime' => '2026-04-22 17:00:00',
                'location' => 'Lot Fourteen, North Terrace',
                'details' => 'Learned about the integration of machine learning in predictive maintenance for mining equipment.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'event_id' => 4,
                'profile_id' => 1,
                'event_name' => 'Civil Engineering Student Society (CESS) BBQ',
                'event_datetime' => '2026-02-15 12:30:00',
                'location' => 'Barr Smith Lawns',
                'details' => 'Informal networking with final year students to discuss elective choices and project workloads.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'event_id' => 5,
                'profile_id' => 1,
                'event_name' => 'Arup: Digital Twin Workshop',
                'event_datetime' => '2026-05-05 14:00:00',
                'location' => 'Arup Adelaide Office, Grenfell St',
                'details' => 'Demonstration of BIM and digital twin applications in urban planning. Spoke with Elena Rodriguez.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'event_id' => 6,
                'profile_id' => 1,
                'event_name' => 'Renewable Energy SA Forum',
                'event_datetime' => '2026-07-12 09:00:00',
                'location' => 'Adelaide Town Hall',
                'details' => 'Panel discussion on the transition to hydrogen power in the Spencer Gulf region.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'event_id' => 7,
                'profile_id' => 1,
                'event_name' => 'Space Industry Careers Evening',
                'event_datetime' => '2026-08-19 18:00:00',
                'location' => 'Australian Space Agency, Lot Fourteen',
                'details' => 'Explored engineering opportunities within the growing South Australian space sector.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'event_id' => 8,
                'profile_id' => 1,
                'event_name' => 'Young Engineers Australia: Leadership Workshop',
                'event_datetime' => '2026-09-02 17:30:00',
                'location' => 'Engineers Australia HQ, King William St',
                'details' => 'Focus on soft skills for engineers: project management and conflict resolution strategies.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'event_id' => 9,
                'profile_id' => 1,
                'event_name' => 'Hack Adelaide: Smart Cities Weekend',
                'event_datetime' => '2026-10-15 18:00:00',
                'location' => 'Hub Central, University of Adelaide',
                'details' => '48-hour event networking with software and electrical engineers to build a traffic sensor prototype.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'event_id' => 10,
                'profile_id' => 1,
                'event_name' => 'Graduate Recruitment Dinner',
                'event_datetime' => '2026-11-10 19:00:00',
                'location' => 'The Playford Adelaide',
                'details' => 'Invitational dinner for high-performing students. Discussed potential 2027 graduate roles.',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
