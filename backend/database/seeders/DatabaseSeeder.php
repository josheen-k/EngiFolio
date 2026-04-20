<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Add the three roles required for the database
        DB::table('roles')->insert([
            [
                'role_id' => 1, 
                'role_name' => 'Admin'
            ],
            [
                'role_id' => 2, 
                'role_name' => 'Staff'
            ],
            [
                'role_id' => 3, 
                'role_name' => 'Student'
            ],
        ]);

        // Create an admin, tutor and two students
        DB::table('users')->insert([
            [
                'user_id' => 1, 
                'username' => 'a123456',
                'email' => 'alex.smith@adelaide.edu.au', 
                'password_hash' => Hash::make('password'), 
                'role_id' => 3, 
                'account_status' => 'active', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'user_id' => 2, 
                'username' => 'a789012', 
                'email' => 'kate.brown@adelaide.edu.au', 
                'password_hash' => Hash::make('password'), 
                'role_id' => 3, 
                'account_status' => 'active', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'user_id' => 3, 
                'username' => 'admin1', 
                'email' => 'admin1@adelaide.edu.au', 
                'password_hash' => Hash::make('password'), 
                'role_id' => 1, 
                'account_status' => 'active', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'user_id' => 4, 
                'username' => 'tutor1', 
                'email' => 'jane@adelaide.edu.au', 
                'password_hash' => Hash::make('password'), 
                'role_id' => 2, 
                'account_status' => 'active', 
                'created_at' => now(), 
                'updated_at' => now()
            ]
        ]);

        // Create profiles for the two students
        DB::table('student_profiles')->insert([
            [
                'profile_id' => 1,
                'user_id' => 1, 
                'first_name' => 'Alex', 
                'last_name' => 'Smith', 
                'degree_title' => 'Bachelor of Engineering', 
                'specialisation' => 'Mechanical',
                'personal_intro' => 'Focused on sustainable energy systems.',
                'profile_image_url' => '/src/assets/sam.jpg',   
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'profile_id' => 2,
                'user_id' => 2, 
                'first_name' => 'Kate', 
                'last_name' => 'Brown', 
                'degree_title' => 'Bachelor of Civil Engineering', 
                'specialisation' => 'Structural',
                'personal_intro' => 'Interested in earthquake-resistant urban design.',
                'profile_image_url' => '/src/assets/kate.jpeg', 
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);

        // Add links to the student profiles
        DB::table('student_links')->insert([
            [
                'profile_id' => 1, 
                'link_type' => 'linkedin', 
                'link_label' => 'LinkedIn', 
                'link_url' => 'https://linkedin.com/in/alex-eng',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'profile_id' => 2, 
                'link_type' => 'portfolio', 
                'link_label' => 'My Design Portfolio', 
                'link_url' => 'https://kate-structures.com', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);

        // Add elevator pitches
        DB::table('elevator_pitches')->insert([
            [
                'user_id' => 1, 
                'pitch_text' => 'Hi, I am Alex, a Mechanical student focusing on fluid dynamics.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'user_id' => 2, 
                'pitch_text' => 'I am Kate, an aspiring Structural Engineer dedicated to steel detailing.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);

        // Add career development plans with smart goals
        DB::table('career_development_plans')->insert([
            [
                'plan_id' => 1, 
                'user_id' => 1, 
                'plan_year' => 2026, 
                'professional_interests' => 'Solar power', 
                'created_at' => now(), 
                'updated_at' => now()],
            [
                'plan_id' => 2, 
                'user_id' => 2, 
                'plan_year' => 2026, 
                'professional_interests' => 'Bridge design', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);

        DB::table('smart_goals')->insert([
            [
                'goal_id' => 1, 
                'plan_id' => 1, 
                'goal_description' => 'Complete Statics Course', 
                'status' => 'in_progress', 
                'start_date' => '2026-02-01', 
                'end_date' => '2026-06-30', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'goal_id' => 2, 
                'plan_id' => 2, 
                'goal_description' => 'Secure a summer internship', 
                'status' => 'planned', 
                'start_date' => '2026-05-01', 
                'end_date' => '2026-08-01', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);

        // Add some industry contacts to the networking pages
        DB::table('industry_contacts')->insert([
            [
                'contact_id' => 1, 
                'user_id' => 1, 
                'contact_name' => 'Robert Ford', 
                'company' => 'Westworld Robotics', 
                'date_met' => '2026-05-15', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'contact_id' => 2, 
                'user_id' => 2, 
                'contact_name' => 'Sarah Connor', 
                'company' => 'Cyberdyne Systems', 
                'date_met' => '2026-01-20', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);

        DB::table('industry_contact_methods')->insert([
            [
                'contact_id' => 1, 
                'method_type' => 'email', 
                'method_value' => 'r.ford@westworld.com', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'contact_id' => 2, 
                'method_type' => 
                'linkedin', 
                'method_value' => 'linkedin.com/in/sconnor', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);

        // Add competency groups for competency indicators and entries
        DB::table('competency_groups')->insert([
            [
                'group_id' => 1, 
                'display_id' => 'CAT1', 
                'group_name' => 'Knowledge Base', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'group_id' => 2, 
                'display_id' => 'CAT2', 
                'group_name' => 'Engineering Ability', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'group_id' => 3, 
                'display_id' => 'CAT3', 
                'group_name' => 'Professional Attributes', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);

        DB::table('competency_indicators')->insert([
            [
                'indicator_id' => 1, 
                'group_id' => 1, 
                'display_id' => '1.1', 
                'indicator_name' => 'Theory Research', 
                'description' => 'Comprehensive, theory based understanding of underpinning sciences.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'indicator_id' => 2, 
                'group_id' => 1, 
                'display_id' => '1.2', 
                'indicator_name' => 'Mathematical Understanding', 
                'description' => 'Conceptual understanding of mathematics and statistics.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'indicator_id' => 3, 
                'group_id' => 2, 
                'display_id' => '2.1', 
                'indicator_name' => 'Problem Solving', 
                'description' => 'Application of established engineering methods to complex problems.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'indicator_id' => 4, 
                'group_id' => 2, 
                'display_id' => '2.2', 
                'indicator_name' => 'Engineering Design', 
                'description' => 'Fluent application of engineering techniques and design processes.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'indicator_id' => 5, 
                'group_id' => 3, 
                'display_id' => '3.1', 
                'indicator_name' => 'Ethical Conduct', 
                'description' => 'Ethical conduct and professional accountability.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'indicator_id' => 6, 
                'group_id' => 3, 
                'display_id' => '3.2', 
                'indicator_name' => 'Effective Communication', 
                'description' => 'Effective oral and written communication in professional domains.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);

        // Add sample competency entries
        DB::table('competency_entries')->insert([
            [
                'entry_id' => 1, 
                'user_id' => 1, 
                'indicator_id' => 1, 
                'experience_title' => 'Bridge Project', 
                'associated_year' => 1, 
                'experience_tasks' => 'CAD work', 
                'level' => 'Proficient', 
                'status' => 'Submitted', 
                'start_date' => '2026-03-01', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'entry_id' => 2, 
                'user_id' => 1, 
                'indicator_id' => 3, 
                'experience_title' => 'Water Treatment Analysis', 
                'associated_year' => 2, 
                'experience_tasks' => 'Chemical testing and flow calculation', 
                'level' => 'Developing', 
                'status' => 'Draft', 
                'start_date' => '2026-04-05', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'entry_id' => 3, 
                'user_id' => 1, 
                'indicator_id' => 5, 
                'experience_title' => 'Corporate Responsibility Seminar', 
                'associated_year' => 0, 
                'experience_tasks' => 'Review of engineering ethics codes', 
                'level' => 'Emerging', 
                'status' => 'Submitted', 
                'start_date' => '2025-11-12', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'entry_id' => 4, 
                'user_id' => 1, 
                'indicator_id' => 6, 
                'experience_title' => 'Technical Report Writing', 
                'associated_year' => 3, 
                'experience_tasks' => 'Drafting site safety documentation', 
                'level' => 'Confident', 
                'status' => 'Submitted', 
                'start_date' => '2026-02-15', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'entry_id' => 5, 
                'user_id' => 1, 
                'indicator_id' => 1, 
                'experience_title' => 
                'Site Visit Analysis', 
                'associated_year' => 4, 
                'experience_tasks' => 'Material testing', 
                'level' => 'Emerging', 
                'status' => 'Draft', 
                'start_date' => '2026-04-10', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);

        // Add modules for students to track progress
        DB::table('cdl_modules')->insert([
            [
                'cdl_id' => 1, 
                'title' => 'Ethics', 
                'description' => 'Ethics 101', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);

        // Add sample progress for students
        DB::table('student_cdl_progress')->insert([
            [
                'user_id' => 1, 
                'cdl_id' => 1, 
                'status' => 'In Progress', 
                'progress_percentage' => 75, 
                'completed_at' => null,
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'user_id' => 2, 
                'cdl_id' => 1, 
                'status' => 'Completed', 
                'progress_percentage' => 100, 
                'completed_at' => now(), 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);

        // Add link between tutor and student
        DB::table('mentor_student_mapping')->insert([
            [
                'staff_id' => 4, 
                'student_id' => 1, 
                'assigned_at' => now()
            ],
            [
                'staff_id' => 4, 
                'student_id' => 2, 
                'assigned_at' => now()
            ],
        ]);
    }
}