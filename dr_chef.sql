-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2023 at 02:59 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dr_chef`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `admin_picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `admin_name`, `admin_email`, `admin_password`, `admin_picture`) VALUES
(1, 'Muhammad Sohaib Khan', 'muhammadsohaib@gmail.com', '$2y$10$VnSZrg66dADDncE/RS3nw.cihvdsg/9PpuRUCTGLN/u.UDGh76JxK', 'sohaib.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `chefs`
--

CREATE TABLE `chefs` (
  `chef_id` bigint(20) UNSIGNED NOT NULL,
  `chef_full_name` varchar(255) NOT NULL,
  `chef_username` varchar(255) NOT NULL,
  `chef_email` varchar(255) NOT NULL,
  `chef_password` varchar(255) NOT NULL,
  `chef_profile_pic` varchar(255) DEFAULT NULL,
  `chef_likes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chefs`
--

INSERT INTO `chefs` (`chef_id`, `chef_full_name`, `chef_username`, `chef_email`, `chef_password`, `chef_profile_pic`, `chef_likes`) VALUES
(1, 'Muhammad Sohaib Khan', 'sohaib_k12', 'muhammadsohaibkhan@gmail.com', '$2y$10$.cI5UL0Qi9Pqnx3EVicRE.TIMRyM48ixRHAWsExMih2Q7bM0uhfbG', 'sohaib_k12.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `chef_likes`
--

CREATE TABLE `chef_likes` (
  `chef_like_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `chef_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dietitians`
--

CREATE TABLE `dietitians` (
  `dietitian_id` bigint(20) UNSIGNED NOT NULL,
  `dietitian_full_name` varchar(255) NOT NULL,
  `dietitian_username` varchar(255) NOT NULL,
  `dietitian_email` varchar(255) NOT NULL,
  `dietitian_phone_number` varchar(255) NOT NULL,
  `dietitian_password` varchar(255) NOT NULL,
  `dietitian_profile_pic` varchar(255) DEFAULT NULL,
  `dietitian_certificate` varchar(255) NOT NULL,
  `verification_status` varchar(255) NOT NULL,
  `dietitian_likes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dietitians`
--

INSERT INTO `dietitians` (`dietitian_id`, `dietitian_full_name`, `dietitian_username`, `dietitian_email`, `dietitian_phone_number`, `dietitian_password`, `dietitian_profile_pic`, `dietitian_certificate`, `verification_status`, `dietitian_likes`) VALUES
(1, 'Muhammad Shams', 'm_shams', 'muhammadshams@gmail.com', '+923101910600', '$2y$10$Hx5kjGw2SJbekoC7If4MVusIXy6eVBpN5gWD87ZCdIDMVL0awNuXm', 'm_shams.jpg', 'm_shams.png', 'approved', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dietitian_likes`
--

CREATE TABLE `dietitian_likes` (
  `dietitian_like_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `dietitian_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `diet_plans`
--

CREATE TABLE `diet_plans` (
  `diet_plan_id` bigint(20) UNSIGNED NOT NULL,
  `dietitian_id` bigint(20) UNSIGNED NOT NULL,
  `diet_plan_duration` int(11) NOT NULL,
  `diet_plan_likes` int(11) NOT NULL,
  `diet_plan_type` varchar(255) NOT NULL,
  `diet_plan_user_type` varchar(255) NOT NULL,
  `diet_plan_weight_goal` int(11) NOT NULL,
  `diet_plan_meals` text NOT NULL,
  `diet_plan_reports` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `diet_plans`
--

INSERT INTO `diet_plans` (`diet_plan_id`, `dietitian_id`, `diet_plan_duration`, `diet_plan_likes`, `diet_plan_type`, `diet_plan_user_type`, `diet_plan_weight_goal`, `diet_plan_meals`, `diet_plan_reports`) VALUES
(1, 1, 3, 0, 'lose weight', 'cardiac patient', 5, 'm_shams_20230617122530.pdf', 0),
(2, 1, 5, 0, 'lose weight', 'blood pressure patient', 5, 'm_shams_20230617122501.pdf', 0),
(3, 1, 14, 0, 'gain weight', 'diabetic patient', 6, 'm_shams_20230617122642.pdf', 0),
(4, 1, 7, 0, 'gain weight', 'healthy person', 2, 'm_shams_20230617122746.pdf', 0),
(5, 1, 7, 0, 'lose weight', 'healthy person', 3, 'm_shams_20230617122834.pdf', 0),
(6, 1, 14, 0, 'lose weight', 'healthy person', 5, 'm_shams_20230617122914.pdf', 0);

-- --------------------------------------------------------

--
-- Table structure for table `diet_plan_likes`
--

CREATE TABLE `diet_plan_likes` (
  `diet_plan_like_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `diet_plan_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `exercise_id` bigint(20) UNSIGNED NOT NULL,
  `exercise_name` varchar(255) NOT NULL,
  `exercise_image` varchar(255) NOT NULL,
  `met_value` double(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exercises`
--

INSERT INTO `exercises` (`exercise_id`, `exercise_name`, `exercise_image`, `met_value`) VALUES
(1, 'Box Jumps', 'box_jumps.gif', 8.00),
(2, 'Crunches', 'crunches.gif', 2.50),
(3, 'Jogging', 'jogging.gif', 7.00),
(4, 'Jumping Jacks', 'jumping_jacks.gif', 8.00),
(5, 'Step Ups', 'step_ups.gif', 5.00),
(6, 'Walk', 'walk.gif', 3.50);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2022_12_31_073802_create_users_table', 1),
(3, '2023_02_08_044634_create_chefs_table', 1),
(4, '2023_02_25_070336_create_recipe_categories_table', 1),
(5, '2023_02_25_073425_create_recipes_table', 1),
(6, '2023_02_25_073454_create_recipe_nutrients_table', 1),
(7, '2023_04_01_164539_create_user_calories_table', 1),
(8, '2023_04_01_221615_create_dietitians_table', 1),
(9, '2023_04_02_000249_create_diet_plans_table', 1),
(10, '2023_04_23_070324_create_exercises_table', 1),
(11, '2023_04_25_161028_create_saved_recipes_table', 1),
(12, '2023_04_26_105244_create_recipe_likes_table', 1),
(13, '2023_04_26_105435_create_diet_plan_likes_table', 1),
(14, '2023_04_26_105511_create_dietitian_likes_table', 1),
(15, '2023_04_26_105537_create_chef_likes_table', 1),
(16, '2023_05_01_125856_create_admins_table', 1),
(17, '2023_05_01_134711_create_report_recipes_table', 1),
(18, '2023_05_07_000933_create_report_diet_plans_table', 1),
(19, '2023_05_28_052140_create_recipe_logs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `recipe_id` bigint(20) UNSIGNED NOT NULL,
  `chef_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `recipe_name` varchar(255) NOT NULL,
  `recipe_image` varchar(255) NOT NULL,
  `recipe_ingredients` text NOT NULL,
  `recipe_instructions` text NOT NULL,
  `recipe_video` varchar(255) NOT NULL,
  `recipe_servings` int(11) NOT NULL,
  `recipe_cooking_time` double(8,2) NOT NULL,
  `recipe_user_type` varchar(255) NOT NULL,
  `recipe_likes` int(11) NOT NULL,
  `recipe_reports` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`recipe_id`, `chef_id`, `category_id`, `recipe_name`, `recipe_image`, `recipe_ingredients`, `recipe_instructions`, `recipe_video`, `recipe_servings`, `recipe_cooking_time`, `recipe_user_type`, `recipe_likes`, `recipe_reports`) VALUES
(1, 1, 10, 'Banana and Dates Milk Shake', 'Banana and Dates Milk Shake_20230617105250.jpg', '20 g dates, dried, pitted\r\n250 ml milk, any (1 cup) soy for vegan option\r\n20 g almonds, raw (1 tbsp)\r\n1 banana peeled\r\n1 cup ice', 'Step 1. In a small bowl place dates in 1 tbsp of hot water. Allow dates to soften for 5 minutes.\r\nStep 2. Combine dates with milk, almonds, banana and ice in a blender. Blend until smooth.\r\nStep 3. Pour into a glass and enjoy!', 'Banana and Dates Milk Shake_20230617105250.mp4', 1, 10.00, 'healthy person', 1, 0),
(2, 1, 3, 'Chinese Chicken Curry', 'Chinese Chicken Curry_20230617105456.jpg', '4 skinless chicken breasts, cut into chunks (or use thighs or drumsticks) \r\n2 tsp cornflour \r\n1 onion, diced \r\n2 tbsp rapeseed oil \r\n1 garlic clove, crushed \r\n2 tsp curry powder \r\n1 tsp turmeric \r\n1/2 tsp ground ginger \r\n1 pinch sugar \r\n400ml chicken stock \r\n1 tsp soy sauce \r\nhandful frozen peas \r\nrice to serve', 'Step 1. Toss the chicken pieces in the cornflour and season well. Set them aside. \r\nStep 2. Fry the onion in half of the oil in a wok on a low to medium heat, until it softens – about 5-6 minutes – then add the garlic and cook for a minute. Stir in the spices and sugar and cook for another minute, then add the stock and soy sauce, bring to a simmer and cook for 20 minutes. Tip everything into a blender and blitz until smooth. \r\nStep 3. Wipe out the pan and fry the chicken in the remaining oil until it is browned all over. Tip the sauce back into the pan and bring everything to a simmer, stir in the peas and cook for 5 minutes. Add a little water if you need to thin the sauce. Serve with rice.', 'Chinese Chicken Curry_20230617105456.mp4', 6, 30.00, 'diabetic patient', 0, 0),
(3, 1, 2, 'Fried Rice', 'Fried Rice_20230617105640.jpg', '2/3 cup chopped baby carrots \r\n1/2 cup frozen green peas\r\n2 tablespoons vegetable oil\r\n1 clove garlic, minced, or to taste (Optional)\r\n2 large eggs\r\n3 cups leftover cooked white rice\r\n1 tablespoon soy sauce, or more to taste\r\n2 teaspoons sesame oil, or to taste', 'Step 1. Assemble Ingredients.\r\nStep 2. Place carrots in a small saucepan and cover with water. Bring to a low boil and cook for 3 to 5 minutes. Stir in peas, then immediately drain in a colander.\r\nStep 3. Heat a wok over high heat. Pour in vegetable oil, then stir in carrots, peas, and garlic; cook for about 30 seconds. Add eggs; stir quickly to scramble eggs with vegetables.\r\nStep 4. Stir in cooked rice. Add soy sauce and toss rice to coat. Drizzle with sesame oil and toss again.\r\nStep 5. Serve hot and enjoy!', 'Fried Rice_20230617105640.mp4', 4, 60.00, 'healthy person', 0, 0),
(4, 1, 2, 'Lamb Chops Sizzled With Garlic', 'Lamb Chops Sizzled With Garlic_20230617105901.jpg', '8 half-inch-thick lamb loin chops (about 2 pounds fatty tips trimmed)\r\nKosher salt and freshly ground black pepper\r\n1 Dried thyme\r\n3 tablespoons extra-virgin olive oil\r\n10 small garlic cloves, halved\r\n3 tablespoons water\r\n2 tablespoons fresh lemon juice\r\n2 tablespoons minced parsley\r\nCrushed red pepper', 'Step 1. Season lamb with salt and pepper and sprinkle lightly with thyme. \r\nStep 2. In a very large skillet over medium-high, heat olive oil until shimmering. Add lamb chops and garlic cook over moderately high heat until chops are browned on the bottom, about 3 minutes. \r\nStep 3. Turn chops and garlic. Cook until chops are browned and garlic is fragrant, about 2 minutes longer for medium-rare. Transfer chops to plates, leaving garlic in skillet.\r\nStep 4. Add water, lemon juice, parsley, and crushed red pepper to pan and cook, scraping up any browned bits stuck to bottom, until sizzling, about 1 minute.\r\nStep 5. Pour garlic and pan sauce over lamb chops and serve immediately.', 'Lamb Chops Sizzled With Garlic_20230617105901.mp4', 8, 80.00, 'healthy person', 0, 0),
(5, 1, 9, 'Puff Pastry Horns', 'Puff Pastry Horns_20230617110119.jpg', '2 sheets frozen puff pastry \r\n1 egg \r\n1 tbsp milk \r\n100g egg whites \r\n200g sugar \r\n60 ml water \r\n1/8 tsp salt \r\n1 tsp lemon juice', 'Step 1. Take a piece of paper and split it in half. Roll each piece into a cone shape securing the sharp end with a piece of duct tape and tuck away any excess paper. The paper cone will give stability to the future pastry cones. Wrap the paper cone with a piece of aluminum foil. Place a small lump of foil inside the cone which will help keep the weight of the dough. \r\nStep 2. Roll out each pastry sheet a little bit and cut into equal strips about 1-inch thick. Wrap the pastry around the cone, starting at the wide end with a 1/8-inch overlap of the layers, covering the form in a spiral. Place the cones onto a baking sheet.\r\nStep 3. Whisk one tablespoon of milk with the egg and brush the wrapped pastry cones. Bake at 350 degrees for 25-30 minutes or until the tops are golden brown.\r\nStep 4. Allow to cool on the baking sheet with the metal forms still inside then carefully remove the pastry cones from the cones. As you can see it’s very easy to remove the pastry cones from the metal cones or from the homemade cones.\r\nStep 5. To make the syrup, combine the sugar and water in a heavy-bottomed saucepan. With a spatula help to cover all the sugar with water.  Start to heat the syrup over medium heat until it reaches 245°F degrees.\r\nStep 6. At the same time, in a stand mixer bowl combine the egg whites, a pinch of salt and lemon juice. Whip the eggs whites on low speed until foamy, then increase the speed to medium and beat until soft peaks are formed. I have a separate post on my site where I talk in more detail about the process of making Italian meringue.\r\nStep 7. When the egg whites are at the stage of soft picks and the syrup reached the right temperature, turn the mixer on high speed and slowly pour the syrup down the side of the bowl into the egg whites. Continue the whipping until the bowl feels slightly warm to the touch. When you lift the whisk up, the meringue should be stiff and glossy.\r\nStep 8. Transfer the Italian meringue into a piping bag with any large piping tip and squeeze with firm pressure into the pastry cavities. Dust with a little bit of powdered sugar or drizzle with chocolate.', 'Puff Pastry Horns_20230617110119.mp4', 3, 90.00, 'healthy person', 1, 0),
(6, 1, 10, 'Orange Smoothie', 'Orange Smoothie_20230617110419.jpg', '1 cup fat-free, no-sugar-added vanilla frozen yogurt \r\n3/4 cup fat-free milk \r\n1/4 cup frozen no-sugar-added orange juice concentrate', 'In a blender, combine the frozen yogurt, milk and orange juice concentrate. Blend until smooth. Pour into chilled glasses and serve immediately.', 'Orange Smoothie_20230617110419.mp4', 1, 10.00, 'healthy person', 0, 0),
(7, 1, 2, 'Spiced Carrot and Lentil Soup', 'Spiced Carrot and Lentil Soup_20230617110629.jpg', '2 tsp cumin seeds\r\n1 pinch chilli flakes\r\n2 tbsp olive oil\r\n600g carrots\r\n140g split red lentils\r\n1 litre hot vegetable stock\r\n125ml milk \r\nplain yogurt and naan bread, to serve', 'Step 1. Heat a large saucepan and dry-fry 2 tsp cumin seeds and a pinch of chilli flakes for 1 min, or until they start to jump around the pan and release their aromas.\r\nStep 2. Scoop out about half with a spoon and set aside. Add 2 tbsp olive oil, 600g coarsely grated carrots, 140g split red lentils, 1l hot vegetable stock and 125ml milk to the pan and bring to the boil.\r\nStep 3. Simmer for 15 mins until the lentils have swollen and softened.\r\nStep 4. Whizz the soup with a stick blender or in a food processor until smooth (or leave it chunky if you prefer).\r\nStep 5. Season to taste and finish with a dollop of plain yogurt and a sprinkling of the reserved toasted spices. Serve with warmed naan breads.', 'Spiced Carrot and Lentil Soup_20230617110629.mp4', 1, 30.00, 'cardiac', 0, 0),
(8, 1, 5, 'Grilled Chicken and Avocado Salad', 'Grilled Chicken and Avocado Salad_20230617112827.jpg', '1 red chilli\r\n150g skinless chicken breast\r\n1 tsp olive oil\r\nSalt and pepper\r\nPinch smoked paprika or chilli powder\r\n80g avocado sliced\r\n1 lime\r\nSmall bunch chopped coriander\r\n2 sliced spring onions\r\n1 baby gem lettuce\r\n1 tomato cut into wedges\r\n30g feta crumbled', 'Step 1. Preheat a griddle pan over a high heat. Lay the chilli on the griddle and cook for 3-4 minutes, turning occasionally until lightly charred then set aside to cool.\r\nStep 2. Rub the chicken with the oil, a pinch of salt and pepper and the smoked paprika. Lay on the griddle and cook for 3-4 minutes on each side, or until cooked through, then set aside to rest. Cook the avocado on the griddle for 1 minute on each side until lightly charred.\r\nStep 3. Deseed the chilli and put into a small food processor with the lime juice and coriander then blend until smooth, adding a little more lime juice or a splash of water if needed.\r\nStep 4. Slice the chicken then mix in a large bowl along with the spring onion, lettuce and tomato. Pour over the dressing and serve topped with the feta.', 'Grilled Chicken and Avocado Salad_20230617111506.mp4', 1, 10.00, 'cardiac patient', 0, 0),
(9, 1, 1, 'Spinach and Egg Scramble with Raspberries', 'Spinach and Egg Scramble with Raspberries_20230617112554.png', '1 teaspoon canola oil\r\n1.5 cups baby spinach (1.5 ounces)\r\n2 large eggs lightly beaten\r\nPinch of kosher salt\r\nPinch of ground pepper\r\n1 slice whole-grain bread toasted\r\nHalf cup fresh raspberries', 'Heat oil in a small nonstick skillet over medium-high heat. Add spinach and cook until wilted, stirring often, 1 to 2 minutes. Transfer the spinach to a plate. Wipe the pan clean, place over medium heat and add eggs. Cook, stirring once or twice to ensure even cooking, until just set, 1 to 2 minutes. Stir in the spinach, salt and pepper. Serve the scramble with toast and raspberries.', 'Spinach and Egg Scramble with Raspberries_20230617112554.mp4', 1, 20.00, 'blood pressure patient', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_categories`
--

CREATE TABLE `recipe_categories` (
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recipe_categories`
--

INSERT INTO `recipe_categories` (`category_id`, `category_name`) VALUES
(1, 'Breakfast'),
(2, 'Lunch'),
(3, 'Dinner'),
(4, 'Seafood'),
(5, 'Salad'),
(6, 'Soup'),
(7, 'Vegetarian'),
(8, 'Fast Food'),
(9, 'Dessert'),
(10, 'Drink');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_likes`
--

CREATE TABLE `recipe_likes` (
  `recipe_like_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `recipe_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recipe_likes`
--

INSERT INTO `recipe_likes` (`recipe_like_id`, `user_id`, `recipe_id`) VALUES
(1, 1, 5),
(2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_logs`
--

CREATE TABLE `recipe_logs` (
  `recipe_log_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `recipe_id` bigint(20) UNSIGNED NOT NULL,
  `intake_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recipe_logs`
--

INSERT INTO `recipe_logs` (`recipe_log_id`, `user_id`, `recipe_id`, `intake_date`) VALUES
(1, 1, 1, '2023-06-17'),
(2, 1, 3, '2023-06-17'),
(3, 1, 4, '2023-06-17'),
(4, 1, 6, '2023-06-17');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_nutrients`
--

CREATE TABLE `recipe_nutrients` (
  `recipe_nutrients_id` bigint(20) UNSIGNED NOT NULL,
  `recipe_id` bigint(20) UNSIGNED NOT NULL,
  `recipe_calories` int(11) NOT NULL,
  `recipe_carbs` double(8,2) DEFAULT NULL,
  `recipe_protien` double(8,2) DEFAULT NULL,
  `recipe_iron` double(8,2) DEFAULT NULL,
  `recipe_dietaryfiber` double(8,2) DEFAULT NULL,
  `recipe_sugar` double(8,2) DEFAULT NULL,
  `recipe_calcium` double(8,2) DEFAULT NULL,
  `recipe_magnesium` double(8,2) DEFAULT NULL,
  `recipe_potassium` double(8,2) DEFAULT NULL,
  `recipe_sodium` double(8,2) DEFAULT NULL,
  `recipe_vitamin_c` double(8,2) DEFAULT NULL,
  `recipe_vitamin_d` double(8,2) DEFAULT NULL,
  `recipe_vitamin_b6` double(8,2) DEFAULT NULL,
  `recipe_vitamin_b12` double(8,2) DEFAULT NULL,
  `recipe_cholesterol` double(8,2) DEFAULT NULL,
  `recipe_fats` double(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recipe_nutrients`
--

INSERT INTO `recipe_nutrients` (`recipe_nutrients_id`, `recipe_id`, `recipe_calories`, `recipe_carbs`, `recipe_protien`, `recipe_iron`, `recipe_dietaryfiber`, `recipe_sugar`, `recipe_calcium`, `recipe_magnesium`, `recipe_potassium`, `recipe_sodium`, `recipe_vitamin_c`, `recipe_vitamin_d`, `recipe_vitamin_b6`, `recipe_vitamin_b12`, `recipe_cholesterol`, `recipe_fats`) VALUES
(1, 1, 51, 1.90, 1.90, 0.30, 1.10, 0.40, 24.00, 24.10, 65.50, 0.10, 0.00, 0.00, 0.00, 0.00, 0.00, 4.50),
(2, 2, 218, 0.00, 40.80, 0.67, 0.00, 0.00, 9.07, 50.77, 0.50, 81.60, 0.00, 0.00, 1.47, 0.38, 132.37, 4.75),
(3, 3, 34, 7.50, 1.45, 0.38, 0.48, 0.22, 41.02, 5.67, 90.90, 3.85, 7.08, 0.00, 0.28, 0.00, 0.00, 0.12),
(4, 4, 295, 0.00, 15.49, 1.52, 0.00, 0.00, 14.25, 19.95, 0.12, 53.20, 0.00, 0.00, 0.12, 1.94, 70.30, 25.27),
(5, 5, 9, 0.13, 1.93, 0.00, 0.00, 0.13, 1.23, 1.93, 28.70, 29.20, 0.00, 0.00, 0.00, 0.00, 0.00, 0.03),
(6, 6, 86, 12.00, 8.40, 0.10, 0.00, 12.00, 503.90, 27.20, 410.00, 128.40, 2.50, 3.00, 0.10, 0.90, 4.90, 0.40),
(7, 7, 28, 5.10, 2.00, 0.50, 0.90, 0.20, 2.80, 3.80, 54.20, 0.50, 0.40, 0.00, 0.00, 0.00, 0.00, 0.10),
(8, 8, 18, 4.00, 0.80, 0.50, 0.70, 2.40, 6.30, 10.40, 144.90, 4.10, 64.80, 0.00, 0.20, 0.00, 0.00, 0.20),
(9, 9, 9, 0.00, 0.80, 0.10, 0.00, 0.00, 3.50, 0.80, 8.70, 9.00, 0.00, 0.10, 0.00, 0.10, 23.50, 0.60);

-- --------------------------------------------------------

--
-- Table structure for table `report_diet_plans`
--

CREATE TABLE `report_diet_plans` (
  `diet_plan_report_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `diet_plan_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_recipes`
--

CREATE TABLE `report_recipes` (
  `recipe_report_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `recipe_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saved_recipes`
--

CREATE TABLE `saved_recipes` (
  `saved_recipe_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `recipe_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `saved_recipes`
--

INSERT INTO `saved_recipes` (`saved_recipe_id`, `user_id`, `recipe_id`) VALUES
(1, 1, 1),
(2, 1, 3),
(3, 1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_full_name` varchar(255) NOT NULL,
  `user_username` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_profile_pic` varchar(255) DEFAULT NULL,
  `user_age` int(11) NOT NULL,
  `user_height` double(8,2) NOT NULL,
  `user_weight` double(8,2) NOT NULL,
  `user_activity` varchar(255) NOT NULL,
  `user_disease` varchar(255) NOT NULL,
  `user_gender` varchar(255) NOT NULL,
  `user_weight_goal` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_full_name`, `user_username`, `user_email`, `user_password`, `user_profile_pic`, `user_age`, `user_height`, `user_weight`, `user_activity`, `user_disease`, `user_gender`, `user_weight_goal`) VALUES
(1, 'Ayesha Khan', 'ayn_khan', 'ayeshakhan@gmail.com', '$2y$10$bKWu/YKYxYUBQ7XqtBgG2Ogy.se6zexztst3L6Vrx0L052mwFH0l6', 'ayn_khan.jpg', 23, 5.40, 41.00, 'light', 'none', 'female', 'gain weight');

-- --------------------------------------------------------

--
-- Table structure for table `user_calories`
--

CREATE TABLE `user_calories` (
  `user_calories_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_calorie_need` int(11) NOT NULL,
  `user_daily_calorie_intake` int(11) NOT NULL,
  `date` date NOT NULL,
  `user_carbs_intake` double(8,2) DEFAULT NULL,
  `user_protien_intake` double(8,2) DEFAULT NULL,
  `user_iron_intake` double(8,2) DEFAULT NULL,
  `user_dietaryfiber_intake` double(8,2) DEFAULT NULL,
  `user_sugar_intake` double(8,2) DEFAULT NULL,
  `user_calcium_intake` double(8,2) DEFAULT NULL,
  `user_magnesium_intake` double(8,2) DEFAULT NULL,
  `user_potassium_intake` double(8,2) DEFAULT NULL,
  `user_sodium_intake` double(8,2) DEFAULT NULL,
  `user_vitamin_c_intake` double(8,2) DEFAULT NULL,
  `user_vitamin_d_intake` double(8,2) DEFAULT NULL,
  `user_vitamin_b6_intake` double(8,2) DEFAULT NULL,
  `user_vitamin_b12_intake` double(8,2) DEFAULT NULL,
  `user_cholesterol_intake` double(8,2) DEFAULT NULL,
  `user_fats_intake` double(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_calories`
--

INSERT INTO `user_calories` (`user_calories_id`, `user_id`, `user_calorie_need`, `user_daily_calorie_intake`, `date`, `user_carbs_intake`, `user_protien_intake`, `user_iron_intake`, `user_dietaryfiber_intake`, `user_sugar_intake`, `user_calcium_intake`, `user_magnesium_intake`, `user_potassium_intake`, `user_sodium_intake`, `user_vitamin_c_intake`, `user_vitamin_d_intake`, `user_vitamin_b6_intake`, `user_vitamin_b12_intake`, `user_cholesterol_intake`, `user_fats_intake`) VALUES
(1, 1, 2711, 466, '2023-06-17', 21.40, 27.24, 2.30, 1.58, 12.62, 583.17, 76.92, 566.52, 185.55, 9.58, 3.00, 0.50, 2.84, 75.20, 30.29);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admins_admin_email_unique` (`admin_email`);

--
-- Indexes for table `chefs`
--
ALTER TABLE `chefs`
  ADD PRIMARY KEY (`chef_id`),
  ADD UNIQUE KEY `chefs_chef_email_unique` (`chef_email`);

--
-- Indexes for table `chef_likes`
--
ALTER TABLE `chef_likes`
  ADD PRIMARY KEY (`chef_like_id`),
  ADD KEY `chef_likes_user_id_foreign` (`user_id`),
  ADD KEY `chef_likes_chef_id_foreign` (`chef_id`);

--
-- Indexes for table `dietitians`
--
ALTER TABLE `dietitians`
  ADD PRIMARY KEY (`dietitian_id`),
  ADD UNIQUE KEY `dietitians_dietitian_email_unique` (`dietitian_email`),
  ADD UNIQUE KEY `dietitians_dietitian_phone_number_unique` (`dietitian_phone_number`);

--
-- Indexes for table `dietitian_likes`
--
ALTER TABLE `dietitian_likes`
  ADD PRIMARY KEY (`dietitian_like_id`),
  ADD KEY `dietitian_likes_user_id_foreign` (`user_id`),
  ADD KEY `dietitian_likes_dietitian_id_foreign` (`dietitian_id`);

--
-- Indexes for table `diet_plans`
--
ALTER TABLE `diet_plans`
  ADD PRIMARY KEY (`diet_plan_id`),
  ADD KEY `diet_plans_dietitian_id_foreign` (`dietitian_id`);

--
-- Indexes for table `diet_plan_likes`
--
ALTER TABLE `diet_plan_likes`
  ADD PRIMARY KEY (`diet_plan_like_id`),
  ADD KEY `diet_plan_likes_user_id_foreign` (`user_id`),
  ADD KEY `diet_plan_likes_diet_plan_id_foreign` (`diet_plan_id`);

--
-- Indexes for table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`exercise_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`recipe_id`),
  ADD KEY `recipes_chef_id_foreign` (`chef_id`),
  ADD KEY `recipes_category_id_foreign` (`category_id`);

--
-- Indexes for table `recipe_categories`
--
ALTER TABLE `recipe_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `recipe_likes`
--
ALTER TABLE `recipe_likes`
  ADD PRIMARY KEY (`recipe_like_id`),
  ADD KEY `recipe_likes_user_id_foreign` (`user_id`),
  ADD KEY `recipe_likes_recipe_id_foreign` (`recipe_id`);

--
-- Indexes for table `recipe_logs`
--
ALTER TABLE `recipe_logs`
  ADD PRIMARY KEY (`recipe_log_id`),
  ADD KEY `recipe_logs_user_id_foreign` (`user_id`),
  ADD KEY `recipe_logs_recipe_id_foreign` (`recipe_id`);

--
-- Indexes for table `recipe_nutrients`
--
ALTER TABLE `recipe_nutrients`
  ADD PRIMARY KEY (`recipe_nutrients_id`),
  ADD KEY `recipe_nutrients_recipe_id_foreign` (`recipe_id`);

--
-- Indexes for table `report_diet_plans`
--
ALTER TABLE `report_diet_plans`
  ADD PRIMARY KEY (`diet_plan_report_id`),
  ADD KEY `report_diet_plans_user_id_foreign` (`user_id`),
  ADD KEY `report_diet_plans_diet_plan_id_foreign` (`diet_plan_id`);

--
-- Indexes for table `report_recipes`
--
ALTER TABLE `report_recipes`
  ADD PRIMARY KEY (`recipe_report_id`),
  ADD KEY `report_recipes_user_id_foreign` (`user_id`),
  ADD KEY `report_recipes_recipe_id_foreign` (`recipe_id`);

--
-- Indexes for table `saved_recipes`
--
ALTER TABLE `saved_recipes`
  ADD PRIMARY KEY (`saved_recipe_id`),
  ADD KEY `saved_recipes_user_id_foreign` (`user_id`),
  ADD KEY `saved_recipes_recipe_id_foreign` (`recipe_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `users_user_email_unique` (`user_email`);

--
-- Indexes for table `user_calories`
--
ALTER TABLE `user_calories`
  ADD PRIMARY KEY (`user_calories_id`),
  ADD KEY `user_calories_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chefs`
--
ALTER TABLE `chefs`
  MODIFY `chef_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chef_likes`
--
ALTER TABLE `chef_likes`
  MODIFY `chef_like_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dietitians`
--
ALTER TABLE `dietitians`
  MODIFY `dietitian_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dietitian_likes`
--
ALTER TABLE `dietitian_likes`
  MODIFY `dietitian_like_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `diet_plans`
--
ALTER TABLE `diet_plans`
  MODIFY `diet_plan_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `diet_plan_likes`
--
ALTER TABLE `diet_plan_likes`
  MODIFY `diet_plan_like_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exercises`
--
ALTER TABLE `exercises`
  MODIFY `exercise_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `recipe_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `recipe_categories`
--
ALTER TABLE `recipe_categories`
  MODIFY `category_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `recipe_likes`
--
ALTER TABLE `recipe_likes`
  MODIFY `recipe_like_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `recipe_logs`
--
ALTER TABLE `recipe_logs`
  MODIFY `recipe_log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `recipe_nutrients`
--
ALTER TABLE `recipe_nutrients`
  MODIFY `recipe_nutrients_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `report_diet_plans`
--
ALTER TABLE `report_diet_plans`
  MODIFY `diet_plan_report_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_recipes`
--
ALTER TABLE `report_recipes`
  MODIFY `recipe_report_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saved_recipes`
--
ALTER TABLE `saved_recipes`
  MODIFY `saved_recipe_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_calories`
--
ALTER TABLE `user_calories`
  MODIFY `user_calories_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chef_likes`
--
ALTER TABLE `chef_likes`
  ADD CONSTRAINT `chef_likes_chef_id_foreign` FOREIGN KEY (`chef_id`) REFERENCES `chefs` (`chef_id`),
  ADD CONSTRAINT `chef_likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `dietitian_likes`
--
ALTER TABLE `dietitian_likes`
  ADD CONSTRAINT `dietitian_likes_dietitian_id_foreign` FOREIGN KEY (`dietitian_id`) REFERENCES `dietitians` (`dietitian_id`),
  ADD CONSTRAINT `dietitian_likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `diet_plans`
--
ALTER TABLE `diet_plans`
  ADD CONSTRAINT `diet_plans_dietitian_id_foreign` FOREIGN KEY (`dietitian_id`) REFERENCES `dietitians` (`dietitian_id`);

--
-- Constraints for table `diet_plan_likes`
--
ALTER TABLE `diet_plan_likes`
  ADD CONSTRAINT `diet_plan_likes_diet_plan_id_foreign` FOREIGN KEY (`diet_plan_id`) REFERENCES `diet_plans` (`diet_plan_id`),
  ADD CONSTRAINT `diet_plan_likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `recipe_categories` (`category_id`),
  ADD CONSTRAINT `recipes_chef_id_foreign` FOREIGN KEY (`chef_id`) REFERENCES `chefs` (`chef_id`);

--
-- Constraints for table `recipe_likes`
--
ALTER TABLE `recipe_likes`
  ADD CONSTRAINT `recipe_likes_recipe_id_foreign` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`),
  ADD CONSTRAINT `recipe_likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `recipe_logs`
--
ALTER TABLE `recipe_logs`
  ADD CONSTRAINT `recipe_logs_recipe_id_foreign` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`),
  ADD CONSTRAINT `recipe_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `recipe_nutrients`
--
ALTER TABLE `recipe_nutrients`
  ADD CONSTRAINT `recipe_nutrients_recipe_id_foreign` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`);

--
-- Constraints for table `report_diet_plans`
--
ALTER TABLE `report_diet_plans`
  ADD CONSTRAINT `report_diet_plans_diet_plan_id_foreign` FOREIGN KEY (`diet_plan_id`) REFERENCES `diet_plans` (`diet_plan_id`),
  ADD CONSTRAINT `report_diet_plans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `report_recipes`
--
ALTER TABLE `report_recipes`
  ADD CONSTRAINT `report_recipes_recipe_id_foreign` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`),
  ADD CONSTRAINT `report_recipes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `saved_recipes`
--
ALTER TABLE `saved_recipes`
  ADD CONSTRAINT `saved_recipes_recipe_id_foreign` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`),
  ADD CONSTRAINT `saved_recipes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_calories`
--
ALTER TABLE `user_calories`
  ADD CONSTRAINT `user_calories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
