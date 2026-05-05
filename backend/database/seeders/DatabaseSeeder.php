<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
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
            ['user_id' => 1, 'username' => 'a123456', 'email' => 'alex.smith@adelaide.edu.au', 'first_name' => 'Alex', 'last_name' => 'Smith', 'password_hash' => Hash::make('password'), 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 2, 'username' => 'a789012', 'email' => 'kate.brown@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Kate', 'last_name' => 'Brown', 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 3, 'username' => 'admin1', 'email' => 'admin1@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Joe', 'last_name' => 'Bloggs', 'role_id' => 1, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 4, 'username' => 'tutor1', 'email' => 'jane@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'first_name' => 'Jane', 'last_name' => 'Doe',  'role_id' => 2, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('student_profiles')->insert([
            ['profile_id' => 1, 'user_id' => 1, 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Mechanical', 'personal_intro' => 'Focused on sustainable energy systems.', 'profile_image_url' => '/src/assets/sam.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['profile_id' => 2, 'user_id' => 2, 'degree_title' => 'Bachelor of Civil Engineering', 'specialisation' => 'Structural', 'personal_intro' => 'Interested in earthquake-resistant urban design.', 'profile_image_url' => '/src/assets/kate.jpeg', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('student_links')->insert([
            ['profile_id' => 1, 'link_label' => 'LinkedIn', 'link_url' => 'https://linkedin.com/in/alex-eng', 'created_at' => now(), 'updated_at' => now()],
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
            ['goal_id' => 1, 'plan_id' => 1, 'goal_description' => 'Complete Statics Course', 'goal_status_id' => 2, 'start_date' => '2026-02-01', 'end_date' => '2026-06-30', 'created_at' => now(), 'updated_at' => now()],
            ['goal_id' => 2, 'plan_id' => 2, 'goal_description' => 'Secure a summer internship', 'goal_status_id' => 1, 'start_date' => '2026-05-01', 'end_date' => '2026-08-01', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('industry_contacts')->insert([
            ['contact_id' => 1, 'profile_id' => 1, 'contact_name' => 'Robert Ford', 'company' => 'Westworld Robotics', 'date_met' => '2026-05-15', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 2, 'profile_id' => 2, 'contact_name' => 'Sarah Connor', 'company' => 'Cyberdyne Systems', 'date_met' => '2026-01-20', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('industry_contact_methods')->insert([
            ['contact_id' => 1, 'method_type' => 'Email', 'method_value' => 'r.ford@westworld.com', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 2, 'method_type' => 'LinkedIn', 'method_value' => 'linkedin.com/in/sconnor', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('competency_groups')->insert([
            ['group_id' => 1, 'display_id' => 'CAT1', 'group_name' => 'Knowledge Base', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 2, 'display_id' => 'CAT2', 'group_name' => 'Engineering Application Ability', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 3, 'display_id' => 'CAT3', 'group_name' => 'Professional And Personal Attributes', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('competency_indicators')->insert([
            ['group_id' => 1, 'display_id' => '1.1', 'indicator_name' => 'Theory based understanding', 'description' => 'Comprehensive, theory based understanding of the underpinning natural and physical sciences and the engineering fundamentals applicable to the engineering discipline.', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 1, 'display_id' => '1.2', 'indicator_name' => 'Mathematical/Numerical understanding', 'description' => 'Conceptual understanding of the mathematics, numerical analysis, statistics, and computer and information sciences which underpin the engineering discipline.', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 1, 'display_id' => '1.3', 'indicator_name' => 'Specialist bodies of knowledge', 'description' => 'In-depth understanding of specialist bodies of knowledge within the engineering discipline.', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 1, 'display_id' => '1.4', 'indicator_name' => 'Research directions', 'description' => 'Discernment of knowledge development and research directions within the engineering discipline.', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 1, 'display_id' => '1.5', 'indicator_name' => 'Design practice and context', 'description' => 'Knowledge of engineering design practice and contextual factors impacting the engineering discipline.', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 1, 'display_id' => '1.6', 'indicator_name' => 'Sustainable engineering practice', 'description' => 'Understanding of the scope, principles, norms, accountabilities and bounds of sustainable engineering practice in the specific discipline.', 'created_at' => now(), 'updated_at' => now()],

            ['group_id' => 2, 'display_id' => '2.1', 'indicator_name' => 'Complex problem solving', 'description' => 'Application of established engineering methods to complex engineering problem solving.', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 2, 'display_id' => '2.2', 'indicator_name' => 'Techniques, tools and resources', 'description' => 'Fluent application of engineering techniques, tools and resources.', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 2, 'display_id' => '2.3', 'indicator_name' => 'Synthesis and design processes', 'description' => 'Application of systematic engineering synthesis and design processes.', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 2, 'display_id' => '2.4', 'indicator_name' => 'Project management approaches', 'description' => 'Application of systematic approaches to the conduct and management of engineering projects.', 'created_at' => now(), 'updated_at' => now()],

            ['group_id' => 3, 'display_id' => '3.1', 'indicator_name' => 'Ethical conduct', 'description' => 'Ethical conduct and professional accountability.', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 3, 'display_id' => '3.2', 'indicator_name' => 'Effective communication', 'description' => 'Effective oral and written communication in professional and lay domains.', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 3, 'display_id' => '3.3', 'indicator_name' => 'Creative and pro-active demeanour', 'description' => 'Creative, innovative and pro-active demeanour.', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 3, 'display_id' => '3.4', 'indicator_name' => 'Information management', 'description' => 'Professional use and management of information.', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 3, 'display_id' => '3.5', 'indicator_name' => 'Self-management', 'description' => 'Orderly management of self, and professional conduct.', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 3, 'display_id' => '3.6', 'indicator_name' => 'Team membership and leadership', 'description' => 'Effective team membership and team leadership.', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('competency_entries')->insert([
            ['entry_id' => 1, 'profile_id' => 1, 'indicator_id' => 1, 'experience_title' => 'Bridge Project', 'associated_year' => 1, 'experience_tasks' => 'CAD work', 'entry_level_id' => 3, 'entry_status_id' => 2, 'start_date' => '2026-03-01', 'created_at' => now(), 'updated_at' => now()],
            ['entry_id' => 2, 'profile_id' => 1, 'indicator_id' => 1, 'experience_title' => 'Electrical Project', 'associated_year' => 2, 'experience_tasks' => 'Wiring', 'entry_level_id' => 1, 'entry_status_id' => 2, 'start_date' => '2026-03-01', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('cdl_modules')->insert([
            ['cdl_id' => 1, 'title' => 'Ethics', 'description' => 'Ethics 101', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('mentor_student_mapping')->insert([
            ['staff_id' => 4, 'profile_id' => 1, 'assigned_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['staff_id' => 4, 'profile_id' => 2, 'assigned_at' => now(), 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('achievement_certs')->insert([
            [
                'profile_id' => 1, 
                'title' => 'Dean\'s Merit List 2025', 
                'body' => 'Recognized for outstanding academic achievement in the Faculty of Engineering, Computer and Mathematical Sciences.', 
                'file_path' => '/uploads/certs/deans_list_alex.pdf', 
                'issued_date' => '2025-12-10', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'profile_id' => 1, 
                'title' => 'Sustainable Energy Innovation Prize', 
                'body' => 'Awarded by Engineers Australia for the best undergraduate project on solar-thermal storage systems.', 
                'file_path' => '/uploads/certs/innovation_prize.pdf', 
                'issued_date' => '2026-03-15', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'profile_id' => 2, 
                'title' => 'Civil Engineering Scholarship', 
                'body' => 'Structural Engineering Industry Excellence Scholarship for high-performing female students.', 
                'file_path' => '/uploads/certs/scholarship_kate.pdf', 
                'issued_date' => '2026-02-01', 
                'created_at' => now(), 
                'updated_at' => now()
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
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'profile_id' => 1, 
                'title' => 'First Aid + CPR', 
                'body' => 'HLTAID011 Provide First Aid. Includes emergency response training for remote work sites.', 
                'file_path' => '/uploads/attain/first_aid_alex.pdf', 
                'issued_date' => '2026-01-10', 
                'expiry_date' => '2029-01-10', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'profile_id' => 2, 
                'title' => 'Working at Heights', 
                'body' => 'Safety certification for structural site inspections and scaffolding access.', 
                'file_path' => '/uploads/attain/heights_kate.pdf', 
                'issued_date' => '2026-04-15', 
                'expiry_date' => '2028-04-15', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);
    }
}