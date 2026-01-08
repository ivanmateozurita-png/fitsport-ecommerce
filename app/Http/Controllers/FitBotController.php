<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FitBotController extends Controller
{
    private $respuestasSimples = [
        'saludos' => [
            'patron' => '/^(o?la|hola|buenas|hey|hi|buenos|saludos|wenas)/u',
            'respuesta' => '¡Hola! 👋 Soy FitBot, tu asistente de Fitsport. ¿Qué buscas hoy? Tengo zapatillas, ropa deportiva y más.',
        ],
        'identidad' => [
            'patron' => '/quien eres|qué eres|eres.*bot|eres.*ia/u',
            'respuesta' => 'Soy FitBot 🤖, el asistente virtual de Fitsport. Puedo ayudarte a encontrar ropa deportiva, ver precios y más.',
        ],
        'carrito' => [
            'patron' => '/carrito|cart|compra|pagar|checkout/u',
            'respuesta' => '🛒 <a href="/cart" style="color:#007bff">Ve a tu carrito</a> para revisar tus productos y proceder al pago.',
        ],
        'login' => [
            'patron' => '/iniciar|login|sesión|cuenta|registrar/u',
            'respuesta' => '👤 <a href="/login" style="color:#007bff">Inicia sesión</a> o <a href="/register" style="color:#007bff">regístrate</a> para comprar.',
        ],
        'envio' => [
            'patron' => '/envío|envio|delivery|entrega|domicilio/u',
            'respuesta' => '📦 ¡Envío GRATIS en compras +$50! Entregamos en todo el país en 3-5 días.',
        ],
        'ayuda' => [
            'patron' => '/ayuda|help|opciones/u',
            'respuesta' => 'Puedo ayudarte con:<br>🛍️ <a href="/catalog" style="color:#007bff">Catálogo</a><br>🛒 <a href="/cart" style="color:#007bff">Carrito</a><br>💰 Precios<br>📦 Envíos',
        ],
        'despedida' => [
            'patron' => '/gracias|thanks|adios|bye|chao/u',
            'respuesta' => '¡Gracias por visitar Fitsport! 🙌 <a href="/catalog" style="color:#007bff">Sigue explorando</a>',
        ],
    ];

    /**
     * Procesar mensaje del chatbot
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $userMessage = $request->input('message');

        // Obtener productos para recomendaciones
        $products = Product::with('category')->where('stock', '>', 0)->take(8)->get();

        return response()->json([
            'response' => $this->generateResponse($userMessage, $products),
        ]);
    }

    /**
     * Generar respuesta inteligente basada en palabras clave
     */
    private function generateResponse($input, $products)
    {
        $input = mb_strtolower($input);

        // Normalizar texto informal
        $input = preg_replace('/\bq\b/', 'que', $input);
        $input = preg_replace('/\bx\b/', 'por', $input);
        $input = preg_replace('/\bk\b/', 'que', $input);

        // Respuestas simples (saludos, identidad, carrito, login, envio, ayuda, despedida)
        $respuestaSimple = $this->buscarRespuestaSimple($input);
        if ($respuestaSimple) {
            return $respuestaSimple;
        }

        // Qué vendes / Qué tienes
        if (preg_match('/que.*(vendes|tienes|ofreces|hay|manejas)|vendes|ofreces|productos/u', $input)) {
            return '🛍️ En Fitsport vendemos:<br>👟 Zapatillas running<br>👕 Camisetas deportivas<br>🧥 Sudaderas<br>🩳 Shorts<br><br><a href="/catalog" style="color:#007bff">Ver todo el catálogo</a>';
        }

        // Catálogo
        if (preg_match('/catalogo|ver.*producto|mostrar|todos|lista/u', $input)) {
            return $this->respuestaCatalogo($products);
        }

        // Búsqueda de productos específicos
        $respuestaProducto = $this->buscarProductoEspecifico($input, $products);
        if ($respuestaProducto) {
            return $respuestaProducto;
        }

        // Precios
        if (preg_match('/precio|costo|cuanto|vale|barato/u', $input)) {
            return $this->respuestaPrecios($products);
        }

        // Recomendación
        if (preg_match('/recomienda|sugieres|mejor|popular/u', $input)) {
            return $this->respuestaRecomendacion($products);
        }

        // Ayuda
        if (preg_match('/ayuda|help|opciones/u', $input)) {
            return 'Puedo ayudarte con:<br>🛍️ <a href="/catalog" style="color:#007bff">Catálogo</a><br>🛒 <a href="/cart" style="color:#007bff">Carrito</a><br>💰 Precios<br>� Envíos';
        }

        // Gracias
        if (preg_match('/gracias|thanks|adios|bye|chao/u', $input)) {
            return '¡Gracias por visitar Fitsport! 🙌 <a href="/catalog" style="color:#007bff">Sigue explorando</a>';
        }

        // Por defecto
        return '🛍️ En Fitsport tenemos:<br>👟 Zapatillas<br>👕 Camisetas<br>🧥 Sudaderas<br><br>Pregunta por algo específico o <a href="/catalog" style="color:#007bff">mira el catálogo</a>';
    }

    private function buscarRespuestaSimple($input)
    {
        foreach ($this->respuestasSimples as $config) {
            if (preg_match($config['patron'], $input)) {
                return $config['respuesta'];
            }
        }

        return null;
    }

    private function respuestaCatalogo($products)
    {
        return '📦 <a href="/catalog" style="color:#007bff">Ver catálogo completo</a> - Tenemos '.$products->count().'+ productos disponibles.';
    }

    private function buscarProductoEspecifico($input, $products)
    {
        $tipos = [
            ['patron' => '/zapatilla|correr|running|tenis|zapato/u', 'keyword' => 'zapatilla', 'emoji' => '🏃', 'default' => 'zapatillas increíbles'],
            ['patron' => '/camiseta|ropa|playera|polo|dispones/u', 'keyword' => 'camiseta', 'emoji' => '👕', 'default' => 'ropa deportiva genial'],
            ['patron' => '/sudadera|hoodie|chaqueta|abrigo/u', 'keyword' => 'sudadera', 'emoji' => '🧥', 'default' => 'sudaderas disponibles'],
        ];

        foreach ($tipos as $tipo) {
            if (preg_match($tipo['patron'], $input)) {
                $producto = $products->first(fn ($p) => str_contains(mb_strtolower($p->name), $tipo['keyword']));
                if ($producto) {
                    return "{$tipo['emoji']} Te recomiendo: <a href=\"/product/{$producto->id}\" style=\"color:#007bff\">{$producto->name}</a> - \${$producto->price}";
                }

                return "{$tipo['emoji']} Tenemos {$tipo['default']}. <a href=\"/catalog\" style=\"color:#007bff\">Ver catálogo</a>";
            }
        }

        return null;
    }

    private function respuestaPrecios($products)
    {
        $cheapest = $products->sortBy('price')->first();
        if ($cheapest) {
            return "💰 Desde \${$cheapest->price}. El más barato: <a href=\"/product/{$cheapest->id}\" style=\"color:#007bff\">{$cheapest->name}</a>";
        }

        return '💰 Precios desde $29.99. <a href="/catalog" style="color:#007bff">Ver catálogo</a>';
    }

    private function respuestaRecomendacion($products)
    {
        if ($products->count() > 0) {
            $recommended = $products->random();

            return "⭐ Te recomiendo: <a href=\"/product/{$recommended->id}\" style=\"color:#007bff\">{$recommended->name}</a> - Solo \${$recommended->price}";
        }

        return '⭐ Visita nuestro catálogo para ver productos. <a href="/catalog" style="color:#007bff">Ver catálogo</a>';
    }
}
