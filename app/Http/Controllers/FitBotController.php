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
        if (preg_match('/catálogo|catalogo|ver.*producto|mostrar|todos|lista/u', $input)) {
            return '📦 <a href="/catalog" style="color:#007bff">Ver catálogo completo</a> - Tenemos '.$products->count().'+ productos disponibles.';
        }

        // Zapatillas
        if (preg_match('/zapatilla|correr|running|tenis|zapato/u', $input)) {
            $shoe = $products->first(fn ($p) => str_contains(mb_strtolower($p->name), 'zapatilla'));
            if ($shoe) {
                return "🏃 Te recomiendo: <a href=\"/product/{$shoe->id}\" style=\"color:#007bff\">{$shoe->name}</a> por solo \${$shoe->price}";
            }

            return '🏃 Tenemos zapatillas increíbles. <a href="/catalog" style="color:#007bff">Ver catálogo</a>';
        }

        // Camisetas / Ropa
        if (preg_match('/camiseta|ropa|playera|polo|dispones/u', $input)) {
            $shirt = $products->first(fn ($p) => str_contains(mb_strtolower($p->name), 'camiseta'));
            if ($shirt) {
                return "👕 Mira esta: <a href=\"/product/{$shirt->id}\" style=\"color:#007bff\">{$shirt->name}</a> - \${$shirt->price}";
            }

            return '👕 Tenemos ropa deportiva genial. <a href="/catalog" style="color:#007bff">Ver catálogo</a>';
        }

        // Sudaderas
        if (preg_match('/sudadera|hoodie|chaqueta|abrigo|frío/u', $input)) {
            $hoodie = $products->first(fn ($p) => str_contains(mb_strtolower($p->name), 'sudadera'));
            if ($hoodie) {
                return "🧥 Te encantará: <a href=\"/product/{$hoodie->id}\" style=\"color:#007bff\">{$hoodie->name}</a> - \${$hoodie->price}";
            }

            return '🧥 Sudaderas disponibles. <a href="/catalog" style="color:#007bff">Ver catálogo</a>';
        }

        // Precios
        if (preg_match('/precio|costo|cuánto|cuanto|vale|barato/u', $input)) {
            $cheapest = $products->sortBy('price')->first();
            if ($cheapest) {
                return "💰 Desde \${$cheapest->price}. El más barato: <a href=\"/product/{$cheapest->id}\" style=\"color:#007bff\">{$cheapest->name}</a>";
            }

            return '💰 Precios desde $29.99. <a href="/catalog" style="color:#007bff">Ver catálogo</a>';
        }

        // Recomendación
        if (preg_match('/recomienda|sugieres|mejor|popular/u', $input)) {
            if ($products->count() > 0) {
                $recommended = $products->random();

                return "⭐ Te recomiendo: <a href=\"/product/{$recommended->id}\" style=\"color:#007bff\">{$recommended->name}</a> - Solo \${$recommended->price}";
            }
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
}
