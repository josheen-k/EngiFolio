<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SEED LOOKUP TABLES (REQUIRED FIRST)
        DB::table('roles')->insert([
            ['role_id' => 1, 'role_name' => 'Admin'],
            ['role_id' => 2, 'role_name' => 'Staff'],
            ['role_id' => 3, 'role_name' => 'Student'],
        ]);

        DB::table('account_statuses')->insert([
            ['account_status_id' => 1, 'account_status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['account_status_id' => 2, 'account_status' => 'disabled', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('link_types')->insert([
            ['link_type_id' => 1, 'link_type' => 'linkedin', 'created_at' => now(), 'updated_at' => now()],
            ['link_type_id' => 2, 'link_type' => 'portfolio', 'created_at' => now(), 'updated_at' => now()],
            ['link_type_id' => 3, 'link_type' => 'resume', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('goal_statuses')->insert([
            ['goal_status_id' => 1, 'status' => 'planned', 'created_at' => now(), 'updated_at' => now()],
            ['goal_status_id' => 2, 'status' => 'in_progress', 'created_at' => now(), 'updated_at' => now()],
            ['goal_status_id' => 3, 'status' => 'completed', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('contact_method_types')->insert([
            ['contact_method_type_id' => 1, 'method_type' => 'email', 'field_size' => 255, 'created_at' => now(), 'updated_at' => now()],
            ['contact_method_type_id' => 2, 'method_type' => 'linkedin', 'field_size' => 255, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('competency_entry_levels')->insert([
            ['entry_level_id' => 1, 'competency_level' => 'Emerging', 'created_at' => now(), 'updated_at' => now()],
            ['entry_level_id' => 2, 'competency_level' => 'Developing', 'created_at' => now(), 'updated_at' => now()],
            ['entry_level_id' => 3, 'competency_level' => 'Proficient', 'created_at' => now(), 'updated_at' => now()],
            ['entry_level_id' => 4, 'competency_level' => 'Confident', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('competency_entry_statuses')->insert([
            ['entry_status_id' => 1, 'entry_status' => 'Draft', 'created_at' => now(), 'updated_at' => now()],
            ['entry_status_id' => 2, 'entry_status' => 'Submitted', 'created_at' => now(), 'updated_at' => now()],
            ['entry_status_id' => 3, 'entry_status' => 'Reviewed', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. USERS
        DB::table('users')->insert([
            ['user_id' => 1, 'username' => 'a123456', 'email' => 'alex.smith@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 2, 'username' => 'a789012', 'email' => 'kate.brown@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'role_id' => 3, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 3, 'username' => 'admin1', 'email' => 'admin1@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'role_id' => 1, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 4, 'username' => 'tutor1', 'email' => 'jane@adelaide.edu.au', 'password_hash' => Hash::make('password'), 'role_id' => 2, 'account_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. PROFILES
        DB::table('student_profiles')->insert([
            ['profile_id' => 1, 'user_id' => 1, 'first_name' => 'Alex', 'last_name' => 'Smith', 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Mechanical', 'personal_intro' => 'Focused on sustainable energy systems.', 'profile_image_url' => '/src/assets/sam.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['profile_id' => 2, 'user_id' => 2, 'first_name' => 'Kate', 'last_name' => 'Brown', 'degree_title' => 'Bachelor of Civil Engineering', 'specialisation' => 'Structural', 'personal_intro' => 'Interested in earthquake-resistant urban design.', 'profile_image_url' => '/src/assets/kate.jpeg', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. STUDENT DATA (LINKED TO PROFILE)
        DB::table('student_links')->insert([
            ['profile_id' => 1, 'link_type_id' => 1, 'link_label' => 'LinkedIn', 'link_url' => 'https://linkedin.com/in/alex-eng', 'created_at' => now(), 'updated_at' => now()],
            ['profile_id' => 2, 'link_type_id' => 2, 'link_label' => 'My Design Portfolio', 'link_url' => 'https://kate-structures.com', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('elevator_pitches')->insert([
            ['profile_id' => 1, 'pitch_text' => 'Hi, I am Alex, a Mechanical student focusing on fluid dynamics.', 'created_at' => now(), 'updated_at' => now()],
            ['profile_id' => 2, 'pitch_text' => 'I am Kate, an aspiring Structural Engineer dedicated to steel detailing.', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 5. CAREER PLANS & GOALS
        DB::table('career_development_plans')->insert([
            ['plan_id' => 1, 'profile_id' => 1, 'plan_year' => 2026, 'professional_interests' => 'Solar power', 'created_at' => now(), 'updated_at' => now()],
            ['plan_id' => 2, 'profile_id' => 2, 'plan_year' => 2026, 'professional_interests' => 'Bridge design', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('smart_goals')->insert([
            ['goal_id' => 1, 'plan_id' => 1, 'goal_description' => 'Complete Statics Course', 'goal_status_id' => 2, 'start_date' => '2026-02-01', 'end_date' => '2026-06-30', 'created_at' => now(), 'updated_at' => now()],
            ['goal_id' => 2, 'plan_id' => 2, 'goal_description' => 'Secure a summer internship', 'goal_status_id' => 1, 'start_date' => '2026-05-01', 'end_date' => '2026-08-01', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 6. NETWORKING
        DB::table('industry_contacts')->insert([
            ['contact_id' => 1, 'profile_id' => 1, 'contact_name' => 'Robert Ford', 'company' => 'Westworld Robotics', 'date_met' => '2026-05-15', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 2, 'profile_id' => 2, 'contact_name' => 'Sarah Connor', 'company' => 'Cyberdyne Systems', 'date_met' => '2026-01-20', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('industry_contact_methods')->insert([
            ['contact_id' => 1, 'method_type_id' => 1, 'method_value' => 'r.ford@westworld.com', 'created_at' => now(), 'updated_at' => now()],
            ['contact_id' => 2, 'method_type_id' => 2, 'method_value' => 'linkedin.com/in/sconnor', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 7. COMPETENCIES
        DB::table('competency_groups')->insert([
            ['group_id' => 1, 'display_id' => 'CAT1', 'group_name' => 'Knowledge Base', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 2, 'display_id' => 'CAT2', 'group_name' => 'Engineering Ability', 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 3, 'display_id' => 'CAT3', 'group_name' => 'Professional Attributes', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('competency_indicators')->insert([
            ['indicator_id' => 1, 'group_id' => 1, 'display_id' => '1.1', 'indicator_name' => 'Theory Research', 'description' => 'Comprehensive understanding...', 'created_at' => now(), 'updated_at' => now()],
            ['indicator_id' => 3, 'group_id' => 2, 'display_id' => '2.1', 'indicator_name' => 'Problem Solving', 'description' => 'Application of methods...', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('competency_entries')->insert([
            ['entry_id' => 1, 'profile_id' => 1, 'indicator_id' => 1, 'experience_title' => 'Bridge Project', 'associated_year' => 1, 'experience_tasks' => 'CAD work', 'entry_level_id' => 3, 'entry_status_id' => 2, 'start_date' => '2026-03-01', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 8. OTHER
        DB::table('cdl_modules')->insert([
            ['cdl_id' => 1, 'title' => 'Ethics', 'description' => 'Ethics 101', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('mentor_student_mapping')->insert([
            ['staff_id' => 4, 'profile_id' => 1, 'assigned_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['staff_id' => 4, 'profile_id' => 2, 'assigned_at' => now(), 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}