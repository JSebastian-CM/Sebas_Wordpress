<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php wp_title('|', true, 'right'); ?></title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.2/tailwind.min.css" rel="stylesheet">
  <style type="text/css" data-vite-dev-id="/home/project/src/index.css">
  </style>
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

  <?php mi_tema_header(); ?>

  <main>
    <!-- Banner -->
    <section class="bg-gradient-to-br from-emerald-50 to-blue-50 py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h1 class="text-5xl font-bold text-gray-900 mb-6 leading-tight">Supporting Mental Health in<span class="text-emerald-600 block">Educational Communities</span></h1>
          <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto leading-relaxed">Comprehensive psychological services designed specifically for teachers, students, and families. We combine professional expertise with playful, artistic approaches to foster emotional well-being throughout the educational journey.</p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center"><button class="bg-emerald-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-emerald-700 transition-colors duration-200 shadow-lg">Schedule Consultation</button><button class="border-2 border-emerald-600 text-emerald-600 px-8 py-4 rounded-lg font-semibold hover:bg-emerald-600 hover:text-white transition-all duration-200">Learn More</button></div>
        </div>
      </div>
    </section>
    <!-- End Banner -->

    <?php
    mi_tema_loop();
    ?>
  </main>

  <?php wp_footer(); // Este hook llama a la función mi_tema_body_end() 
  ?>
</body>

</html>