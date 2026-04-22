
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
        <div class="flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-brain h-8 w-8 text-emerald-600">
            <path d="M12 5a3 3 0 1 0-5.997.125 4 4 0 0 0-2.526 5.77 4 4 0 0 0 .556 6.588A4 4 0 1 0 12 18Z"></path>
            <path d="M12 5a3 3 0 1 1 5.997.125 4 4 0 0 1 2.526 5.77 4 4 0 0 1-.556 6.588A4 4 0 1 1 12 18Z"></path>
            <path d="M15 13a4.5 4.5 0 0 1-3-4 4.5 4.5 0 0 1-3 4"></path>
            <path d="M17.599 6.5a3 3 0 0 0 .399-1.375"></path>
            <path d="M6.003 5.125A3 3 0 0 0 6.401 6.5"></path>
            <path d="M3.477 10.896a4 4 0 0 1 .585-.396"></path>
            <path d="M19.938 10.5a4 4 0 0 1 .585.396"></path>
            <path d="M6 18a4 4 0 0 1-1.967-.516"></path>
            <path d="M19.967 17.484A4 4 0 0 1 18 18"></path>
        </svg>
        <span class="text-2xl font-bold text-gray-900">MindBridge</span>
    </div>
    <nav class="hidden md:flex space-x-8">
        <?php
        $args = array(
            'post_type' => 'principal',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC'
        );
        $posts = get_posts($args);
        
        foreach ($posts as $post) {
            $active = ($post->post_name === get_post_field('post_name', get_the_ID())) ? 'text-emerald-600' : 'text-gray-700';
            echo '<a href="' . get_permalink($post) . '" class="' . $active . ' hover:text-emerald-600 transition-colors duration-200">' . $post->post_title . '</a>';
        }
        ?>
   </nav>
</div>
</div>
</header>
