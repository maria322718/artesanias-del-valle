<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Sombrero Vueltiao 21 Vueltas Quinciano',
                'slug' => 'sombrero-vueltiao-21-vueltas',
                'description' => 'Símbolo cultural de la Nación Colombiana. Tejido a mano en fibra vegetal de caña flecha por artesanos indígenas de la etnia Zenú. Su flexibilidad permite doblarlo completamente y recuperar su forma intacta.',
                'artisan_name' => 'Maestro Efraín Castillo (Resguardo Indígena Zenú)',
                'origin_region' => 'Tuchín, Córdoba',
                'technique' => 'Trenzado fino tradicional en caña flecha natural y tinturada',
                'price' => 385000.0,
                'stock' => 12,
                'image_url' => 'https://images.unsplash.com/photo-1576871337622-98d48d1cf531?auto=format&fit=crop&w=800&q=80',
                'is_fragile' => false,
            ],
            [
                'name' => 'Mochila Wayuu Susu Tradicional a Una Hebra',
                'slug' => 'mochila-wayuu-susu-una-hebra',
                'description' => 'Pieza única tejida enteramente a mano con la técnica ancestral de una sola hebra (Kanasü) por mujeres de los clanes Wayuu. Diseños geométricos irrepetibles que narran los sueños y la cosmogonía de La Guajira.',
                'artisan_name' => 'Iitaima Uriana (Comunidad Mayapo)',
                'origin_region' => 'La Guajira, Colombia',
                'technique' => 'Ganchillo cerrado en hilado fino a una hebra con cordón trenzado',
                'price' => 240000.0,
                'stock' => 8,
                'image_url' => 'https://images.unsplash.com/photo-1590736969955-71cc94801759?auto=format&fit=crop&w=800&q=80',
                'is_fragile' => false,
            ],
            [
                'name' => 'Vajilla Rústica en Cerámica Negra de Ráquira (12 Piezas)',
                'slug' => 'vajilla-ceramica-negra-raquira',
                'description' => 'Auténtica alfarería cocida en hornos de leña tradicionales con técnica de ahumado mineral. Confeccionada con arcillas boyacenses seleccionadas y bruñidas a mano con piedra de río para obtener un brillo sedoso natural.',
                'artisan_name' => 'Taller Familiar Alfarero Los Robles',
                'origin_region' => 'Ráquira, Boyacá',
                'technique' => 'Modelado en torno de pie, bruñido con canto rodado y cocción en atmósfera reductora',
                'price' => 420000.0,
                'stock' => 6,
                'image_url' => 'https://images.unsplash.com/photo-1610701596007-11502861dcfa?auto=format&fit=crop&w=800&q=80',
                'is_fragile' => true, // Aplica especialmente para ArtisanInsuranceDecorator!
            ],
            [
                'name' => 'Chiva Tradicional de Barro Modelada y Pintada a Mano',
                'slug' => 'chiva-barro-pitalito',
                'description' => 'Escultura costumbrista representativa del transporte campesino de la cordillera andina. Cada detalle —racimos de plátano, costales de café, pasajeros e instrumentos musicales— es esculpido pacientemente a mano.',
                'artisan_name' => 'Maestra Cecilia Vargas (Pionera de la Chiva de Barro)',
                'origin_region' => 'Pitalito, Huila',
                'technique' => 'Modelado a mano alzada en arcilla roja y policromía al óleo',
                'price' => 195000.0,
                'stock' => 10,
                'image_url' => 'https://images.unsplash.com/photo-1582562124811-c09040d0a901?auto=format&fit=crop&w=800&q=80',
                'is_fragile' => true,
            ],
            [
                'name' => 'Plato Ornamental en Barniz de Pasto (Técnica Mopa-Mopa)',
                'slug' => 'plato-barniz-de-pasto-mopa-mopa',
                'description' => 'Patrimonio Cultural Inmaterial de la Humanidad por la UNESCO. Resina silvestre de la selva del Putumayo masticada, hervida, teñida con pigmentos vegetales y cortada con bisturí sobre madera tallada sin pegamento alguno.',
                'artisan_name' => 'Maestro Richard Valderrama (Sello de Excelencia UNESCO)',
                'origin_region' => 'San Juan de Pasto, Nariño',
                'technique' => 'Enchapado manual al calor con finísimas películas de resina Mopa-Mopa',
                'price' => 580000.0,
                'stock' => 4,
                'image_url' => 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?auto=format&fit=crop&w=800&q=80',
                'is_fragile' => true,
            ],
            [
                'name' => 'Hamaca Sanjacintera Telar Vertical Ribeteada',
                'slug' => 'hamaca-sanjacintera-telar',
                'description' => 'Tejida centímetro a centímetro en telares verticales tradicionales de la Sabana de Bolívar. Hilos de puro algodón peinado con acabados en macramé y flecos trenzados conocidos como "faldones de gala".',
                'artisan_name' => 'Asociación de Tejedoras de San Jacinto (Red de Artesanas)',
                'origin_region' => 'San Jacinto, Bolívar',
                'technique' => 'Tejido en telar vertical campesino y remates calados en macramé',
                'price' => 310000.0,
                'stock' => 9,
                'image_url' => 'https://images.unsplash.com/photo-1584100936595-c0654b55a2e2?auto=format&fit=crop&w=800&q=80',
                'is_fragile' => false,
            ],
        ];

        foreach ($products as $item) {
            Product::updateOrCreate(['slug' => $item['slug']], $item);
        }
    }
}
