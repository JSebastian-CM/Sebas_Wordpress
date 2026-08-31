<div class="p-8 rounded-2xl hover:shadow-lg transition-all duration-300 hover:-translate-y-1" style="background: linear-gradient(135deg, #f0f9ff, <?php echo $bloque['color']; ?>);">
    <div class="bg-blue-600 w-16 h-16 rounded-full flex items-center justify-center mb-6">
        <img src="<?php echo $bloque['imagen'] ?? ''; ?>" alt="Imagen" class="rounded-full">
    </div>
    <h3 class="text-2xl font-bold text-gray-900 mb-4"><?php echo $bloque['titulo'] ?? ''; ?></h3>
    <p class="text-gray-600 mb-6 leading-relaxed"><?php echo $bloque['descripcion'] ?? ''; ?></p>
</div>