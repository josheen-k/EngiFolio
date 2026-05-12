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
            ['profile_id' => 1, 'user_id' => 1, 'degree_title' => 'Bachelor of Engineering', 'specialisation' => 'Mechanical', 'personal_intro' => 'Focused on sustainable energy systems.', 'profile_image_url' => '/src/assets/alex.jpg', 'created_at' => now(), 'updated_at' => now()],
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
            ['goal_id' => 3, 'plan_id' => 2, 'goal_description' => 'Obtain 5 new industry contacts at upcoming career fair', 'goal_status_id' => 1, 'start_date' => '2026-05-01', 'end_date' => '2026-06-01', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('goal_feedback')->insert([
            ['goal_id' => 2, 'staff_id' => 4, 'feedback_content' => 'Please write goal using SMART criteria', 'created_at' => now(), 'updated_at' => now()],
            ['goal_id' => 3, 'staff_id' => 4, 'feedback_content' => 'Well written but could use a more specific timeframe', 'created_at' => now(), 'updated_at' => now()],
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
            ['group_id' => 1, 'display_id' => '1.1', 'indicator_name' => 'Theory based understanding', 'description' => 'Comprehensive, theory based understanding of the underpinning natural and physical sciences and the engineering fundamentals applicable to the engineering discipline.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 1, 'display_id' => '1.2', 'indicator_name' => 'Mathematical/Numerical understanding', 'description' => 'Conceptual understanding of the mathematics, numerical analysis, statistics, and computer and information sciences which underpin the engineering discipline.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 1, 'display_id' => '1.3', 'indicator_name' => 'Specialist bodies of knowledge', 'description' => 'In-depth understanding of specialist bodies of knowledge within the engineering discipline.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 1, 'display_id' => '1.4', 'indicator_name' => 'Research directions', 'description' => 'Discernment of knowledge development and research directions within the engineering discipline.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 1, 'display_id' => '1.5', 'indicator_name' => 'Design practice and context', 'description' => 'Knowledge of engineering design practice and contextual factors impacting the engineering discipline.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 1, 'display_id' => '1.6', 'indicator_name' => 'Sustainable engineering practice', 'description' => 'Understanding of the scope, principles, norms, accountabilities and bounds of sustainable engineering practice in the specific discipline.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],

            ['group_id' => 2, 'display_id' => '2.1', 'indicator_name' => 'Complex problem solving', 'description' => 'Application of established engineering methods to complex engineering problem solving.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 2, 'display_id' => '2.2', 'indicator_name' => 'Techniques, tools and resources', 'description' => 'Fluent application of engineering techniques, tools and resources.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 2, 'display_id' => '2.3', 'indicator_name' => 'Synthesis and design processes', 'description' => 'Application of systematic engineering synthesis and design processes.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 2, 'display_id' => '2.4', 'indicator_name' => 'Project management approaches', 'description' => 'Application of systematic approaches to the conduct and management of engineering projects.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],

            ['group_id' => 3, 'display_id' => '3.1', 'indicator_name' => 'Ethical conduct', 'description' => 'Ethical conduct and professional accountability.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 3, 'display_id' => '3.2', 'indicator_name' => 'Effective communication', 'description' => 'Effective oral and written communication in professional and lay domains.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 3, 'display_id' => '3.3', 'indicator_name' => 'Creative and pro-active demeanour', 'description' => 'Creative, innovative and pro-active demeanour.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 3, 'display_id' => '3.4', 'indicator_name' => 'Information management', 'description' => 'Professional use and management of information.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 3, 'display_id' => '3.5', 'indicator_name' => 'Self-management', 'description' => 'Orderly management of self, and professional conduct.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
            ['group_id' => 3, 'display_id' => '3.6', 'indicator_name' => 'Team membership and leadership', 'description' => 'Effective team membership and team leadership.', 'discontinued_date' => null, 'created_at' => now(), 'updated_at' => now()],
        
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
                'profile_id' => 1,
                'title' => 'University Scholarship for Academic Excellence',
                'body' => 'Awarded a merit-based scholarship for maintaining a GPA above 6.5 throughout the academic year.',
                'file_path' => '/uploads/certs/scholarship_2024.pdf',
                'issued_date' => '2024-02-01',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'profile_id' => 1,
                'title' => 'Best Team Project – Software Engineering',
                'body' => 'Recognized for leading a team to deliver a scalable web application using modern development practices.',
                'file_path' => '/uploads/certs/team_project_award.pdf',
                'issued_date' => '2025-06-05',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'profile_id' => 1,
                'title' => 'Volunteer Recognition Award',
                'body' => 'Acknowledged for contributing over 100 hours to STEM outreach programs for high school students.',
                'file_path' => '/uploads/certs/volunteer_award.pdf',
                'issued_date' => '2023-11-18',
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
        
        DB::table('competency_feedback')->insert([
            ['entry_id' => 1, 'staff_id' => 4, 'feedback_content' => 'Excellent detail on the CAD drawings. Consider adding more information about the structural materials used.', 'created_at' => now(), 'updated_at' => now()],
            ['entry_id' => 1, 'staff_id' => 4, 'feedback_content' => 'Good start on the wiring diagrams. Please ensure you upload the safety certification as evidence for this task.', 'created_at' => now(), 'updated_at' => now()],
        ]);

    }
}