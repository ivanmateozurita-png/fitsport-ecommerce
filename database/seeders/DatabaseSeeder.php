<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. crear usuario administrador
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@fitsport.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(), // Auto-verificar admin
        ]);

        Profile::create([
            'user_id' => $user->id,
            'role' => 'admin',
            'phone' => '123456789',
            'address' => 'Admin HQ',
            'city' => 'Quito'
        ]);

        // 2. crear categorias
        $catRopa = Category::create([
            'name' => 'Ropa',
            'description' => 'Ropa deportiva de alta calidad',
            'active' => true
        ]);

        $catCalzado = Category::create([
            'name' => 'Calzado',
            'description' => 'Zapatillas para correr y entrenar',
            'active' => true
        ]);

        $catAccesorios = Category::create([
            'name' => 'Accesorios',
            'description' => 'Complementos para tu entrenamiento',
            'active' => true
        ]);

        // Subcategorias (opcional, para el sidebar)
        $subCamisetas = Category::create(['name' => 'Camisetas', 'parent_id' => $catRopa->id]);
        $subPantalones = Category::create(['name' => 'Pantalones', 'parent_id' => $catRopa->id]);
        $subRunning = Category::create(['name' => 'Running', 'parent_id' => $catCalzado->id]);
        $subBolsos = Category::create(['name' => 'Bolsos', 'parent_id' => $catAccesorios->id]);
        $subGorras = Category::create(['name' => 'Gorras', 'parent_id' => $catAccesorios->id]);
        $subOtros = Category::create(['name' => 'Otros', 'parent_id' => $catAccesorios->id]);

        // 3. crear productos
        // ROPA PREMIUM (AI Generated)
        Product::create([
            'name' => 'Sudadera Urbana Negra',
            'description' => 'Sudadera premium de algodón de alto gramaje. Diseño minimalista y corte oversize moderno.',
            'price' => 65.00,
            'stock' => 40,
            'size' => 'L',
            'color' => 'Negro',
            'image_path' => 'assets/malefashion/img/product/hoodie_black_modern.png',
            'category_id' => $subCamisetas->id // Sudaderas en Camisetas
        ]);

        Product::create([
            'name' => 'Pantalones Deportivos Gris',
            'description' => 'Pantalones deportivos cónicos con tejido elástico. Ideales para entrenamiento o descanso.',
            'price' => 45.00,
            'stock' => 55,
            'size' => 'M',
            'color' => 'Gris',
            'image_path' => 'assets/malefashion/img/product/joggers_gray_sport.png',
            'category_id' => $subPantalones->id
        ]);

        Product::create([
            'name' => 'Cortavientos Pro Azul',
            'description' => 'Chaqueta ligera resistente al viento y lluvia ligera. Con detalles reflectantes para seguridad.',
            'price' => 89.99,
            'stock' => 25,
            'size' => 'M',
            'color' => 'Azul',
            'image_path' => 'assets/malefashion/img/product/jacket_windbreaker_blue.png',
            'category_id' => $subCamisetas->id // Chaquetas en Camisetas
        ]);

        Product::create([
            'name' => 'Camiseta Compresión Azul Marino',
            'description' => 'Base layer de compresión que mejora la circulación. Tecnología de absorción de sudor.',
            'price' => 34.50,
            'stock' => 60,
            'size' => 'L',
            'color' => 'Azul Marino',
            'image_path' => 'assets/malefashion/img/product/shirt_compression_navy.png',
            'category_id' => $subCamisetas->id
        ]);

        // ROPA ESTÁNDAR
        Product::create([
            'name' => 'Camiseta Secado Rápido Pro',
            'description' => 'Camiseta técnica de alto rendimiento. Tejido ultra ligero y transpirable.',
            'price' => 34.99,
            'stock' => 50,
            'size' => 'M',
            'color' => 'Negro',
            'image_path' => 'assets/malefashion/img/product/product_shirt.jpg',
            'category_id' => $subCamisetas->id
        ]);
        
        Product::create([
            'name' => 'Sudadera Básica',
            'description' => 'Sudadera cómoda para el gimnasio.',
            'price' => 40.00,
            'stock' => 25,
            'size' => 'XL',
            'color' => 'Negro',
            'image_path' => 'assets/malefashion/img/product/product_hoodie.png',
            'category_id' => $subCamisetas->id // Sudaderas en Camisetas
        ]);

        Product::create([
            'name' => 'Shorts Running Élite',
            'description' => 'Pantalones cortos con forro interior compresivo. Libertad de movimiento total.',
            'price' => 39.99,
            'stock' => 45,
            'size' => 'M',
            'color' => 'Gris',
            'image_path' => 'assets/malefashion/img/product/product_shorts.jpg',
            'category_id' => $subPantalones->id
        ]);

        Product::create([
            'name' => 'Polo Golf Rendimiento',
            'description' => 'Polo de tejido elástico que acompaña tu swing. Protección UV 50+.',
            'price' => 45.00,
            'stock' => 30,
            'size' => 'L',
            'color' => 'Blanco',
            'image_path' => 'assets/malefashion/img/product/product_polo.png',
            'category_id' => $subCamisetas->id
        ]);

        Product::create([
            'name' => 'Chaqueta Rompevientos',
            'description' => 'Chaqueta ligera y comprimible. Llévala contigo a cualquier aventura.',
            'price' => 79.99,
            'stock' => 25,
            'size' => 'M',
            'color' => 'Azul',
            'image_path' => 'assets/malefashion/img/product/product_jacket.jpg',
            'category_id' => $subCamisetas->id // Chaquetas en Camisetas
        ]);

        // Calzado (Expanded)
        Product::create([
            'name' => 'Zapatillas Corredor X',
            'description' => 'Diseñadas para maratones. Suela de carbono y espuma reactiva.',
            'price' => 180.00,
            'stock' => 15,
            'size' => '42',
            'color' => 'Negro/Dorado',
            'image_path' => 'assets/malefashion/img/product/product_shoes.jpg',
            'category_id' => $subRunning->id
        ]);

        Product::create([
            'name' => 'Zapatillas Urbanas Retro',
            'description' => 'Estilo clásico renovado con materiales modernos. Ideales para el día a día.',
            'price' => 95.00,
            'stock' => 30,
            'size' => '41',
            'color' => 'Blanco/Verde',
            'image_path' => 'assets/malefashion/img/product/sneaker_retro_white_green.png', // New unique image
            'category_id' => $subRunning->id
        ]);

        Product::create([
            'name' => 'Zapatillas Trail Running',
            'description' => 'Agarre superior en terrenos difíciles. Protección reforzada en la puntera.',
            'price' => 110.00,
            'stock' => 20,
            'size' => '43',
            'color' => 'Gris/Naranja',
            'image_path' => 'assets/malefashion/img/product/sneaker_trail_grey_orange.png', // New unique image
            'category_id' => $subRunning->id
        ]);

        Product::create([
            'name' => 'Zapatillas Training Pro',
            'description' => 'Estabilidad máxima para levantamiento de pesas y HIIT.',
            'price' => 85.00,
            'stock' => 25,
            'size' => '42',
            'color' => 'Negro/Rojo',
            'image_path' => 'assets/malefashion/img/product/sneaker_training_pro_black_red.png', // New unique image
            'category_id' => $subRunning->id
        ]);
        
        Product::create([
            'name' => 'Sneakers Lifestyle Chunky',
            'description' => 'La tendencia del momento con una suela voluminosa y cómoda.',
            'price' => 105.00,
            'stock' => 18,
            'size' => '40',
            'color' => 'Beige',
            'image_path' => 'assets/malefashion/img/product/sneaker_chunky_beige.png', // New unique image
            'category_id' => $subRunning->id
        ]);

        // Accesorios (Expanded)
        Product::create([
            'name' => 'Mochila Urbana',
            'description' => 'Mochila minimalista con puerto de carga USB integrado.',
            'price' => 55.00,
            'stock' => 40,
            'size' => 'Unica',
            'color' => 'Negro',
            'image_path' => 'assets/malefashion/img/product/product_bag.jpg',
            'category_id' => $subBolsos->id
        ]);
        
        Product::create([
            'name' => 'Gorra Snapback',
            'description' => 'Gorra de visera plana con logo bordado en 3D.',
            'price' => 28.00,
            'stock' => 50,
            'size' => 'Unica',
            'color' => 'Rojo',
            'image_path' => 'assets/malefashion/img/product/product_cap.png', 
            'category_id' => $subGorras->id
        ]);

        Product::create([
            'name' => 'Bolso Deportivo (Gym Bag)',
            'description' => 'Espacioso bolso para llevar todo tu equipo. Compartimento para zapatillas.',
            'price' => 45.00,
            'stock' => 35,
            'size' => 'Unica',
            'color' => 'Azul Marino',
            'image_path' => 'assets/malefashion/img/product/gym_bag_navy.png',
            'category_id' => $subBolsos->id
        ]);

        Product::create([
            'name' => 'Riñonera Running',
            'description' => 'Ligera y ajustable. Mantén tus llaves y móvil seguros mientras corres.',
            'price' => 25.00,
            'stock' => 60,
            'size' => 'Unica',
            'color' => 'Negro',
            'image_path' => 'assets/malefashion/img/product/waist_pack_running_black.png',
            'category_id' => $subBolsos->id
        ]);

        Product::create([
            'name' => 'Guantes de Entrenamiento',
            'description' => 'Protege tus manos con estos guantes acolchados y transpirables.',
            'price' => 20.00,
            'stock' => 45,
            'size' => 'M',
            'color' => 'Negro',
            'image_path' => 'assets/malefashion/img/product/gloves_training_black.png',
            'category_id' => $subOtros->id
        ]);

        // Llamar al seeder de roles para asignar permisos
        $this->call(RoleSeeder::class);
    }
}
